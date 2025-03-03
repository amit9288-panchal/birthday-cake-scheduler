<?php

namespace App\Services;

use App\Models\CakeEvent;
use App\Models\CakeEventDeveloper;
use App\Models\Developer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class CakeDayService
{
    /**
     * @var array|string[]
     * @description: Public holiday: [ Christmas Day, Boxing Day, New Year]
     */
    protected array $holidays = ['2025-12-25', '2025-12-26', '2025-01-01'];

    /**
     * @description : Fetch developer's birthdate from table and prepared array for cake day
     *  • Check for Rules
     *  • All employees get their birthday off.
     *  • The office is closed on weekends, Christmas Day, Boxing Day, and New Year’s Day.
     *  • If the office is closed on an employee’s birthday, they get the next working day off.
     * @return void
     */
    public function calculateCakeDays(): void
    {
        $developers = Developer::all();
        $cakeDays = [];
        foreach ($developers as $dev) {
            $birthday = Carbon::parse($dev->birth_date)->year(Carbon::now()->year);
            /**
             * If employee having birthday on weekend then he/she will get day off on next working and after
             * that day cake will celebrate
             */
            if ($birthday->isWeekend() || in_array($birthday->toDateString(), $this->holidays)) {
                $cakeDay = $birthday->nextWeekday()->addDay(1);
            } else {
                $cakeDay = Carbon::parse($birthday)->addDay(1);
            }
            $cakeDays[$cakeDay->toDateString()][] = $dev->id;
        }

        $this->processCakeRules($cakeDays);
    }

    /**
     * @Description : Check for Rules
     * • A small cake is provided on the employee’s first working day after their birthday
     * • If two or more Cake Days coincide, we instead provide one large cake to share.
     * • If there is to be cake two days in a row, we instead provide one large cake on the second day.
     * • For health reasons, the day after each cake must be cake-free. Any cakes due on a cake-free day are postponed to the next working day.
     * • There is never more than one cake per day.
     * @param $cakeDays
     * @return void
     */
    private function processCakeRules(&$cakeDays): void
    {
        try {
            $dates = array_keys($cakeDays);
            $cakeEvents = [];
            $skipDates = [];
            $doubleCheckSkipDates = [];
            foreach ($dates as $index => $date) {
                $developer = $cakeDays[$date];
                if (!in_array(Carbon::parse($date)->toDateString(), array_keys($cakeEvents)) && count($developer) > 1) {
                    $cakeEvents[$date] = ['large_cakes' => 1, 'small_cakes' => 0, 'developer_id' => $developer];
                } elseif (!in_array(Carbon::parse($date)->toDateString(), array_keys($cakeEvents))) {
                    $cakeEvents[$date] = ['large_cakes' => 0, 'small_cakes' => 1, 'developer_id' => $developer];
                }
                if (isset($dates[$index + 1]) && count($cakeDays[$dates[$index + 1]]) > 0) {
                    $currentDate = Carbon::parse($date);
                    $nextDate = Carbon::parse($dates[$index + 1]);
                    $nextContinuityDate = null;
                    if ($nextDate->diffInDays(Carbon::parse($date)) === 1) {
                        /**
                         * @Description : if two consecutive days are cake day,
                         * it will celebrate both day employee birthday in next day
                         */
                        $cakeEvents[$nextDate->toDateString()] =
                            [
                                'large_cakes' => 1,
                                'small_cakes' => 0,
                                'developer_id' => array_merge(
                                    $cakeDays[$currentDate->toDateString()],
                                    $cakeDays[$nextDate->toDateString()]
                                )
                            ];
                        $nextContinuityDate = Carbon::parse($nextDate)->addDay(1);
                        $skipMergedDate = $nextDate->subDay(1);
                        $skipDates[$skipMergedDate->toDateString()] = $skipMergedDate->toDateString();
                    }
                    $doubleCheckSkipDate = null;
                    if ($nextContinuityDate && in_array($nextContinuityDate->toDateString(), $dates)) {
                        /**
                         * The day after each cake must be cake-free. Any cakes due on a cake-free day are postponed to the next working day
                         */
                        $cakeFreeDay = Carbon::parse($nextContinuityDate);
                        $cakeEvents[$cakeFreeDay->nextWeekday(1)->toDateString()] =
                            [
                                'large_cakes' => 0,
                                'small_cakes' => 1,
                                'developer_id' => $cakeDays[$nextContinuityDate->toDateString()]
                            ];
                        $doubleCheckSkipDate = Carbon::parse($nextContinuityDate)->subDay(1)->toDateString();
                        $doubleCheckSkipDates[$doubleCheckSkipDate] = $doubleCheckSkipDate;
                        $skipDates[$nextContinuityDate->toDateString()] = $nextContinuityDate->toDateString();
                    }
                }
            }
            $finalCakeEvents = (array_diff_key($cakeEvents, array_diff_key($skipDates, $doubleCheckSkipDates)));
            $this->saveCakeEvents($finalCakeEvents);
        } catch (Exception $e) {
            dd("Error : processCakeRules : " . $e->getMessage());
        }
    }

    private function saveCakeEvents($events): void
    {
        try {
            DB::beginTransaction();
            foreach ($events as $date => $event) {
                $event['cake_date'] = $date;
                $eventDeveloperIDs = $event['developer_id'];
                unset($event['developer_id']);
                $cakeEvent = CakeEvent::updateOrCreate($event, $event);
                $cakeEventID = $cakeEvent->id;
                if (count($eventDeveloperIDs) > 0) {
                    $mapDeveloperToEvents = [];
                    foreach ($eventDeveloperIDs as $eventDeveloperID) {
                        $mapDeveloperToEvent['event_id'] = $cakeEventID;
                        $mapDeveloperToEvent['developer_id'] = $eventDeveloperID;
                        $mapDeveloperToEvents[] = $mapDeveloperToEvent;
                    }
                    CakeEventDeveloper::insertOrIgnore($mapDeveloperToEvents);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd("Error : saveCakeEvents : " . $e->getMessage());
        }
    }
}

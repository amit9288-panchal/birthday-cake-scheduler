<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\CakeEvent;
use App\Models\Developer;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class CakeCalendar extends Component
{
    use WithFileUploads;

    public $birthdayList;
    public $isOpen = 0;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $cakeEventObj = CakeEvent::where('cake_date', '>=', Carbon::today())->orderBy('cake_date')->get();
        $cakeEvents = $this->prepareEvents($cakeEventObj);
        return view('livewire.cake-calendar', compact('cakeEvents'));
    }

    /**
     * @return void
     */
    public function upload()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    /**
     * @return void
     */
    public function openModal($modelName = null)
    {
        $this->isOpen = true;
    }

    /**
     * @return void
     */
    public function closeModal()
    {
        $this->isOpen = false;
    }

    /**
     * @return void
     */
    private function resetInputFields()
    {
        $this->birthdayList = '';
    }

    /**
     * Stored developers list of a birthday
     * @return void
     */

    public function store()
    {
        $this->validate([
            'birthdayList' => 'required'
        ]);
        $result = $this->birthdayList->storeAs(
            $this->getStoragePath(),
            $this->getFileName(),
            'public'
        );
        $content = file(storage_path("app/public/$result"));

        foreach ($content as $line) {
            [$name, $birth_date] = explode(',', trim($line));
            Developer::updateOrCreate([
                'name' => $name,
                'birth_date' => $birth_date,
            ]);
        }
        session()->flash(
            'message',
            'File Imported Successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    /**
     * @return string
     */
    private function getStoragePath(): string
    {
        $today = Carbon::now();
        return 'birthday_list/' . $today->format('Y') . '/' . $today->format('m') . '/' . $today->format('d');
    }

    /**
     * @return string
     */
    private function getFileName(): string
    {
        return 'developer_birthday_list_' . date('YmdHis') . '.' . $this->birthdayList->getClientOriginalExtension();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function prepareEvents(Collection $cakeEvents): array
    {
        $cakeEventArr = [];
        foreach ($cakeEvents as $cakeEvent) {
            if ($cakeEvent instanceof CakeEvent) {
                $developersList = [];
                $eventDevelopers = $cakeEvent->cakeEventDeveloper()->get();
                foreach ($eventDevelopers as $developer) {
                    $developersList[] = $developer->developer()->first()->name;
                }
                $event['date'] = $cakeEvent->cake_date;
                $event['small_cake'] = $cakeEvent->small_cakes;
                $event['large_cake'] = $cakeEvent->large_cakes;
                $event['developers'] = $developersList;
                $cakeEventArr[] = $event;
            }
        }
        return $cakeEventArr;
    }

}

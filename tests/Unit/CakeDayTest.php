<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Services\CakeDayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CakeDayTest extends TestCase
{
    use RefreshDatabase;

    public function testImportDeveloperBirthDate()
    {
        $content = file(storage_path("app/public/sample_file/birthdays.txt"));
        $totalDeveloper = 0;
        foreach ($content as $line) {
            [$name, $birth_date] = explode(',', trim($line));
            Developer::updateOrCreate([
                'name' => $name,
                'birth_date' => $birth_date,
            ]);
            $totalDeveloper++;
        }
        $totalInsertedDeveloper = Developer::get()->count();

        $this->assertEquals($totalDeveloper, $totalInsertedDeveloper);
    }

    public function testCakeCalculation()
    {
        $content = file(storage_path("app/public/sample_file/birthdays.txt"));
        foreach ($content as $line) {
            [$name, $birth_date] = explode(',', trim($line));
            Developer::updateOrCreate([
                'name' => $name,
                'birth_date' => $birth_date,
            ]);
        }

        $service = new CakeDayService();
        $service->calculateCakeDays();

        $this->assertDatabaseHas('cake_events', [
            'cake_date' => '2025-07-16',
            'large_cakes' => 1,
        ]);
    }
}

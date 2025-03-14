<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Event;
use Carbon\Carbon;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function testGetEventsBetween()
    {
        Event::create([
            'type' => 'FLT',
            'flight_number' => 'DX77',
            'departure_airport' => 'JFK',
            'arrival_airport' => 'LHR',
            'date' => Carbon::parse('2022-01-15'),
        ]);

        $response = $this->getJson('/api/events-between?start=2022-01-14&end=2022-01-16');
        $response->assertStatus(200)->assertJsonCount(1);
    }
}

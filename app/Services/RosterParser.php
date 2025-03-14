<?php

namespace App\Services;

ini_set('max_execution_time', 300); // 300 seconds (5 minutes)
use App\Models\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;



class RosterParser
{
    public function parse($filePath)
    {
        $html = Storage::get($filePath);
        $crawler = new Crawler($html);
        //dd($crawler);
        $rows = $crawler->filter('table.activityGrid_class tr')->slice(1); // Skip the header row

        $rows->each(function ($row) {
            $eventDate = trim($row->filter('.activitytablerow-date')->text(''));
    
        
            // Ensure we are not extracting the header row
            if (strtolower($eventDate) === 'date') {
                return;
            }
        
          

            $activity = $row->filter('.activitytablerow-activity')->text('');
            $departure = $row->filter('.activitytablerow-fromstn')->text('');
            $arrival = $row->filter('.activitytablerow-tostn')->text('');
            $stdZulu = $row->filter('.activitytablerow-stdutc')->text('');
            $staZulu = $row->filter('.activitytablerow-stautc')->text('');
            $ciZulu = $row->filter('.activitytablerow-checkinutc')->text('');
            $coZulu = $row->filter('.activitytablerow-checkoututc')->text('');

            $eventType = $this->getEventType($activity);
            $flightNumber = ($eventType === 'FLT') ? $activity : null;

            // Debugging: Print extracted values
// dd([
//     "Extracted Date" => $eventDate,
//     "Activity" => $activity,
//     "Departure" => $departure,
//     "Arrival" => $arrival,
//     "STD (Zulu)" => $stdZulu,
//     "STA (Zulu)" => $staZulu,
//     "Check-in (Zulu)" => $ciZulu,
//     "Check-out (Zulu)" => $coZulu,
// ]);

$events[] = [
    'type' => $eventType,
    'flight_number' => $flightNumber,
    'departure_airport' => $departure,
    'arrival_airport' => $arrival,
    'date' => $eventDate,
    'start_time' => $this->formatZuluTime($stdZulu),
    'end_time' => $this->formatZuluTime($staZulu),
    'check_in_time' => $this->formatZuluTime($ciZulu),
    'check_out_time' => $this->formatZuluTime($coZulu),
    'created_at' => now(),
    'updated_at' => now(),
];

// After processing all rows, insert all records at once
if (!empty($events)) {
    Event::insert($events);
}
        });
    }

    private function getEventType($activity)
    {
        if (preg_match('/^[A-Z]{2}\d+$/', $activity)) {
            return 'FLT';
        } elseif (stripos($activity, 'SBY') !== false) {
            return 'SBY';
        } elseif (stripos($activity, 'OFF') !== false) {
            return 'DO';
        } elseif ($activity === '') {
            return 'UNK';
        }
        return 'UNK';
    }

    // private function formatZuluTime($timeString)
    // {
    //     return ($timeString) ? Carbon::createFromFormat('Hi', $timeString)->format('H:i:s') : null;
    // }

    private function formatZuluTime($timeString)
    {
        // Remove spaces and "Z" if present (Zulu indicator)
        $timeString = trim(str_replace('Z', '', $timeString));

        if (empty($timeString)) {
            return null; // Avoid parsing empty values
        }

        try {
            // Handle different time formats
            if (preg_match('/^\d{4}$/', $timeString)) { // Matches 0930, 2230
                return Carbon::createFromFormat('Hi', $timeString)->format('H:i:s');
            } elseif (preg_match('/^\d{2}:\d{2}$/', $timeString)) { // Matches 09:30
                return Carbon::createFromFormat('H:i', $timeString)->format('H:i:s');
            } else {
               // dump("Invalid time format: " . $timeString); // Debug invalid times
                return null;
            }
        } catch (\Exception $e) {
            //dump("Time parsing failed: " . $timeString); // Debugging
            return null;
        }
    }

}

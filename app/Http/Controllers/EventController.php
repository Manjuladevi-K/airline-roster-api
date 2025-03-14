<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function getEventsBetween(Request $request)
    {
        $events = Event::whereBetween('date', [$request->start, $request->end])->get();
        return response()->json($events);
    }

    public function getFlightsNextWeek()
    {
        $start = Carbon::create(2022, 1, 14);
        $end = $start->copy()->addWeek();
        // dd($start);
        // dd($end);
        $flights = Event::where('type', 'FLT')
                        ->whereRaw("strftime('%Y-%m-%d', '2022-01-' || substr(date, 5, 2)) BETWEEN ? AND ?", [$start, $end])
                        ->get();
        return response()->json($flights);
    }

    public function getStandbyNextWeek()
    {
        $start = Carbon::create(2022, 1, 14);
        $end = $start->copy()->addWeek();

        $standby = Event::where('type', 'SBY')
                        ->whereRaw("strftime('%Y-%m-%d', '2022-01-' || substr(date, 5, 2)) BETWEEN ? AND ?", [$start, $end])
                        ->get();
        return response()->json($standby);
    }

    public function getFlightsFromLocation(Request $request)
    {
        //dd($request->location);
        $flights = Event::where('departure_airport', $request->location)->get();
        return response()->json($flights);
    }

    public function uploadRoster(Request $request)
    {
       // dd(Event::all());

        $file = $request->file('roster');
        $filePath = $file->store('rosters');

        (new \App\Services\RosterParser)->parse($filePath);

        return response()->json(['message' => 'Roster uploaded and parsed successfully']);
    }
}

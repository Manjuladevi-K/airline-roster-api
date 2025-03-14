<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 
        'flight_number', 
        'departure_airport', 
        'arrival_airport', 
        'date', 
        'start_time', 
        'end_time', 
        'check_in_time', 
        'check_out_time'
    ];
}

// Event::create([
//     'type' => 'FLT',
//     'flight_number' => 'DX123',
//     'departure_airport' => 'JFK',
//     'arrival_airport' => 'LAX',
//     'date' => '2025-03-13',
//     'start_time' => '10:00:00',
//     'end_time' => '14:00:00',
//     'check_in_time' => '09:30:00',
//     'check_out_time' => '14:30:00',
// ]);
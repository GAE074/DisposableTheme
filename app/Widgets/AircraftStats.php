<?php

namespace App\Widgets;

use App\Models\Aircraft;
use App\Contracts\Widget;
use Illuminate\Support\Facades\DB;

class AircraftStats extends Widget
{
  protected $config = ['type' => 'location']; 

  public function run() 
  {
    if ($this->config['type'] === 'location') { 
      $AircraftStats = Aircraft::select(DB::raw('count(registration) as result, airport_id'))
                ->where('status', 'A')
                ->groupBy('airport_id')
                ->orderBy('result','DESC')
                ->get();
    } elseif ($this->config['type'] === 'icao') {
      $AircraftStats = Aircraft::select(DB::raw('count(registration) as result, icao'))
                ->where('status', 'A')
                ->groupBy('icao')
                ->orderBy('icao','asc')
                ->get();
    }

    return view('widgets.aircraftstats', [
      'acstats' => $AircraftStats, 
      'type'    => $this->config['type'],
      ]);
  }
}

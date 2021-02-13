<?php

namespace App\Widgets;

use App\Models\User;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\Pirep;
use App\Contracts\Widget;
use Illuminate\Support\Facades\DB;

class AirlineStats extends Widget
{
  protected $config = ['airline' => null]; 

  public function run()
  {
    if ($this->config['airline']) { 
        $airlineid = $this->config['airline'];
        $wtotalpilot = User::where('airline_id', $airlineid)->count() ;
        $wtotalaircraft = "--" ; // TODO: Get Counts Per Airline
        $wtotalflight = Flight::where('airline_id', $airlineid)->count() ;
        $wtotalpirep = Pirep::where('airline_id', $airlineid)->where('state', 2)->count() ;
        $wtotaltime = Pirep::where('airline_id', $airlineid)->where('state', 2)->sum('flight_time') ;
        $wtotaldistance = Pirep::where('airline_id', $airlineid)->where('state', 2)->sum('distance') ;
        $wtotalfuel = Pirep::where('airline_id', $airlineid)->where('state', 2)->sum('fuel_used') ;
    } else {
        $wtotalpilot = User::count() ;
        $wtotalaircraft = Aircraft::count() ;
        $wtotalflight = Flight::count() ;
        $wtotalpirep = Pirep::where('state', 2)->count() ;
        $wtotaltime = Pirep::where('state', 2)->sum('flight_time') ;
        $wtotaldistance = Pirep::where('state', 2)->sum('distance') ;
        $wtotalfuel = Pirep::where('state', 2)->sum('fuel_used') ;
    }

    return view('widgets.airlinestats', [
        'wtotalpilot'    => $wtotalpilot,
        'wtotalaircraft' => $wtotalaircraft,
        'wtotalflight'   => $wtotalflight,
        'wtotalpirep'    => $wtotalpirep,
        'wtotaltime'     => $wtotaltime,
        'wtotaldistance' => $wtotaldistance,
        'wtotalfuel'     => $wtotalfuel,
        'wairlineid'     => $this->config['airline'],
        ]);
  }
}

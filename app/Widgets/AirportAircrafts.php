<?php

namespace App\Widgets;

use App\Models\Aircraft;
use App\Contracts\Widget;
use Illuminate\Support\Facades\DB;

class AirportAircrafts extends Widget
{
  protected $config = ['location' => 'ZZZZ'];

  public function run() 
  {
    $airportaircrafts = Aircraft::where('airport_id', $this->config['location'])
                ->where('status', 'A')
                ->orderBy('registration')
                ->get();

    return view('widgets.airportaircrafts', [
      'aircrafts' => $airportaircrafts,
      ]);
  }
}

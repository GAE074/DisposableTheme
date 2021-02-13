<?php

namespace App\Widgets;

use App\Models\Pirep;
use App\Contracts\Widget;
use Illuminate\Support\Facades\DB;

class AirportPireps extends Widget
{
  protected $config = ['location' => 'ZZZZ'];

  public function run() 
  {
    $airportpireps = Pirep::where('dpt_airport_id', $this->config['location'])
                ->where('state', 2)
                ->orWhere('arr_airport_id', $this->config['location'])
                ->where('state', 2)
                ->orderBy('submitted_at', 'desc')
                ->get();

    return view('widgets.airportpireps', [
      'pireps' => $airportpireps,
      'apicao' => $this->config['location'],
      ]);
  }
}

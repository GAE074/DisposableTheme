<?php

namespace App\Widgets;

use App\Contracts\Widget;

class FlightTimeMultiplier extends Widget
{
  public function run() { return view('widgets.ftimemultiplier'); }
}

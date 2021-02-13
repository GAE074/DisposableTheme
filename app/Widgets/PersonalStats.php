<?php

namespace App\Widgets;

use Carbon;
use App\Models\Pirep;
use App\Contracts\Widget;
use Illuminate\Support\Facades\Auth;

class PersonalStats extends Widget
{
  protected $config = ['disp' => null, 'user' => null, 'period' => null, 'type' => 'avglanding'];

  public function run() {

    $userid = $this->config['user'] ?? Auth::user()->id;
    $period = $this->config['period'];
    $periodtext = null;

    // Define Main Queries
    $manualq = Pirep::where('user_id', $userid)->where('state', 2);
    $acarsq = Pirep::where('user_id', $userid)->where('state', 2)->where('source', 1);
    // Define Day Queries
    if (is_numeric($period)) {
      $manualq = $manualq->where('submitted_at','>=', Carbon::now()->subdays($period));
      $acarsq = $acarsq->where('submitted_at','>=', Carbon::now()->subdays($period));
      $periodtext = $this->config['period'];
    }
    // Define Month And Year Queries
    if ($period === 'currentm') {
      $manualq = $manualq->whereMonth('submitted_at', '=', Carbon::now()->month);
      $acarsq = $acarsq->whereMonth('submitted_at', '=', Carbon::now()->month);
      $periodtext = Carbon::now()->format('F');
    }
    if ($period === 'lastm') {
      $manualq = $manualq->whereMonth('submitted_at', '=', Carbon::now()->subMonth()->month);
      $acarsq = $acarsq->whereMonth('submitted_at', '=', Carbon::now()->subMonth()->month);
      $periodtext = Carbon::now()->subMonth()->format('F');
    }
    if ($period === 'prevm') {
      $manualq = $manualq->whereMonth('submitted_at', '=', Carbon::now()->subMonth(2)->month);
      $acarsq = $acarsq->whereMonth('submitted_at', '=', Carbon::now()->subMonth(2)->month);
      $periodtext = Carbon::now()->subMonth(2)->format('F');
    }
    if ($period === 'currenty') {
      $manualq = $manualq->whereYear('submitted_at', '=', Carbon::now()->year);
      $acarsq = $acarsq->whereYear('submitted_at', '=', Carbon::now()->year);
      $periodtext = Carbon::now()->format('Y');
    }
    if ($period === 'lasty') {
      $manualq = $manualq->whereYear('submitted_at', '=', Carbon::now()->subYear()->year);
      $acarsq = $acarsq->whereYear('submitted_at', '=', Carbon::now()->subYear()->year);
      $periodtext = Carbon::now()->subYear()->format('Y');
    }

    // Average Landing Rate - Acars Only
    if ($this->config['type'] === 'avglanding') { $PersonalStats = $acarsq->avg('landing_rate'); }
    // Average Score - Acars Only
    if ($this->config['type'] === 'avgscore') { $PersonalStats = $acarsq->avg('score'); }
    // Average Distance - Acars Only
    if ($this->config['type'] === 'avgdistance') { $PersonalStats = $acarsq->avg('distance'); }
    // Total Distance - Acars Only
    if ($this->config['type'] === 'totdistance') { $PersonalStats = $acarsq->sum('distance'); }
    // Average Time
    if ($this->config['type'] === 'avgtime') { $PersonalStats = $manualq->avg('flight_time'); }
    // Total Time
    if ($this->config['type'] === 'tottime') { $PersonalStats = $manualq->sum('flight_time'); }
    // Average Fuel
    if ($this->config['type'] === 'avgfuel') { $PersonalStats = $manualq->avg('fuel_used'); }
    // Total Fuel
    if ($this->config['type'] === 'totfuel') { $PersonalStats = $manualq->sum('fuel_used'); }
    // Total Flights
    if ($this->config['type'] === 'totflight') { $PersonalStats = $manualq->count('score'); }

    // Add Unit Type If Necessary and Format The Result
    if ($this->config['type'] === 'avglanding') { $PersonalStats = number_format(ceil($PersonalStats)) . ' ft/min'; }
    if ($this->config['type'] === 'avgscore') { $PersonalStats = number_format(ceil($PersonalStats)); }
    if ($this->config['type'] === 'avgtime' || $this->config['type'] === 'tottime') { ceil($PersonalStats); }
    if ($this->config['type'] === 'avgdistance' || $this->config['type'] === 'totdistance') {
      if(setting('units.distance') === 'km') { $PersonalStats = number_format(ceil($PersonalStats * 1.852)) . ' Km';
      } else { $PersonalStats = number_format(ceil($PersonalStats)) . ' Nm'; }
    }
    if ($this->config['type'] === 'avgfuel' || $this->config['type'] === 'totfuel') {
      if(setting('units.weight') === 'kg') { $PersonalStats = number_format(ceil($PersonalStats / 2.205)) . ' Kgs';
      } else { $PersonalStats = number_format(ceil($PersonalStats)) . ' Lbs'; }
    }

    // Return Data To Blade
    return view('widgets.personalstats', [
      'pstat'  => $PersonalStats,
      'type' 	 => $this->config['type'],
      'disp'   => $this->config['disp'],
      'period' => $periodtext,
      ]);
  }
}

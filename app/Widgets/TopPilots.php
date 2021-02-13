<?php

namespace App\Widgets;

use App\Models\Pirep;
use App\Contracts\Widget;
use Illuminate\Support\Facades\DB;

class TopPilots extends Widget
{
  protected $config = ['count' => 3, 'type' => 'flights'];

  public function run()
  {
    if ($this->config['type'] === 'flights') { $rawsql = DB::raw('count(user_id) as totals'); }
    if ($this->config['type'] === 'distance') { $rawsql = DB::raw('sum(distance) as totals'); }
    if ($this->config['type'] === 'time') { $rawsql = DB::raw('sum(flight_time) as totals'); }

    $tpilots = Pirep::select('user_id', $rawsql)
          ->where('state', 2)
          ->orderby('totals', 'desc')
          ->groupby('user_id')
          ->take($this->config['count'])
          ->get();

    return view('widgets.toppilots', [
      'tpilots' => $tpilots,
      'type'    => $this->config['type'],
      'count'   => $this->config['count'],
    ]);
  }
}

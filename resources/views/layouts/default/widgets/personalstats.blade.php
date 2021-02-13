{{-- DEFINE YOUR TEXT HERE IF NEEDED ---}}
@php
  $last = 'Last' ;
  $days = 'Days' ;
  $noreports = 'No Reports' ;
  $statname = 'Personal Stats Widget' ;
  if($type === 'avglanding') { $statname = 'Average Landing Rate' ;}
  if($type === 'avgscore') { $statname = 'Average Score' ;}
  if($type === 'avgdistance') { $statname = 'Average Distance' ;}
  if($type === 'totdistance') { $statname = 'Total Distance' ;}
  if($type === 'avgtime') { $statname = 'Average Flight Time' ;}
  if($type === 'tottime') { $statname = 'Total Flight Time' ;}
  if($type === 'avgfuel') { $statname = 'Average Fuel Burn' ;}
  if($type === 'totfuel') { $statname = 'Total Fuel Burn' ;}
  if($type === 'totflight') { $statname = 'Total Flights' ;}
@endphp
@if($disp === 'full')
  <div class="card mb-2 text-center">
    <div class="card-body p-1">
      <h5 class="m-1 p-0">
        @if($pstat)
          @if($type === 'avgtime' || $type === 'tottime')
            @minutestotime($pstat)
          @else
            {{ $pstat }}
          @endif
        @else
          {{ $noreports }}
        @endif
      </h5>
    </div>
    <div class="card-footer p-1">
      {{ $statname }}
      @if(is_numeric($period) && $period < 2000) ({{ $last }} {{ $period }} {{ $days }})
      @else {{ $period }}
      @endif
    </div>
  </div>
@else
  @if($pstat)
    @if($type === 'avgtime' || $type === 'tottime')
      @minutestotime($pstat)
    @else
      {{ $pstat }}
    @endif
  @else
    {{ $noreports }}
  @endif
@endif
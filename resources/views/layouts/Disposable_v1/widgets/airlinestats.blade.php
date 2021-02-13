<div class="card mb-2">
  <div class="card-header p-1">
      <h5 class="m-1 p-0">@if($wairlineid) Airline @endif Statistics<i class="fas fa-folder-open float-right"></i></h5>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm table-striped table-borderless mb-0 text-left">
      <tr>
        <th style="width: 50%">Pilots</th>
        <td class="text-right">{{ $wtotalpilot }}</td>
      </tr>
      @if(!$wairlineid)
        <tr>
          <th>Aircrafts</th>
          <td class="text-right">{{ $wtotalaircraft }}</td>
        </tr>
      @endif
      <tr>
        <th>Flights</th>
        <td class="text-right">{{ $wtotalflight }}</td>
      </tr>
      <tr>
        <th style="width: 50%">Pireps</th>
        <td class="text-right">{{ $wtotalpirep }}</td>
      </tr>
      <tr>
        <th>Flight Time</th>
        <td class="text-right"> @minutestotime($wtotaltime)</td>
      </tr>
      @if($wtotaldistance)
        <tr>
          <th>Distance</th>
          <td class="text-right">
          @if (setting('units.distance') === 'km') {{ number_format(round($wtotaldistance * 1.852)) }}
          @else {{ number_format(round($wtotaldistance)) }}
          @endif {{ setting('units.distance') }}
          </td>
        </tr>
      @endif
      <tr>
        <th>Fuel Burn</th>
        <td class="text-right">
          @if (setting('units.weight') === 'kg') {{ number_format(round($wtotalfuel / 2.205)) }}
          @else {{ number_format(round($wtotalfuel)) }}
          @endif {{ setting('units.weight') }}
        </td>
      </tr>
    </table>
  </div>
</div>

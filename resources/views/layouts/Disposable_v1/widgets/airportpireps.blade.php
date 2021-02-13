<div class="card mb-2">
  <div class="card-header p-1"><h5 class="m-1 p-0">Pireps<i class="fas fa-upload float-right"></i></h5></div>
  <div class="card-body p-0 overflow-auto">
    @if(!$pireps->count())
      <div class="text-center m-1">No Pireps !</div>
    @else
      <table class="table table-sm table-striped table-borderless mb-0 text-center">
        <tr>
          <th class="text-left">Ident</th>
          <th>Dep</th>
          <th>Arr</th>
          <th>Aircraft</th>
          <th>F.Time</th>
          <th>Pilot</th>
        </tr>
        @foreach($pireps as $pirep)
          <tr>
            <td class="text-left">{{ $pirep->airline->icao}}{{ $pirep->ident }}</td>
            <td>
              @if ($apicao <> $pirep->dpt_airport_id)
                <a href="{{ route('frontend.airports.show', [$pirep->dpt_airport_id]) }}">{{ $pirep->dpt_airport->icao }}</a>
              @else
                {{ $pirep->dpt_airport->icao }}
              @endif
            </td>
            <td>
              @if ($apicao <> $pirep->arr_airport_id)
                <a href="{{ route('frontend.airports.show', [$pirep->arr_airport_id]) }}">{{ $pirep->arr_airport->icao }}</a>
              @else
                {{ $pirep->arr_airport->icao }}
              @endif
            </td>
            <td>@if($pirep->aircraft) {{ $pirep->aircraft->registration }} ({{ $pirep->aircraft->icao }}) @endif</td>
            <td>@minutestotime($pirep->flight_time)</td>
            <td><a href="{{ route('frontend.users.show.public', [$pirep->user->id]) }}">{{ $pirep->user->name_private }}</a></td>
          </tr>
        @endforeach
      </table>
    @endif
  </div>
</div>
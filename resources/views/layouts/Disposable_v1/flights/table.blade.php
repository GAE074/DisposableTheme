@foreach($flights as $flight)
  <div class="card mb-2">
    <div class="card-header p-1 align-items-center">
      <h5 class="p-0 ml-2 mr-2 mt-0 mb-0 ">
        @if(optional($flight->airline)->logo)
          <img src="{{ $flight->airline->logo }}" alt="{{$flight->airline->name}}" class="p-0 mr-2 img-mh40"/>
        @endif
        <a href="{{ route('frontend.flights.show', [$flight->id]) }}">
          {{ $flight->airline->iata }} {{ $flight->flight_number }}
          @if(filled($flight->callsign)) [{{ $flight->airline->icao }} {{ $flight->callsign }}] @endif
        </a>
        <span class="float-right">
          <button class="btn btn-round btn-icon" title="Show/Hide Basic Details" type="button" data-toggle="collapse" data-target="#Details{{$flight->id}}" aria-expanded="false" aria-controls="Details{{$flight->id}}">
          <i class="fas fa-arrows-alt-v p-0 m-0"></i></button>
          <a href="{{ route('frontend.flights.show', [$flight->id]) }}" class="btn btn-round btn-icon" title="Full Flight Details"><i class="fas fa-info-circle p-0 m-0"></i></a>
        {{--
          !!! IMPORTANT NOTE !!!
           Don't remove the "save_flight" class, or the x-id attribute. It will break the AJAX to save/delete
           "x-saved-class" is the class to add/remove if the bid exists or not. If you change it, remember to change it in the in-array line as well
        --}}
        @if (!setting('pilots.only_flights_from_current') || $flight->dpt_airport_id == Auth::user()->current_airport->icao)
          <button class="btn btn-round btn-icon btn-icon-mini save_flight {{ in_array($flight->id, $saved, true) ? 'btn-info':'' }}"
                  x-id="{{ $flight->id }}"
                  x-saved-class="btn-info"
                  type="button"
                  title="@lang('flights.addremovebid')">
                  <i class="fas fa-map-marker p-0 m-0"></i>
          </button>
        @endif
        </span>
      </h5>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm table-borderless table-striped text-center mb-1">
        <tr>
          <th class="text-left" style="width: 50%;">
            <a href="{{ route('frontend.airports.show', [$flight->dpt_airport_id]) }}">{{ $flight->dpt_airport_id }} {{ $flight->dpt_airport->name ?? '' }}</a>
          </th>
          <th class="text-right" style="width: 50%;">
            <a href="{{ route('frontend.airports.show', [$flight->arr_airport_id]) }}">{{ $flight->arr_airport_id }} {{ $flight->arr_airport->name ?? '' }}</a>
          </th>
        </tr>
        @if($flight->dpt_time && $flight->arr_time)
          <tr>
            <td class="text-left" style="width: 50%;">{{ $flight->dpt_time }}</td>
            <td class="text-right" style="width: 50%;">{{ $flight->arr_time }}</td>
          </tr>
        @endif
      </table>
      <div id="Details{{$flight->id}}" class="collapse">
        <table class="table table-sm table-striped table-borderless mb-0">
            @if($flight->alt_airport_id)
              <tr>
                <th style="width: 15%;">@lang('flights.alternateairport')</th>
                <td>
                  <a href="{{route('frontend.airports.show', [$flight->alt_airport_id])}}">
                    {{ $flight->alt_airport_id }} {{ $flight->alt_airport->name ?? '' }}
                  </a>
                </td>
              </tr>
            @endif
            @if(filled($flight->route))
              <tr>
                <th style="width: 15%;">@lang('flights.route')</th>
                <td>{{ strtoupper($flight->route) }}</td>
              </tr>
            @endif
            @if(filled($flight->level))
              <tr>
                <th style="width: 15%;">@lang('flights.level')</th>
                <td>{{ number_format($flight->level) }} {{ setting('units.altitude') }}</td>
              </tr>
            @endif
            @if($flight->flight_time)
              <tr>
                <th style="width: 15%;">@lang('flights.flighttime')</th>
                <td>@minutestotime($flight->flight_time)</td>
              </tr>
            @endif
            @if(filled($flight->distance))
              <tr>
                <th style="width: 15%;">@lang('common.distance')</th>
                <td>
                  @if (setting('units.distance') === 'km') 
                    {{ number_format($flight->distance * 1.852) }} 
                  @else 
                    {{ number_format($flight->distance) }} 
                  @endif 
                    {{ setting('units.distance') }}
                </td>
              </tr>
            @endif
            @if(filled($flight->notes))
              <tr>
                <th style="width: 15%;">{{ trans_choice('common.note', 2) }}</th>
                <td>{{ $flight->notes }}</td>
              </tr>
            @endif
            @if($flight->subfleets->count())
              <tr>
                <th style="width: 15%;">SubFleets</th>
                <td>
                  @foreach($flight->subfleets as $subfleet)
                    @if(!$loop->first) &bull; @endif {{ $subfleet->name }}
                  @endforeach
                </td>
              </tr>
            @endif
        </table>
      </div>
    </div>
    <div class="card-footer p-1">
      @if($flight->flight_type)
        <h5 class="mb-0 mt-0"><span class="badge badge-light float-left mr-2 ml-2">{{ \App\Models\Enums\FlightType::label($flight->flight_type) }}</span></h5>
      @endif
      @if($flight->route_code)
        <h5 class="mb-0 mt-0"><span class="badge badge-warning text-black float-left mr-2 ml-2">Code: {{ $flight->route_code }}</span></h5>
      @endif
      @if($flight->route_leg)
        <h5 class="mb-0 mt-0"><span class="badge badge-warning text-black float-left mr-2 ml-2">Leg #{{ $flight->route_leg }}</span></h5>
      @endif
      <div class="col text-right">
        @if($simbrief !== false)
          @if($flight->simbrief && $flight->simbrief->user_id == Auth::user()->id)
            <a href="{{ route('frontend.simbrief.briefing', $flight->simbrief->id) }}" class="btn btn-sm btn-secondary">View Simbrief OFP</a>
          @elseif($simbrief_bids === false || ($simbrief_bids === true && in_array($flight->id, $saved, true)))
            <a href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}" class="btn btn-sm btn-primary">Create SimBrief OFP</a>
          @endif
        @endif
        <a href="{{ route('frontend.pireps.create') }}?flight_id={{ $flight->id }}" class="btn btn-sm btn-info">New Manual Pirep</a>
      </div>
    </div>
  </div>
@endforeach

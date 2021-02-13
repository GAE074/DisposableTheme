@extends('app')
@section('title', trans_choice('common.flight', 1).' '.$flight->ident)

@section('content')
  <h3 class="card-title">Flight Details</h3>

  <div class="row">
    <div class="col-8">
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0">{{ $flight->airline->icao }} {{ $flight->flight_number }}</h5></div>
        <div class="card-body p-0">
          <table class="table table-sm table-striped table-borderless mb-0">
            <tr>
              <th style="width: 15%;">@lang('common.departure')</th>
              <td>
                <a href="{{route('frontend.airports.show', ['id' => $flight->dpt_airport_id])}}">
                  {{ optional($flight->dpt_airport)->name ?? $flight->dpt_airport_id }}
                   / {{$flight->dpt_airport_id}}
                </a>
                  @if($flight->dpt_time) @ {{ $flight->dpt_time }} @endif
              </td>
            </tr>
            <tr>
              <th>@lang('common.arrival')</th>
              <td>
                <a href="{{route('frontend.airports.show', ['id' => $flight->arr_airport_id])}}">
                  {{ optional($flight->arr_airport)->name ?? $flight->arr_airport_id }}
                   / {{$flight->arr_airport_id }} 
                </a>
                  @if($flight->arr_time) @ {{ $flight->arr_time }} @endif
              </td>
            </tr>
            @if($flight->alt_airport_id)
              <tr>
                <th>@lang('flights.alternateairport')</th>
                <td>
                  {{ optional($flight->alt_airport)->name ?? $flight->alt_airport_id }}
                  (<a href="{{route('frontend.airports.show', ['id' => $flight->alt_airport_id])}}">{{$flight->alt_airport_id}}</a>)
                </td>
              </tr>
            @endif         
            @if(filled($flight->route))
              <tr>
                <th>@lang('flights.route')</th>
                <td>{{ strtoupper($flight->route) }}</td>
              </tr>
            @endif
            @if(filled($flight->level))
              <tr>
                <th>@lang('flights.level')</th>
                <td>{{ number_format($flight->level) }} {{ setting('units.altitude') }}</td>
              </tr>
            @endif
            @if($flight->flight_time)
              <tr>
                <th>@lang('flights.flighttime')</th>
                <td>@minutestotime($flight->flight_time)</td>
              </tr>
            @endif
            @if(filled($flight->distance))
              <tr>
                <th>@lang('common.distance')</th>
                <td>@if (setting('units.distance') === 'km') {{ number_format($flight->distance * 1.852) }} @else {{ number_format($flight->distance) }} @endif {{ setting('units.distance') }}</td>
              </tr>
            @endif
            @if(filled($flight->notes))
              <tr>
                <th>{{ trans_choice('common.note', 2) }}</th>
                <td>{{ $flight->notes }}</td>
              </tr>
            @endif
            @if($flight->subfleets->count())
              <tr>
                <th>SubFleets</th>
                <td>
                  @foreach($flight->subfleets as $subfleet)
                    &bull; {{ $subfleet->name }}
                  @endforeach
                </td>
              </tr>
            @endif
          </table>
        </div>
        <div class="card-footer p-1">
          @if($flight->flight_type)
            <h5 class="mb-0 mt-0"><span class="badge badge-light float-left mr-2 ml-2">{{ \App\Models\Enums\FlightType::label($flight->flight_type) }}</span></h5>
          @endif
          @if($flight->route_leg)
            <h5 class="mb-0 mt-0"><span class="badge badge-warning text-black float-right mr-2 ml-2">Leg No: {{ $flight->route_leg }}</span></h5>
          @endif
          @if($flight->route_code)
            <h5 class="mb-0 mt-0"><span class="badge badge-warning text-black float-right mr-2 ml-2">Code: {{ $flight->route_code }}</span></h5>
          @endif
        </div>
      </div>

      @include('flights.map')

    </div>
    <div class="col">
      {{ Widget::Weather(['icao' => $flight->dpt_airport_id,]) }}
      @if($flight->flight_time > 60)
        {{ Widget::Weather(['icao' => $flight->arr_airport_id, 'raw_only' => true]) }}
      @else
        {{ Widget::Weather(['icao' => $flight->arr_airport_id]) }}
      @endif
      @if($flight->alt_airport_id)
        {{ Widget::Weather(['icao' => $flight->alt_airport_id, 'raw_only' => true]) }}
      @endif
    </div>
  </div>
@endsection

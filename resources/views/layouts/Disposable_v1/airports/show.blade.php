@extends('app')
@section('title', $airport->full_name)

@section('content')
<h3 class="card-title">{{ $airport->full_name }}</h3>

<div class="row">
  <div class="col-4">
  {{ Widget::Weather(['icao' => $airport->icao,]) }}

  {{-- Show if there are files uploaded and a user is logged in--}}
  @if(count($airport->files) > 0 && Auth::check())
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0">{{ trans_choice('common.download', 2) }}<i class="fas fa-download float-right"></i></h5></div>
      <div class="card-body p-0">@include('downloads.table', ['files' => $airport->files])</div>
    </div>
  @endif
  </div>
  <div class="col">
    {{ Widget::AirspaceMap(['width' => '100%', 'height' => '500px', 'lat' => $airport->lat, 'lon' => $airport->lon,]) }}
  </div>
</div>
<div class="clearfix" style="height: 10px;"></div>
<div class="row">
  <div class="col">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0">@lang('flights.inbound')<i class="fas fa-paper-plane float-right"></i></h5></div>
      <div class="card-body p-0 overflow-auto">
        @if(!$inbound_flights)
          <div class="jumbotron text-center mb-0">@lang('flights.none')</div>
        @else
          <table class="table table-sm table-striped table-borderless mb-0">
            <thead>
              <tr>
                <th class="text-left">@lang('airports.ident')</th>
                <th class="text-left">@lang('airports.departure')</th>
                <th>@lang('flights.dep')</th>
                <th>@lang('flights.arr')</th>
              </tr>
            </thead>
            <tbody>
            @foreach($inbound_flights as $flight)
              <tr>
                <td class="text-left"><a href="{{ route('frontend.flights.show', [$flight->id]) }}">{{ $flight->ident }}</a></td>
                <td class="text-left">
                  {{ optional($flight->dpt_airport)->name }}
                  (<a href="{{route('frontend.airports.show',['id' => $flight->dpt_airport_id])}}">{{$flight->dpt_airport_id}}</a>)
                </td>
                <td>{{ $flight->dpt_time }}</td>
                <td>{{ $flight->arr_time }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
    {{ Widget::AirportAircrafts(['location' => $airport->icao]) }}
  </div>
  <div class="col">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0">@lang('flights.outbound')<i class="fas fa-paper-plane float-right"></i></h5></div>
      <div class="card-body p-0 overflow-auto">
        @if(!$outbound_flights)
          <div class="jumbotron text-center mb-0">@lang('flights.none')</div>
        @else
          <table class="table table-sm table-striped table-borderless mb-0">
            <thead>
              <tr>
                <th class="text-left">@lang('airports.ident')</th>
                <th class="text-left">@lang('airports.arrival')</th>
                <th>@lang('flights.dep')</th>
                <th>@lang('flights.arr')</th>
              </tr>
            </thead>
            <tbody>
            @foreach($outbound_flights as $flight)
              <tr>
                <td class="text-left"><a href="{{ route('frontend.flights.show', [$flight->id]) }}">{{ $flight->ident }}</a></td>
                <td class="text-left">
                  {{ $flight->arr_airport->name }}
                  (<a href="{{route('frontend.airports.show',['id'=>$flight->arr_airport->icao])}}">{{$flight->arr_airport->icao}}</a>)
                </td>
                <td>{{ $flight->dpt_time }}</td>
                <td>{{ $flight->arr_time }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        @endif
      </div>
    </div>
    {{ Widget::AirportPireps(['location' => $airport->icao]) }}
  </div>
</div>
@endsection

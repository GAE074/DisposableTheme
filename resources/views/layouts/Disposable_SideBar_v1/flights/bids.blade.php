@extends('app')
@section('title', __('flights.mybid'))

@section('content')
  <div class="row">
    <div class="col">
      <h3 class="card-title">{{ __('flights.mybid') }}</h3>
    </div>
  </div>
  
  @include('flash::message')

  <div class="row">
    <div class="col">
      @if (Auth::user()->bids == "[]")
        <div class="alert alert-primary">@lang('flights.none')</div>
      @else
        @include('flights.table')

        @foreach($flights as $biddedflight)
          <hr>
          <h4 class="card-title m-1 p-0">Weather Reports For {{ $biddedflight->ident }}</h4>
          <div class="row">
            <div class="col">
              <h5 class="card-title m-1 p-0">@lang('common.departure')</h5>
              {{ Widget::Weather(['icao' => $biddedflight->dpt_airport_id, 'raw_only' => true]) }}
            </div>
            <div class="col">
              <h5 class="card-title m-1 p-0">@lang('common.arrival')</h5>
              {{ Widget::Weather(['icao' => $biddedflight->arr_airport_id, 'raw_only' => true]) }}
            </div>
            @if(strlen($biddedflight->alt_airport_id) == 4)
              <div class="col">
                <h5 class="card-title m-1 p-0">@lang('flights.alternateairport')</h5>
                {{ Widget::Weather(['icao' => $biddedflight->alt_airport_id, 'raw_only' => true]) }}
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>
  </div>
@endsection

@include('flights.scripts')

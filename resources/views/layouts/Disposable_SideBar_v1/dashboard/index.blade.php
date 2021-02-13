@extends('app')
@section('title', __('common.dashboard'))

@section('content')
@if(Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
  <div class="row">
    <div class="col-sm mb-2">
      <div class="alert alert-warning" role="alert">You are on leave! File a PIREP to set your status to active!</div>
    </div>
  </div>
@endif
{{-- TOP ROW WITH BOXES ONLY --}}
<div class="row">
  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-paper-plane fa-4x text-primary"></i></div>
        <h5 class="card-title m-0 p-0 text-center">{{ $user->flights }}</h5>
        <h6 class="card-title m-0 p-0 text-center">{{ trans_choice('common.flight', $user->flights) }}</h6>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-clock fa-4x text-danger"></i></div>
        <h5 class="card-title m-0 p-0 text-center">@minutestotime($user->flight_time)</h5>
        <h6 class="card-title m-0 p-0 text-center">Tot.Flight Time</h6>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-stopwatch fa-4x text-warning"></i></div>
        <h5 class="card-title m-0 p-0 text-center">{{ Widget::PersonalStats(['user' => $user->id, 'type' => 'avgtime']) }}</h5>
        <h6 class="card-title m-0 p-0 text-center">Avg.Flight Time</h6>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-gas-pump fa-4x text-info"></i></div>
        <h5 class="card-title m-0 p-0 text-center">{{ Widget::PersonalStats(['user' => $user->id, 'type' => 'avgfuel']) }}</h5>
        <h6 class="card-title m-0 p-0 text-center">Avg.Fuel Used</h6>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-map-marker fa-4x text-success"></i></div>
        <h5 class="card-title m-0 p-0 text-center">{{ optional($user->current_airport)->icao ?? '--' }}</h5>
        <h6 class="card-title m-0 p-0 text-center">Current Location</h6>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card bg-transparent shadow-none text-dark border-0 mb-2">
      <div class="card-body bg-transparent p-1">
        <div class="float-right"><i class="fas fa-coins fa-4x text-primary"></i></div>
        <h5 class="card-title m-0 p-0 text-center">{{ optional($user->journal)->balance ?? 0 }}</h5>
        <h6 class="card-title m-0 p-0 text-center">@lang('dashboard.yourbalance')</h6>
      </div>
    </div>
  </div>

</div>
{{-- MAIN DASHBOARD AREA --}}
<div class="row">
  {{-- Left --}}
  <div class="col-8">
    {{ Widget::liveMap(['height' => '500px', 'table' => false]) }}
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0">@lang('dashboard.yourlastreport')<i class="fas fa-upload float-right"></i></h5></div>
      @if($last_pirep === null)
        <div class="card-body text-center p-1"> @lang('dashboard.noreportsyet') <a href="{{ route('frontend.pireps.create') }}">@lang('dashboard.fileonenow')</a></div>
      @else
        @include('dashboard.pirep_card', ['pirep' => $last_pirep])
      @endif
    </div>

    {{ Widget::latestNews(['count' => 3]) }}
    <div class="row">
      <div class="col">
        {{ Widget::TopAirports(['type' => 'dep', 'count' => 3]) }}
        {{ Widget::TopPilotsByPeriod(['type' => 'flights', 'count' => 3, 'period'=> 'lastm']) }}
      </div>
      <div class="col">
        {{ Widget::TopAirports(['type' => 'arr', 'count' => 3]) }}
        {{ Widget::TopPilotsByPeriod(['type' => 'time', 'count' => 3, 'period'=> 'lastm']) }}
      </div>
    </div>
  </div>

  {{-- Right --}}
  <div class="col">
    <div class="card p-0 mb-2 border-0"><a href="{{ route('frontend.flights.bids') }}" class="btn btn-primary shadow-none">My Flight Bids</a></div>
    {{ Widget::Weather(['icao' => $current_airport]) }}
    {{ Widget::TopPilotsByPeriod(['count' => 3, 'type' => 'flights']) }}   
    {{ Widget::TopPilotsByPeriod(['count' => 3, 'type' => 'time']) }}    
    {{ Widget::AirlineStats() }}
  </div>
</div>
@endsection

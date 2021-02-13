@extends('app')
@section('title', trans_choice('common.flight', 2))

@section('content')
  <div class="row">
    <div class="col">
      <h3 class="card-title">{{ trans_choice('common.flight', 2) }}</h3>
    </div>
  </div>
  @include('flash::message')

  <div class="row">
    <div class="col-9">
      @include('flights.table')
    </div>
    <div class="col-3">
      @include('flights.nav')
      @include('flights.search')
    </div>
  </div>
  <div class="row">
    <div class="col-9 text-center mt-3">
      {{ $flights->links('pagination.default') }}
    </div>
  </div>
@endsection

@include('flights.scripts')


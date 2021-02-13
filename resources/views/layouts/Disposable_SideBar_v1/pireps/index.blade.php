@extends('app')
@section('title', trans_choice('common.pirep', 2))

@section('content')
<div class="row">
  <div class="col text-left">
    <h3 class="card-title">{{ trans_choice('pireps.pilotreport', 2) }}</h3>
  </div>
</div>
@include('flash::message')

<div class="row">
  <div class="col">
    <div class="card mb-2">
      <div class="card-body p-0">@include('pireps.table')</div>
      <div class="card-pirep text-right p-1">
        <a class="btn btn-info" href="{{ route('frontend.pireps.create') }}">@lang('pireps.filenewpirep')</a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col text-center">
    {{ $pireps->links('pagination.default') }}
  </div>
</div>
@endsection

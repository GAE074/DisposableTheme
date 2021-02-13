@extends('app')
@section('title', trans_choice('common.pilot', 2))

@section('content')
<div class="row row-cols-2 mb-2">
  <div class="col">
    <h3 class="card-title">{{ trans_choice('common.pilot', 2) }}</h3>
  </div>
</div>

<div class="row">
  <div class="col">
    <div class="card mb-2">@include('users.table')</div>
  </div>
</div>

<div class="row">
  <div class="col text-center">
    {{ $users->links('pagination.default') }}
  </div>
</div>
@endsection

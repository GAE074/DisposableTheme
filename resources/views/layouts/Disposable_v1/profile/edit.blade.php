@extends('app')
@section('title', __('profile.editprofile'))

@section('content')
<div class="row">
  <div class="col-6 ml-auto mr-auto content-center">
    <div class="card">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-id-card float-right"></i>@lang('profile.edityourprofile')</h5></div>
      @include('flash::message')
      <div class="card-body p-0">
        {{ Form::model($user, ['route' => ['frontend.profile.update', $user->id], 'files' => true, 'method' => 'patch']) }}
        @include("profile.fields")
        {{ Form::close() }}
      </div>
    </div>
  </div>
</div>
@endsection

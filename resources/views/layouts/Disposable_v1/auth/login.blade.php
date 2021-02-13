@extends('auth.login_layout')
@section('title', __('common.login'))

@section('content')
<div class="col-4 ml-auto mr-auto content-center">
  <div class="card mt-5 mb-2">
    {{ Form::open(['url' => url('/login'), 'method' => 'post', 'class' => 'form']) }}
    <div class="card-header bg-transparent p-1 text-center">
      <img src="{{ public_asset('/assets/frontend/img/logo.svg') }}" class="rounded" style="width: 320px;">
    </div>
    <div class="card-body p-1">
      <div class="input-group form-group-no-border {{ $errors->has('email') ? ' has-error' : '' }} input-lg mb-2">
        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
          {{ Form::text('email', old('email'), ['id' => 'email', 'placeholder' => __('common.email').' '.__('common.or').' '.__('common.pilot_id'), 'class' => 'form-control', 'required' => true,]) }}
          @if ($errors->has('email')) <span class="form-text"><strong>{{ $errors->first('email') }}</strong></span>@endif
      </div>
      <div class="input-group form-group-no-border {{ $errors->has('password') ? ' has-error' : '' }} input-lg">
          <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
          {{ Form::password('password', ['name' => 'password', 'class' => 'form-control', 'placeholder' => __('auth.password'), 'required' => true,]) }}
          @if ($errors->has('password')) <span class="form-text"><strong>{{ $errors->first('password') }}</strong></span>@endif
      </div>
    </div>
    <div class="card-footer text-center p-1"><button class="btn btn-primary btn-round btn-block">@lang('common.login')</button></div>
  </div>
  <div class="float-left"><h6><a href="{{ url('/register') }}">@lang('auth.createaccount')</a></h6></div>
  <div class="float-right"><h6><a href="{{ url('/password/reset') }}">@lang('auth.forgotpassword')?</a></h6></div>
  {{ Form::close() }}
</div>
@endsection

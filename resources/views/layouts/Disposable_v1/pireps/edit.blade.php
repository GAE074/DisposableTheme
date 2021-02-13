@extends('app')
@section('title', __('pireps.editflightreport'))

@section('content')
<div class="row">
  <div class="col text-left">
    <h3 class="card-title">@lang('pireps.editflightreport')</h3>
  </div>
</div>
@include('flash::message')

<div class="row">
  <div class="col">
    <div class="card bg-transparent shadow-none border-0 p-0 mb-0">
        {{ Form::model($pirep, [
              'route' => ['frontend.pireps.update', $pirep->id],
              'class' => 'form-group',
              'method' => 'patch']) 
        }}

        @include('pireps.fields')

        {{ Form::close() }}
    </div>
  </div>
</div>
@endsection

@include('pireps.scripts')

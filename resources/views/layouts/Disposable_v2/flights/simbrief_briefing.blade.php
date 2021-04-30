@extends('app')
@section('title', 'Briefing')
@include('disposable_functions')
@section('content')
  <ul class="nav nav-pills nav-justified mb-2" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link dispo-pills mr-2 ml-2 active" id="pills-summary-tab" data-toggle="pill" href="#pills-summary" role="tab" aria-controls="pills-summary" aria-selected="true">@lang('disposable.ofpsummary')</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link dispo-pills mr-2 ml-2" id="pills-ofp-tab" data-toggle="pill" href="#pills-ofp" role="tab" aria-controls="pills-ofp" aria-selected="false">@lang('disposable.ofpsigwx')</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link dispo-pills mr-2 ml-2" id="pills-weather-tab" data-toggle="pill" href="#pills-weather" role="tab" aria-controls="pills-weather" aria-selected="false">@lang('disposable.ofpweather')</a>
    </li>
    @if(empty($simbrief->pirep_id) && Theme::getSetting('manual_pireps'))
      <li class="nav-item" role="presentation">
        <a class="nav-link mr-2 ml-2 btn-info" href="{{ url(route('frontend.simbrief.prefile', [$simbrief->id])) }}">@lang('disposable.sbprefilepirep')</a>
      </li>
    @endif
    <li class="nav-item" role="presentation">
      <a class="nav-link mr-2 ml-2 btn-danger" href="{{ url(route('frontend.simbrief.generate_new', [$simbrief->id])) }}">@lang('disposable.sbgeneratenew')</a>
    </li>
  </ul>
 
  <div class="tab-content" id="pills-tabContent">
    {{-- Summary Tab --}}
    <div class="tab-pane fade show active" id="pills-summary" role="tabpanel" aria-labelledby="pills-summary-tab">
      @include('flights.simbrief_briefing_summary')
    </div>
    {{-- OFP and Weather Maps--}}
    <div class="tab-pane fade" id="pills-ofp" role="tabpanel" aria-labelledby="pills-ofp-tab">
      @include('flights.simbrief_briefing_ofp')
    </div>
    {{-- OFP Weather Section --}}
    <div class="tab-pane fade" id="pills-weather" role="tabpanel" aria-labelledby="pills-weather-tab">
      @include('flights.simbrief_briefing_weather')
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      $("#download_fms").click(e => {
        e.preventDefault();
        const select = document.getElementById("download_fms_select");
        const link = select.options[select.selectedIndex].value;
        console.log('Downloading FMS: ', link);
        window.open(link, '_blank');
      });
    });
  </script>
@endsection

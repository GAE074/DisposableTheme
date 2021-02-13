@extends('app')
@section('title', trans_choice('common.download', 2))

@section('content')
  <div class="row">
    <div class="col">
    {{--  <h3 class="card-title">{{ trans_choice('common.download', 2) }}</h3> --}}
    </div>
  </div>
  @include('flash::message')

  @if(!$grouped_files || \count($grouped_files) === 0)
    <div class="row">
      <div class="alert alert-primary">@lang('downloads.none')</div>
    </div>
  @else 
    <h4 class="card-title m-1 p-0">
      <button class="btn btn-round btn-icon" title="Show/Hide Aircraft Downloads" type="button" data-toggle="collapse" data-target="#DownloadsAircraft" aria-expanded="false" aria-controls="DownloadsAircraft">
        <i class="fas fa-arrows-alt-v"></i>
      </button>
      Downloads: Aircraft
    </h4>

    <div id="DownloadsAircraft" class="row row-cols-3 collapse">     
      @foreach($grouped_files as $group => $files)
        @if(strpos($group, 'Aircraft >') !== false)
          <div class="col">
            <div class="card mb-2">
              <div class="card-header p-1"><h5 class="m-1 p-0">{{ substr($group, 11) }}</h5></div>
              <div class="card-body p-0">
                  @include('downloads.table', ['files' => $files])
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>

    <h4 class="card-title m-1 p-0">
      <button class="btn btn-round btn-icon" title="Show/Hide Subfleet Downloads" type="button" data-toggle="collapse" data-target="#DownloadsSubfleet" aria-expanded="false" aria-controls="DownloadsSubfleet">
        <i class="fas fa-arrows-alt-v"></i>
      </button>
      Downloads: SubFleet
    </h4>

    <div id="DownloadsSubfleet" class="row row-cols-3 collapse">     
      @foreach($grouped_files as $groupsf => $filessf)
        @if(strpos($group, 'Subfleet >') !== false)
          <div class="col">
            <div class="card mb-2">
              <div class="card-header p-1"><h5 class="m-1 p-0">{{ substr($groupsf, 11) }}</h5></div>
              <div class="card-body p-0">
                  @include('downloads.table', ['files' => $filessf])
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>

    <h4 class="card-title m-1 p-0">
      <button class="btn btn-round btn-icon" title="Show/Hide Airport Downloads" type="button" data-toggle="collapse" data-target="#DownloadsAirport" aria-expanded="false" aria-controls="DownloadsAirport">
        <i class="fas fa-arrows-alt-v"></i>
      </button>
      Downloads: Airport
    </h4>

    <div id="DownloadsAirport" class="row row-cols-3 collapse">     
      @foreach($grouped_files as $groupap => $filesap)
        @if(strpos($groupap, 'Airport >') !== false)
          <div class="col">
            <div class="card mb-2">
                <div class="card-header p-1"><h5 class="m-1 p-0">{{ substr($groupap, 10) }}</h5></div>
                <div class="card-body p-0">
                    @include('downloads.table', ['files' => $filesap])
                </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>

    <h4 class="card-title m-1 p-0">
      <button class="btn btn-round btn-icon" title="Show/Hide Airline Downloads" type="button" data-toggle="collapse" data-target="#DownloadsAirline" aria-expanded="false" aria-controls="DownloadsAirline">
        <i class="fas fa-arrows-alt-v"></i>
      </button>
      Downloads: Airline
    </h4>

    <div id="DownloadsAirline" class="row row-cols-3 collapse">
      @foreach($grouped_files as $groupal => $filesal)
        @if(strpos($groupal, 'Airline >') !== false)
          <div class="col">
            <div class="card mb-2">
              <div class="card-header p-1"><h5 class="m-1 p-0">{{ substr($groupal, 10) }}</h5></div>
              <div class="card-body p-0">
                  @include('downloads.table', ['files' => $filesal])
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  @endif

@endsection

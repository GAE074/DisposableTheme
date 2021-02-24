@extends('app')
@section('title', trans_choice('common.pirep', 1).' '.$pirep->ident)

@section('content')
<div class="row row-cols-2 mb-3">
  <div class="col text-left">
    <h3 class="card-title m-0 p-0">{{ $pirep->airline->icao }}{{ $pirep->ident }} : {{ $pirep->dpt_airport_id }} > {{ $pirep->arr_airport_id }}</h3>
  </div>

  <div class="col text-right">
    @if (!empty($pirep->simbrief))
      <a href="{{ url(route('frontend.simbrief.briefing', [$pirep->simbrief->id])) }}" class="btn btn-sm btn-info mr-1">SimBrief OFP</a>
    @endif
    {{-- Show the link to edit if it can be edited --}}
    @if(!$pirep->read_only)
      <form method="get" action="{{ route('frontend.pireps.edit', $pirep->id) }}" style="display: inline">
        @csrf
        <button class="btn btn-sm btn-info mr-1">@lang('common.edit')</button>
      </form>
      <form method="post" action="{{ route('frontend.pireps.submit', $pirep->id) }}" style="display: inline">
        @csrf
        <button class="btn btn-sm btn-success mr-1">@lang('common.submit')</button>
      </form>
    @endif
  </div>
</div>

<div class="row row-cols-2">
  <div class="col-8">
    <div class="card mb-2">
      <div class="card-header p-1">
        <div class="row row-cols-2">
          <div class="col text-left"><h5 class="m-0 p-0">{{$pirep->dpt_airport->location}}</h5></div>
          <div class="col text-right"><h5 class="m-0 p-0">{{$pirep->arr_airport->location}}</h5></div>
        </div>
      </div>
      <div class="card-body p-1">
        <div class="row row-cols-2">
          <div class="col text-left">
            <p class="p-0 mb-1"><a href="{{route('frontend.airports.show', $pirep->dpt_airport_id)}}">{{ $pirep->dpt_airport->full_name }} / {{  $pirep->dpt_airport_id }}</a></p>
            <p class="p-0 mb-1">@if($pirep->block_off_time) {{ $pirep->block_off_time->toDayDateTimeString() }} @endif</p>
          </div>
          <div class="col text-right">
            <p class="p-0 mb-1"><a href="{{route('frontend.airports.show', $pirep->arr_airport_id)}}">{{ $pirep->arr_airport->full_name }} / {{  $pirep->arr_airport_id }})</a></p>
            <p class="p-0 mb-1">@if($pirep->block_on_time) {{ $pirep->block_on_time->toDayDateTimeString() }} @endif</p>
          </div>
        </div>
      </div>
      <div class="card-footer p-1">
        <div class="progress progress-bar bg-success" role="progressbar" style="width: @if($pirep->progress_percent > 100) 100% @else {{ $pirep->progress_percent }}% @endif;"
             aria-valuenow="{{ $pirep->progress_percent }}" aria-valuemin="0" aria-valuemax="100">{{ $pirep->progress_percent }}%
        </div>
      </div>
    </div>

    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-route float-right"></i>Route Map</h5></div>
      <div class="card-body p-0">@include('pireps.map')</div>
    </div>

    @if(count($pirep->acars_logs) > 0)
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-info-circle float-right"></i>@lang('pireps.flightlog')</h5></div>
        <div class="card-body p-0">
          <table class="table table-sm table-borderless table-hover mb-0">
            @foreach($pirep->acars_logs as $log)
              <tr>
                <td nowrap="true">{{ show_datetime($log->created_at) }}</td>
                <td>{{ $log->log }}</td>
              </tr>
            @endforeach
          </table>
        </div>
      </div>
    @endif
  </div>

  <div class="col-4">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fab fa-avianex float-right"></i>Pirep Details</h5></div>
      <div class="card-body p-0">
        <table class="table table-sm table-borderless table-striped mb-0">
          <tr>
            <td width="20%">@lang('common.state')</td>
            <td><div class="badge badge-info">{{ PirepState::label($pirep->state) }}</div></td>
          </tr>
          @if ($pirep->state !== PirepState::DRAFT)
          <tr>
            <td>@lang('common.status')</td>
            <td><div class="badge badge-info">{{ PirepStatus::label($pirep->status) }}</div></td>
          </tr>
          @endif
          <tr>
            <td>@lang('pireps.source')</td>
            <td>{{ PirepSource::label($pirep->source) }}</td>
          </tr>
          <tr>
            <td>@lang('flights.flighttype')</td>
            <td>{{ \App\Models\Enums\FlightType::label($pirep->flight_type) }}</td>
          </tr>
          <tr>
            <td>@lang('pireps.filedroute')</td>
            <td>{{ $pirep->route }}</td>
          </tr>
          <tr>
            <td>{{ trans_choice('common.note', 2) }}</td>
            <td>{{ $pirep->notes }}</td>
          </tr>
          <tr>
            <td>@lang('pireps.filedon')</td>
            <td>{{ show_datetime($pirep->created_at) }}</td>
          </tr>
        </table>
      </div>
    </div>

    @if(count($pirep->fields) > 0)
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fab fa-wpforms float-right"></i>{{ trans_choice('common.field', 2) }}</h5></div>
        <div class="card-body p-0">
          <table class="table table-sm table-boderless table-striped mb-0">
            <thead>
              <th>@lang('common.name')</th>
              <th>{{ trans_choice('common.value', 1) }}</th>
            </thead>
            <tbody>
            @foreach($pirep->fields as $field)
              <tr>
                <td>{{ $field->name }}</td>
                <td>{{ $field->value }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    @if(count($pirep->fares) > 0)
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-ellipsis-h float-right"></i>{{ trans_choice('pireps.fare', 2) }}</h5></div>
        <div class="card-body p-0">
          <table class="table table-sm table-borderless table-striped mb-0">
            <thead>
              <th>@lang('pireps.class')</th>
              <th>@lang('pireps.count')</th>
            </thead>
            <tbody>
            @foreach($pirep->fares as $fare)
              <tr>
                <td>{{ $fare->fare->name }} ({{ $fare->fare->code }})</td>
                <td>{{ $fare->count }}</td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endif

    @if(count($pirep->transactions) > 0)
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-file-invoice float-right"></i>Transaction Details</h5></div>
        <div class="card-body p-0">
          <table class="table table-sm table-borderless table-striped mb-0 text-right">
            <tr>
              <th class="text-left"></th>
              <th>Credit</th>
              <th>Debit</th>
            </tr>
            @foreach($pirep->transactions->where('journal_id', $pirep->airline->journal->id) as $entry)
              <tr>
                <td class="text-left">{{ $entry->memo }}</td>
                <td>
                  @if($entry->credit)
                    {{ money($entry->credit, setting('units.currency')) }}
                  @endif
                </td>
                <td>
                  @if($entry->debit)
                    {{ money($entry->debit, setting('units.currency')) }}
                  @endif
                </td>
              </tr>
            @endforeach
            <tr>
              <th class="text-left"></th>
              <th>{{ money($pirep->transactions->where('journal_id', $pirep->airline->journal->id)->sum('credit'), setting('units.currency')) }}</th>
              <th>{{ money($pirep->transactions->where('journal_id', $pirep->airline->journal->id)->sum('debit'), setting('units.currency')) }}</th>
            </tr>
          </table>
        </div>
        <div class="card-footer p-1 text-left">
          <span><b>Balance</b></span>
          <span class="float-right"><b>
          {{ money($pirep->transactions->where('journal_id', $pirep->airline->journal->id)->sum('credit') - $pirep->transactions->where('journal_id', $pirep->airline->journal->id)->sum('debit'), setting('units.currency')) }}
          </b></span>
        </div>
      </div>
    @endif

    @if(!empty($pirep->simbrief))
      <div class="card mb-2">
        <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-info-circle float-right"></i>SimBrief OFP</h5></div>
        <div class="card-body p-0">
          <div class="overflow-auto" style="height: 500px;">{!! $pirep->simbrief->xml->text->plan_html !!}</div>
        </div>
      </div>
    @endif
  </div>
</div>

@endsection

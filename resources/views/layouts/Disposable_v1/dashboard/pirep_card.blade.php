<div class="card-body p-0">
  <table class="table table-striped table-borderless mb-0 text-center">
    <tr>
      <td class="text-left">
        <a href="{{ route('frontend.pireps.show', [$pirep->id]) }}">{{ $pirep->airline->code }}{{ $pirep->ident }}</a>
      </td>
      <td class="text-left">
        {{ $pirep->dpt_airport->name }} (<a href="{{route('frontend.airports.show', ['id' => $pirep->dpt_airport->icao])}}">{{$pirep->dpt_airport->icao}}</a>)
      </td>
      <td class="text-left">
        {{ $pirep->arr_airport->name }} (<a href="{{route('frontend.airports.show', ['id' => $pirep->arr_airport->icao])}}">{{$pirep->arr_airport->icao}}</a>)
      </td>
      <td>
        {{ $pirep->submitted_at->diffForHumans() }}
      </td>
      <td>
        @if($pirep->state === PirepState::PENDING)
          <div class="badge badge-warning">
        @elseif($pirep->state === PirepState::ACCEPTED)
            <div class="badge badge-success">
        @elseif($pirep->state === PirepState::REJECTED)
            <div class="badge badge-danger">
        @else
           <div class="badge badge-info">
        @endif
          {{ PirepState::label($pirep->state) }}</div>
      </td>
      <td>
        <a href="{{ route('frontend.pireps.edit', [$pirep->id]) }}" class="btn btn-sm btn-info">@lang('common.edit')</a>
      </td>
    </tr>
  </table>
</div>

<div class="card mb-2">
  <div class="card-header p-1">
    @if ($rtype === 'Departures')
      <h5 class="m-1 p-0"><i class="fas fa-plane-departure float-right"></i></h5>
    @elseif ($rtype === 'Arrivals')
      <h5 class="m-1 p-0"><i class="fas fa-plane-arrival float-right"></i></h5>
    @endif
    <h5>Top {{ $count }} Airports By {{ $rtype }}</h5>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm table-striped table-borderless mb-0 text-right">
      <tr>
        <th class="text-left">Name</th>
        <th>{{ $rtype }}</th>
      </tr>
      @foreach($tairports as $ta)
        <tr>
        @if ($rtype === 'Departures')
          <td class="text-left"><a href="{{ route('frontend.airports.show', [$ta->dpt_airport_id]) }}">{{ $ta->dpt_airport->name }}</a></td>
        @elseif ($rtype === 'Arrivals')
          <td class="text-left"><a href="{{ route('frontend.airports.show', [$ta->arr_airport_id]) }}">{{ $ta->arr_airport->name }}</a></td>
        @endif
          <td>{{ number_format($ta->tusage) }}</td>
        </tr>
      @endforeach
    </table>
  </div>
</div>
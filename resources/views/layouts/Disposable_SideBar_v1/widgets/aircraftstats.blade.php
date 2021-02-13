<div class="card mb-2">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">Aircrafts By {{ ucfirst($type) }} @if ($type === 'icao') Type @endif <i class="fas fa-plane float-right"></i></h5>
  </div>
  <div class="card-body p-0">
    <table class="table table-sm table-striped table-borderless mb-0 text-left">
      <tr>
        <th>{{ ucfirst($type) }} @if ($type === 'icao') Type @endif</th>
        <th class="text-right">AC Count</th>
      </tr>
      @foreach($acstats as $ac)
        <tr>
          <td>
            @if ($type === 'icao') {{ $ac->icao }}
            @elseif ($type === 'location') <a href="{{route('frontend.airports.show', [$ac->airport_id])}}" target="_blank">{{ $ac->airport->name }}</a>
            @endif
          </td>
          <td class="text-right">{{ $ac->result }}</td>
        </tr>
      @endforeach
    </table>
  </div>
</div>

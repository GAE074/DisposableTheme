<div class="card mb-2">
  <div class="card-header p-1">
    <h5 class="m-1 p-0">Top {{ $count }} Pilots By {{ ucfirst($type) }}<i class="fas fa-medal float-right"></i></h5>
  </div>
  <div class="card-body p-0">
    @if(count($tpilots) > 0)
      <table class="table table-sm table-striped table-borderless mb-0 text-right">
        <tr>
          <th class="text-left">Name</th>
          <th>{{ ucfirst($type) }}</th>
        </tr>
        @foreach($tpilots as $tp)
          <tr>
            <td class="text-left"><a href="{{route('frontend.profile.show', [$tp->user_id])}}">{{ $tp->user->name_private }}</a></td>
            @if($type === 'time')
              <td> @minutestotime($tp->totals) </td>
            @elseif($type === 'distance')
              <td> @if (setting('units.distance') === 'km') {{ number_format($tp->totals * 1.852) }} @else {{ number_format($tp->totals) }} @endif {{ setting('units.distance') }}</td>
            @else
              <td> {{ number_format($tp->totals) }}</td>
            @endif
          </tr>
        @endforeach
      </table>
    @else
      No Stats Available
    @endif
  </div>
</div>

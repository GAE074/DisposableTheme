<div class="card mb-2">
  <div class="card-header p-1">
    @if ($count === 1)
      <h5 class="m-1 p-0">Best Pilot of {{ ucfirst($rperiod) }} By {{ ucfirst($type) }}<i class="fas fa-star float-right"></i></h5>
    @else
      <h5 class="m-1 p-0">Top {{ $count }} Pilots of {{ ucfirst($rperiod) }} By {{ ucfirst($type) }}<i class="fas fa-medal float-right"></i></h5>
    @endif
  </div>
  <div class="card-body p-0">
    @if(count($tpilots) > 0)
      <table class="table table-sm table-striped table-borderless mb-0 text-right">
        @if($count > 1)
          <tr>
            <th class="text-left">Name</th>
            <th>{{ ucfirst($type) }}</th>
          </tr>
        @endif
        @foreach($tpilots as $tp)
          <tr>
            <td class="text-left"><a href="{{ route('frontend.users.show.public', [$tp->user_id]) }}">{{ $tp->user->name_private }}</a></td>
          @if($type == 'time')
            <td> @minutestotime($tp->totals) </td>
          @elseif($type == 'distance')
            <td> @if (setting('units.distance') === 'km') {{ number_format($tp->totals * 1.852) }} @else {{ number_format($tp->totals) }} @endif {{ setting('units.distance') }} </td>
          @else
            <td> {{ number_format($tp->totals) }} </td>
          @endif
          </tr>
        @endforeach
      </table>
    @else
      <span class="m-1">No Stats Available</span>
    @endif
  </div>
</div>
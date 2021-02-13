<table class="table table-sm table-striped table-borderless mb-0 text-center">
  <thead>
    <th class="text-left"></th>
    <th class="text-left">@lang('common.name')</th>
    <th class="text-left">Ident</th>
    <th>Country</th>
    <th>@lang('airports.home')</th>
    <th>@lang('common.airline')</th>
    <th>@lang('airports.current')</th>
    <th>{{ trans_choice('common.flight', 2) }}</th>
    <th>{{ trans_choice('common.hour', 2) }}</th>
    @if(setting('pilots.allow_transfer_hours') === true)
      <th>Transferred</th>
      <th>Total</th>
    @endif
    <th>Awards</th>
  </thead>
  <tbody>
  @foreach($users as $user)
    <tr>
      <td class="text-left align-middle">
        @if ($user->avatar == null)
          <img class="rounded img-h50 border border-dark" src="{{ public_asset('/image/nophoto.jpg') }}"/>
        @else
          <img class="rounded img-h50 border border-dark" src="{{ $user->avatar->url }}">
        @endif
      </td>
      <td class="text-left align-middle"><a href="{{ route('frontend.profile.show', [$user->id]) }}">{{ $user->name_private }}</a></td>
      <td class="text-left align-middle">{{$user->ident}}</td>
      <td class="align-middle">
        @if(filled($user->country))
          <span class="flag-icon flag-icon-{{ $user->country }}" title="{{ $country->alpha2($user->country)['name'] }}"></span>
        @endif
      </td>
      <td class="align-middle">@if($user->home_airport)<a href="{{route('frontend.airports.show', [$user->home_airport->icao])}}">{{ $user->home_airport->icao }}</a>@else - @endif</td>
      <td class="align-middle">{{ $user->airline->name }}</td>
      <td class="align-middle">@if($user->current_airport)<a href="{{route('frontend.airports.show', [$user->current_airport->icao])}}">{{ $user->current_airport->icao }}</a> @else - @endif </td>
      <td class="align-middle">{{ $user->flights }}</td>
      <td class="align-middle">@minutestotime($user->flight_time)</td>
      @if(setting('pilots.allow_transfer_hours') === true)
        <td class="align-middle">@minutestohours($user->transfer_time)h</td>
        <td class="align-middle">@minutestotime($user->flight_time + $user->transfer_time)</td>
      @endif
      <td class="align-middle">@if ($user->awards->count() > 0) <i class="fas fa-trophy fa-lg" style="color: darkgreen;" title="Pilot Has {{ $user->awards->count() }} Award/s"></i> @endif</td>
    </tr>
  @endforeach
  </tbody>
</table>

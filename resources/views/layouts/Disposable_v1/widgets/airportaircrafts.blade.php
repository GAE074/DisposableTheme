<div class="card mb-2">
  <div class="card-header p-1"><h5 class="m-1 p-0">Aircrafts<i class="fas fa-plane float-right"></i></h5></div>
  <div class="card-body p-0 overflow-auto">
    @if(!$aircrafts->count())
      <div class="text-center m-1">No Aircrafts !</div>
    @else
      <table class="table table-sm table-striped table-borderless mb-0 text-center">
        <tr>
          <th class="text-left">Registration</th>
          <th>ICAO Type</th>
          <th>Company</th>
          <th>SubFleet</th>
        </tr>
        @foreach($aircrafts as $ac)
          <tr>
            <td class="text-left">@if ($ac->registration) {{ $ac->registration }} @endif</td>
            <td>{{ $ac->icao }}</td>
            <td>{{ $ac->subfleet->airline->name }}</td>
            <td>{{ $ac->subfleet->name }}</td>
          </tr>
        @endforeach
      </table>
    @endif
  </div>
</div>

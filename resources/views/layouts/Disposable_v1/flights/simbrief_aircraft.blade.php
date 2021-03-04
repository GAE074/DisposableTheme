@extends('app')
@section('title', 'SimBrief Flight Planning')

@section('content')
<div class="row">
  <div class="col-8">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0">Select Aircraft for Flight<i class="fas fa-plane float-right"></i></h5></div>
      <div class="card-body p-1">
        <select id="aircraftselection" class="form-control select2" onchange="checkacselection()">
          <option value="ZZZZZ">Please Select An Aircraft</option>
          @foreach($subfleets as $subfleet)
            @foreach($subfleet->aircraft as $ac)
              @if(setting('pireps.only_aircraft_at_dpt_airport') && $flight->dpt_airport == $ac->location || !setting('pireps.only_aircraft_at_dpt_airport'))
                <option value="{{ $ac->id }}">[{{ $ac->icao }}] {{ $ac->registration }}</option>
              @endif
            @endforeach
          @endforeach
        </select>
      </div>
      <div class="card-footer p-1 text-right">
        <a id="generate_link" style="visibility: hidden" href="{{ route('frontend.simbrief.generate') }}?flight_id={{ $flight->id }}" class="btn btn-sm btn-primary">Proceed To Flight Planning</a>
      </div>
    </div>
</div>
@endsection
@section('scripts')
  <script type="text/javascript">
    // Simple Aircraft Selection With Dropdown Change
    // Also keep Generate button hidden until a valid AC selection
    const $oldlink = document.getElementById("generate_link").href;

    function checkacselection() {
      if (document.getElementById("aircraftselection").value === "ZZZZZ") {
        document.getElementById('generate_link').style.visibility = 'hidden';
      } else {
        document.getElementById('generate_link').style.visibility = 'visible';
      }
      const selectedac = document.getElementById("aircraftselection").value;
      const newlink = "&aircraft_id=".concat(selectedac);

      document.getElementById("generate_link").href = $oldlink.concat(newlink);
    }
  </script>
@endsection

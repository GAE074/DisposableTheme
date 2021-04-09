@extends('app')
@section('title', 'SimBrief Flight Planning')

@section('content')
  <div class="row">
    <div class="col">
      <h3 class="card-title">Create Simbrief OFP</h3>
    </div>
  </div>

  <form id="sbapiform">
    <div class="row">
      <div class="col-8">
        <div class="card mb-2">
          <div class="card-header p-1"><h6 class="m-1 p-0"><i class="fas fa-plane float-right"></i>Aircraft Details</h6></div>
          <div class="card-body p-1">
            <div class="row row-cols-4">
              <div class="col">
                <label for="type">Type</label>
                <input type="text" class="form-control" value="{{ $aircraft->icao }}" maxlength="4" disabled/>
                <input type="hidden" name="type" class="form-control" value="{{ $aircraft->subfleet->simbrief_type ?? $aircraft->icao }}">
              </div>
              <div class="col">
                <label for="reg">Registration</label>
                <input type="text" class="form-control" value="{{ $aircraft->registration }}" maxlength="6" disabled>
                <input type="hidden" name="reg" value="{{ $aircraft->registration }}">
              </div>
              @if($aircraft->registration != $aircraft->name)
                <div class="col">
                  <label for="acname">Name</label>
                  <input type="text" class="form-control" value="{{ $aircraft->name }}" disabled>
                </div>
              @endif
              @if($aircraft->fuel_onboard > 0)
                <div class="col">
                  <label for="fuel_onboard">Fuel OnBoard</label>
                  <input type="text" class="form-control"
                    value="@if(setting('units.fuel') === 'kg'){{ number_format($aircraft->fuel_onboard / 2.205) }}@else{{ number_format($aircraft->fuel_onboard) }}@endif {{ setting('units.fuel') }}" disabled>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card mb-2">
          <div class="card-header p-1">
            <h6 class="m-1 p-0"><i class="fas fa-paper-plane float-right"></i>
            @lang('pireps.flightinformations') For <b>{{ $flight->airline->icao }}{{ $flight->flight_number }} ({{ \App\Models\Enums\FlightType::label($flight->flight_type) }})</b></h6>
          </div>
          <div class="card-body p-1">
            <div class="row mb-1">
              <div class="col">
                <label for="dorig">Departure Airport</label>
                <input id="dorig" type="text" class="form-control" maxlength="4" value="{{ $flight->dpt_airport_id }}" disabled>
                <input name="orig" type="hidden" maxlength="4" value="{{ $flight->dpt_airport_id }}">
              </div>
              <div class="col">
                <label for="ddest">Arrival Airport</label>
                <input id="ddest" type="text" class="form-control" maxlength="4" value="{{ $flight->arr_airport_id }}" disabled>
                <input name="dest" type="hidden" maxlength="4" value="{{ $flight->arr_airport_id }}">
              </div>
              <div class="col">
                <label for="altn">Alternate Airport</label>
                <input name="altn" type="text" class="form-control" maxlength="4" value="{{ $flight->alt_airport_id ?? 'AUTO' }}">
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-8">
                <label for="route">Preferred Company Route</label>
                <input name="route" type="text" class="form-control" value="{{ $flight->route }}">
              </div>
              <div class="col">
                <label for="fl">Preferred Flight Level</label>
                <input id="fl" name="fl" type="text" class="form-control" maxlength="5" value="{{ $flight->level }}" onchange="CheckFL()">
              </div>
            </div>
            <div class="row row-cols-4 mb-1">
              <div class="col">
                <label class="pl-1 mb-1" for="dof">Date Of Flight (UTC)</label>
                <input id="dof" type="text" class="form-control" maxlength="9" disabled>
              </div>
              <div class="col">
                <label class="pl-1 mb-1" for="etd">Estimated Departure Time (UTC)</label>
                <div class="input-group">
                  <input id="deph" name="deph" type="number" class="form-control text-center" min="0" max="23" maxlength="2">
                  <div class="input-group-append ml-0 mr-0"><span class="input-group-text pr-1 pl-1">:</span></div>
                  <input id="depm" name="depm" type="number" class="form-control text-center" min="0" max="59" maxlength="2">
                </div>
              </div>
              <div class="col">
                @if($flight->dpt_time)
                  <label class="pl-1 mb-1" for="std">Scheduled Departure Time (UTC)</label>
                  <input type="text" class="form-control" value="{{ $flight->dpt_time }}" disabled>
                @endif
              </div>
              <div class="col">
                @if($flight->arr_time)
                  <label class="pl-1 mb-1" for="std">Scheduled Arrival Time (UTC)</label>
                  <input type="text" class="form-control" value="{{ $flight->arr_time }}" disabled>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="card mb-2">
          <div class="card-header p-1">
            <h6 class="m-1 p-0">
              <i class="fas fa-balance-scale float-right"></i>
              Configuration And Load Information For <b>{{ $aircraft->registration }} ({{ $aircraft->subfleet->name }})</b>
            </h6>
          </div>
          <div class="card-body p-1">
            <div class="row row-cols-4 mb-1">
              {{-- Pax Fares --}}
                @foreach($pax_load_sheet as $pfare)
                  <div class="col">
                    <label for="LoadFare{{ $pfare['id'] }}">{{ $pfare['name'] }} [Max: {{ $pfare['capacity'] }}]</label>
                    <input id="LoadFare{{ $pfare['id'] }}" type="text" class="form-control" value="{{ $pfare['count'] }}" disabled>
                  </div>
                @endforeach
              {{-- Cargo Fares --}}
                @foreach($cargo_load_sheet as $cfare)
                  <div class="col">
                    <label for="LoadFare{{ $cfare['id'] }}">{{ $cfare['name'] }} @if($tbagload > 0) [Available: @else [Max: @endif {{ number_format($cfare['capacity'] - $tbagload) }} {{ setting('units.weight') }}]</label>
                    <input id="LoadFare{{ $cfare['id'] }}" type="text" class="form-control" value="{{ number_format($cfare['count']) }} {{ setting('units.weight') }}" disabled>
                  </div>
                @endforeach
            </div>
            @if(isset($tpayload) && $tpayload > 0)
              {{-- Display The Weights Generated --}}
              <div class="row row-cols-4 mb-1">
                @if($tpaxload)
                  <div class="col">
                    <label for="tdPaxLoad">Pax Weight</label>
                    <input id="tdPaxLoad" type="text" class="form-control" value="{{ number_format($tpaxload) }} {{ setting('units.weight') }}" disabled>
                  </div>
                  <div class="col">
                    <label for="tBagLoad">Baggage Weight</label>
                    <input id="tBagLoad" type="text" class="form-control" value="{{ number_format($tbagload) }} {{ setting('units.weight') }}" disabled>
                  </div>
                @endif
                @if($tpaxload && $tcargoload)
                  <div class="col">
                    <label for="tCargoload">Cargo Weight</label>
                    <input id="tCargoload" type="text" class="form-control" value="{{ number_format($tcargoload) }} {{ setting('units.weight') }}" disabled>
                  </div>
                @endif
                <div class="col">
                  <label for="tPayload">Total Payload</label>
                  <input id="tPayload" type="text" class="form-control" value="{{ number_format($tpayload) }} {{ setting('units.weight') }}" disabled>
                </div>
              </div>
            @endif
          </div>
        </div>

        {{-- Prepare Form Fields For SimBrief --}}
          <input type="hidden" name="acdata" value="{'paxwgt':{{ round($pax_weight + $bag_weight) }}}">
          @if($tpaxfig)
            <input type="hidden" name="pax" value="{{ $tpaxfig }}">
          @elseif(!$tpaxfig && $tcargoload)
            <input type="hidden" name="pax" value="0">
          @endif
          @if($tcargoload)
            <input type='hidden' name='cargo' value="{{ number_format(($tcargoload / 1000),1) }}">
          @endif
          @if(isset($tpayload) && $tpayload > 0)
            <input type="hidden" name="manualrmk" value="Load Distribution {{ $loaddist }}">
          @endif
          <input type="hidden" name="airline" value="{{ $flight->airline->icao }}">
          <input type="hidden" name="fltnum" value="{{ $flight->flight_number }}">
          @if(setting('simbrief.callsign', false))
            <input type="hidden" name="callsign" value="{{ $user->ident }}">
          @elseif(filled($flight->callsign))
            <input type="hidden" name="callsign" value="{{ $flight->airline->icao }}{{ $flight->callsign }}">
          @endif
          @if(setting('simbrief.name_private', false))
            <input type="hidden" name="cpt" value="{{ $user->name_private }}">
          @endif
          <input type="hidden" id="steh" name="steh" maxlength="2">
          <input type="hidden" id="stem" name="stem" maxlength="2">
          <input type="hidden" id="date" name="date" maxlength="9">
          <input type="hidden" name="selcal" value="BK-FS">
          <input type="hidden" name="omit_sids" value="0">
          <input type="hidden" name="omit_stars" value="0">
        {{-- For more info about form fields and their details check SimBrief Forum / API Support --}}
      </div>

      <div class="col-4">
        <div class="card mb-2">
          <div class="card-header p-1"><h6 class="m-1 p-0"><i class="fas fa-tasks float-right"></i>Planning Options</h6></div>
          <div class="card-body p-0">
            <table class="table table-sm table-borderless table-striped mb-0">
              <tr>
                <td class="align-middle" style="width: 30%;">Cruise Fuel Policy:</td>
                <td class="align-middle">
                  <select id="cruise" name="cruise" class="form-control form-control-sm" onchange="DisableCI()">
                    <option value="LRC">Long Range Cruise (LRC)</option>
                    <option value="CI" selected>Cost Index (CI)</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Cost Index:</td>
                <td class="align-middle"><input type="text" id="civalue" name="civalue" class="form-control form-control-sm" maxlength="4" value="AUTO"></td>
              </tr>
              <tr>
                <td class="align-middle">Cont Fuel:</td>
                <td class="align-middle">
                  <select name="contpct" class="form-control form-control-sm">
                    <option value="0">None</option>
                    <option value="auto">AUTO</option>
                    <option value="easa">EASA</option>
                    <option value="0.03/5">3% or 05 MIN</option>
                    <option value="0.03/10">3% or 10 MIN</option>
                    <option value="0.03/15">3% or 15 MIN</option>
                    <option value="0.05/5" selected>5% or 05 MIN</option>
                    <option value="0.05/10">5% or 10 MIN</option>
                    <option value="0.05/15">5% or 15 MIN</option>
                    <option value="0.03">3%</option>
                    <option value="0.05">5%</option>
                    <option value="0.1">10%</option>
                    <option value="0.15">15%</option>
                    <option value="3">03 MIN</option>
                    <option value="5">05 MIN</option>
                    <option value="10">10 MIN</option>
                    <option value="15">15 MIN</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Reserve Fuel:</td>
                <td class="align-middle">
                  <select name="resvrule" class="form-control form-control-sm">
                    <option value="auto">AUTO</option>
                    <option value="0">0 MIN</option>
                    <option value="15">15 MIN</option>
                    <option value="30" selected>30 MIN</option>
                    <option value="45">45 MIN</option>
                    <option value="60">60 MIN</option>
                    <option value="75">75 MIN</option>
                    <option value="90">90 MIN</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">SID/STAR Type:</td>
                <td class="align-middle">
                  <select name="find_sidstar" class="form-control form-control-sm">
                    <option value="C">Conventional</option>
                    <option value="R" selected>RNAV</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Plan Stepclimbs:</td>
                <td class="align-middle">
                  <select id="stepclimbs" name="stepclimbs" class="form-control form-control-sm" onchange="DisableFL()">
                    <option value="0" selected>Disabled</option>
                    <option value="1">Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">ETOPS Planning:</td>
                <td class="align-middle">
                  <select name="etops" class="form-control form-control-sm">
                    <option value="0" selected>Disabled</option>
                    <option value="1">Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Altn Airports:</td>
                <td class="align-middle">
                  <select name="altn_count" class="form-control form-control-sm">
                    <option value="1">1</option>
                    <option value="2" selected>2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="card mb-2">
          <div class="card-header p-1"><h6 class="m-1 p-0"><i class="fas fa-info-circle float-right"></i>Briefing Options</h6></div>
          <div class="card-body p-0">
            <table class="table table-sm table-borderless table-striped mb-0">
              <tr>
                <td class="align-middle" style="width: 30%;">OFP Units:</td>
                <td class="align-middle">
                  <select id="kgslbs" name="units" class="form-control form-control-sm">
                    @if(setting('units.weight') === 'kg')
                      <option value="KGS" selected>KGS</option>
                      <option value="LBS">LBS</option>
                    @else
                      <option value="KGS">KGS</option>
                      <option value="LBS" selected>LBS</option>
                    @endif
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">OFP Format:</td>
                <td class="align-middle">
                  <select name="planformat" class="form-control form-control-sm">
                    <option value="lido" selected>Lido</option>
                    <option value="ber">Air Berlin</option>
                    <option value="baw">British Airways</option>
                    <option value="ezy">Easy Jet</option>
                    <option value="gwi">German Wings</option>
                    <option value="klm">KLM</option>
                    <option value="dlh">Lufthansa</option>
                    <option value="qfa">Quantas</option>
                    <option value="ryr">Ryan Air</option>
                    <option value="thy">SunExpress</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Detailed Navlog:</td>
                <td class="align-middle">
                  <select name="navlog" class="form-control form-control-sm">
                    <option value="0">Disabled</option>
                    <option value="1" selected>Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Runway Analysis:</td>
                <td class="align-middle">
                  <select name="tlr" class="form-control form-control-sm">
                    <option value="0">Disabled</option>
                    <option value="1" selected>Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Include NOTAMS:</td>
                <td class="align-middle">
                  <select name="notams" class="form-control form-control-sm">
                    <option value="0">Disabled</option>
                    <option value="1" selected>Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">FIR NOTAMS:</td>
                <td class="align-middle">
                  <select name="firnot" class="form-control form-control-sm">
                    <option value="0" selected>Disabled</option>
                    <option value="1">Enabled</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td class="align-middle">Flight Maps:</td>
                  <td class="align-middle">
                    <select name="maps" class="form-control form-control-sm">
                      <option value="detail" selected>Detailed</option>
                      <option value="simple">Simple</option>
                      <option value="none">None</option>
                    </select>
                  </td>
              </tr>
            </table>
          </div>
        </div>

        <div class="card bg-transparent mb-2 p-0 text-right border-0">
          <input type="button" class="btn btn-primary" value="Generate"
              onclick="simbriefsubmit('{{ $flight->id }}', '{{ $aircraft->id }}', '{{ url(route('frontend.simbrief.briefing', [''])) }}');">
        </div>
      </div>
    </div>
  </form>

@endsection
@section('scripts')
  <script src="{{public_asset('/assets/global/js/simbrief.apiv1.js')}}"></script>
  <script type="text/javascript">
    // Get current UTC time, add 45 minutes to it and format according to Simbrief API
    // Script also rounds the minutes to nearest 5 to avoid a Departure time like 1538
    // If you need to reduce the margin of 45 mins, change value below
    let d = new Date();
    const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    d.setMinutes(d.getMinutes() + 45); // Change the value here
    let deph = ("0" + d.getUTCHours(d)).slice(-2);
    let depm = d.getUTCMinutes(d);
    if (depm < 55) {
      depm = Math.ceil(depm / 5) * 5;
    }
    if (depm > 55) {
      depm = Math.floor(depm / 5) * 5;
    }
    depm = ("0" + depm).slice(-2);
    dept = deph + ":" + depm;
    let dof = ("0" + d.getUTCDate()).slice(-2) + months[d.getUTCMonth()] + d.getUTCFullYear();
    document.getElementById("dof").setAttribute('value', dof);
    document.getElementById("date").setAttribute('value', dof); // Sent to Simbrief
    document.getElementById("deph").setAttribute('value', deph); // Sent to SimBrief
    document.getElementById("depm").setAttribute('value', depm); // Sent to SimBrief
  </script>
  <script type="text/javascript">
    // Calculate the Scheduled Enroute Time for Simbrief API
    // Your PHPVMS flight_time value must be from BLOCK to BLOCK
    // Including departure and arrival taxi times
    // If this value is not correctly calculated and configured
    // Simbrief CI (Cost Index) calculation will not provide realistic results
    let num = {{ $flight->flight_time }};
    let hours = (num / 60);
    let rhours = Math.floor(hours);
    let minutes = (hours - rhours) * 60;
    let rminutes = Math.round(minutes);
    document.getElementById("steh").setAttribute('value', rhours.toString()); // Sent to Simbrief
    document.getElementById("stem").setAttribute('value', rminutes.toString()); // Sent to Simbrief
    // If No Block Time Provided Revert Back To Long Range Cruise
    // And Do Not Send Block Time Values
    if (rhours == 0 && rminutes == 0) {
      document.getElementById("cruise").value = "LRC";
      document.getElementById("civalue").disabled = true;
      document.getElementById("steh").disable = true;
      document.getElementById("stem").disable = true;
    }
  </script>
  <script type="text/javascript">
    // Disable Submitting CI Value when LRC is selected
    function DisableCI() {
      let cruise = document.getElementById("cruise").value;
      if (cruise === "LRC") {
        document.getElementById("civalue").disabled = true
      } else {
        document.getElementById("civalue").disabled = false
      }
    }
  </script>
  <script type="text/javascript">
    // Change StepClimbs Option According to Flight Level
    // If No FL is defined, set StepClimbs to Enabled
    // If or When FL is defined, set it to Disabled
    // Check SimBrief API details for requirements of StepClimb Option
    let level = Number(document.getElementById("fl").value);
    if (level === 0) {
      document.getElementById("stepclimbs").value = "1";
      }
    function CheckFL() {
      level = Number(document.getElementById("fl").value);
      if (level === 0) {
        document.getElementById("stepclimbs").value = "1";
      } else {
        document.getElementById("stepclimbs").value = "0";
      }
    }
  </script>
  <script type="text/javascript">
    // Disable Submitting a fixed flight level for Stepclimb option to work
    // Script is related to Plan Step Climbs selection
    function DisableFL() {
      let climb = document.getElementById("stepclimbs").value;
      if (climb === "0") {
        document.getElementById("fl").disabled = false
      }
      if (climb === "1") {
        document.getElementById("fl").disabled = true
      }
    }
  </script>
@endsection

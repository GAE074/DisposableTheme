@extends('app')
@section('title', 'SimBrief Flight Planning')

@section('content')
<div class="row">
  <div class="col">
    <h3 class="card-title">Create Simbrief Briefing</h3>
  </div>
</div>

  @foreach($aircraft as $acdetails)
    @php
      $simbrieftype = $acdetails->icao ;
      $subflid = $acdetails->subfleet_id ;
      if($acdetails->icao === 'A20N') { $simbrieftype = 'A320'; }
      if($acdetails->icao === 'A21N') { $simbrieftype = 'A321'; }
      if($acdetails->icao === 'B77L') { $simbrieftype = 'B77F'; }
      if($acdetails->icao === 'B773') { $simbrieftype = 'B77W'; }
      if($acdetails->icao === 'E35L') { $simbrieftype = 'E135'; }
    @endphp
  @endforeach

<form id="sbapiform">
  <div class="row">
    <div class="col-8">
      <div class="card mb-2">
        <div class="card-header p-1"><h6 class="m-1 p-0"><i class="fas fa-plane float-right"></i>Aircraft Details</h6></div>
        <div class="card-body p-1">
          <div class="row row-cols-3">
            <div class="col">
              <label for="type">Type</label>
              <input type="text" class="form-control" value="{{ $acdetails->icao }}" maxlength="4" disabled/>
              <input type="hidden" id="type" name="type" class="form-control" value="{{ $simbrieftype }}" maxlength="4"/>
            </div>
            <div class="col">
              <label for="reg">Registration</label>
              <input type="text" class="form-control" value="{{ $acdetails->registration }}" maxlength="6" disabled/>
              <input type="hidden" id="reg" name="reg" value="{{ $acdetails->registration }}"/>
            </div>
            @if($acdetails->registration <> $acdetails->name)
              <div class="col">
                <label for="acname">Name</label>
                <input type="text" class="form-control" value="{{ $acdetails->name }}" disabled/>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="card mb-2">
        <div class="card-header p-1">
          <h6 class="m-1 p-0"><i class="fas fa-paper-plane float-right"></i>@lang('pireps.flightinformations') for <b>{{ $flight->airline->icao }} {{ $flight->flight_number }}</b></h6>
        </div>
        <div class="card-body p-1">
          <div class="row mb-1">
            <div class="col">
              <label for="dorig">Departure Airport</label>
              <input id="dorig" type="text" class="form-control" maxlength="4" value="{{ $flight->dpt_airport_id }}" disabled/>
              <input id="orig" name="orig" type="hidden" maxlength="4" value="{{ $flight->dpt_airport_id }}"/>
            </div>
            <div class="col">
              <label for="ddest">Arrival Airport</label>
              <input id="ddest" type="text" class="form-control" maxlength="4" value="{{ $flight->arr_airport_id }}" disabled/>
              <input id="dest" name="dest" type="hidden" maxlength="4" value="{{ $flight->arr_airport_id }}"/>
            </div>
            <div class="col">
              <label for="altn">Alternate Airport</label>
              <input id="altn" name="altn" type="text" class="form-control" maxlength="4" value="{{ $flight->alt_airport_id ?? 'AUTO' }}"/>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col-8">
              <label for="route">Preferred Company Route</label>
              <input id="route" name="route" type="text" class="form-control" value="{{ $flight->route }}"/>
            </div>
            <div class="col">
              <label for="fl">Preferred Flight Level</label>
              <input id="fl" name="fl" type="text" class="form-control" maxlength="5" value="{{ $flight->level }}"/>
            </div>
          </div>
          <div class="row mb-1">
            <div class="col">
            @if($flight->dpt_time)
              <label for="std">Scheduled Departure Time (UTC)</label>
              <input id="std" type="text" class="form-control" maxlength="4" value="{{ $flight->dpt_time }}" disabled/>
            @endif
            </div>
            <div class="col">
              <label for="etd">Estimated Departure Time (UTC)</label>
              <input id="etd" type="text" class="form-control" maxlength="4" disabled/>
            </div>
            <div class="col">
              <label for="dof">Date Of Flight (UTC)</label>
              <input id="dof" type="text" class="form-control" maxlength="4" disabled/>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-2">
      @foreach($subfleets as $subfleet)
        @if($subfleet->id == $subflid)
          <div class="card-header p-1">
            <h6 class="m-1 p-0">
              <i class="fas fa-balance-scale float-right"></i>
              Configuration And Load Information For <b>{{ $subfleet->name }} ; {{ $acdetails->registration }}</b>
            </h6>
          </div>
          <div class="card-body p-1">
            {{-- Generate Load Figures --}}
            <div class="row row-cols-3 mb-1">
              {{-- Create and send some data to the $loadarray for MANUALRMK generation --}}
              @php $loadarray = [] ; @endphp
              @foreach($subfleet->fares as $fare)
                @if($fare->capacity > 0)
                  @php
                    $randomloadperfare = ceil(($fare->capacity * (rand($loadmin, $loadmax))) /100);
                    $loadarray[] = ['SeatType' => $fare->code];
                    $loadarray[] = ['SeatLoad' => $randomloadperfare];
                  @endphp
                  <div class="col">
                    <label for="LoadFare{{ $fare->id }}">{{ $fare->name }} Load [Max: {{ number_format($fare->capacity) }}]</label>
                    <input id="LoadFare{{ $fare->id }}" type="text" class="form-control" 
                          value="{{ number_format($randomloadperfare) }} @if($randomloadperfare > '900') {{ setting('units.weight') }} @endif" disabled/>
                  </div>
                @endif
              @endforeach
              @php
                $loadcollection = collect($loadarray) ;
                $totalgenload = $loadcollection->sum('SeatLoad') ;
              @endphp
            </div>
            @if($totalgenload > 0 && $totalgenload < 900)
              <input type="hidden" name="acdata" value="{'paxwgt':{{ $pax_weight }}}">
              <div class="row row-cols-3 mb-1">
                <div class="col">
                  @if(setting('units.weight') === 'kg')
                    @php $estimatedpayload = number_format(round(($pax_weight * $totalgenload) / 2.205)) ; @endphp
                  @else
                    @php $estimatedpayload = number_format(round($pax_weight * $totalgenload)) ; @endphp
                  @endif
                  <label for="EstimatedLoad">Estimated Payload For {{ $totalgenload }} Pax</label>
                  <input id="EstimatedLoad" type="text" class="form-control" value="{{ $estimatedpayload }} {{ setting('units.weight') }}" disabled/>
                </div>
              </div>
              <input type="hidden" id="pax" name="pax" class="form-control" value="{{ $totalgenload }}"/>
            @elseif($totalgenload > 900)
              <input type='hidden' id="pax" name='pax' value='0' maxlength='3'>
              <input type='hidden' id="cargo" name='cargo' value="{{ $totalgenload }}" maxlength='7'>
            @endif
          </div>
        @endif
      @endforeach
      </div>

      {{--
        Here we generate the MANUALRMK which is sent to SimBrief and displayed in the generated
        ofp as Dispatch Remarks. $loadarray is created and filled with data during random load
        generation, it holds each fare's code and the generated load then we are imploding that
        array to get the fare codes and load counts.

        Returned string will be like Load Distribution Y 132 C 12 F 4
      --}}
      @if($totalgenload > 0)
        @php
          $loaddisttxt =  "Load Distribution ";
          $loaddist = implode(' ', array_map(
                      function ($v, $k) {
                          if(is_array($v)){
                            return implode('&'.' '.':', $v);
                          }else{
                            return $k.':'.$v;
                          }
                        },
                      $loadarray,	array_keys($loadarray)
                    ));
        @endphp
        <input type="hidden" name="manualrmk" value="{{ $loaddisttxt }}{{ $loaddist }}">
      @endif
        <input type="hidden" name="airline" value="{{ $flight->airline->icao }}">
        <input type="hidden" name="fltnum" value="{{ $flight->flight_number }}">
        <input type="hidden" id="steh" name="steh" maxlength="2">
        <input type="hidden" id="stem" name="stem" maxlength="2">
        <input type="hidden" id="date" name="date" maxlength="9">
        <input type="hidden" id="deph" name="deph" maxlength="2">
        <input type="hidden" id="depm" name="depm" maxlength="2">
        <input type="hidden" name="selcal" value="BK-FS">
        <input type="hidden" name="planformat" value="lido">
        <input type="hidden" name="omit_sids" value="0">
        <input type="hidden" name="omit_stars" value="0">
        <input type="hidden" name="cruise" value="CI">
        <input type="hidden" name="civalue" value="AUTO">
    </div>

    <div class="col-4">
      <div class="card mb-2">
        <div class="card-header p-1"><h6 class="m-1 p-0"><i class="fas fa-tasks float-right"></i>Planning Options</h6></div>
        <div class="card-body p-0">
          <table class="table table-sm table-borderless table-striped mb-0">
            <tr>
              <td>Cont Fuel:</td>
              <td>
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
              <td>Reserve Fuel:</td>
              <td>
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
              <td>SID/STAR Type:</td>
              <td>
                <select name="find_sidstar" class="form-control form-control-sm">
                  <option value="C">Conventional</option>
                  <option value="R" selected>RNAV</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Plan Stepclimbs:</td>
              <td>
                <select id="stepclimbs" name="stepclimbs" class="form-control form-control-sm" onchange="DisableFL()">
                  <option value="0" selected>Disabled</option>
                  <option value="1">Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>ETOPS Planning:</td>
              <td>
                <select name="etops" class="form-control form-control-sm">
                  <option value="0" selected>Disabled</option>
                  <option value="1">Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Altn Airports:</td>
              <td>
                <select name="altn_count" class="form-control form-control-sm">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4" selected>4</option>
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
              <td>Units:</td>
              <td>
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
              <td>Detailed Navlog:</td>
              <td>
                <select name="navlog" class="form-control form-control-sm">
                  <option value="0">Disabled</option>
                  <option value="1" selected>Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Runway Analysis:</td>
              <td>
                <select name="tlr" class="form-control form-control-sm">
                  <option value="0">Disabled</option>
                  <option value="1" selected>Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Include NOTAMS:</td>
              <td>
                <select name="notams" class="form-control form-control-sm">
                  <option value="0">Disabled</option>
                  <option value="1" selected>Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>FIR NOTAMS:</td>
              <td>
                <select name="firnot" class="form-control form-control-sm">
                  <option value="0" selected>Disabled</option>
                  <option value="1">Enabled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>Flight Maps:</td>
                <td>
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
            onclick="simbriefsubmit('{{ $flight->id }}', '{{ url(route('frontend.simbrief.briefing', [''])) }}');">
      </div>
    </div>
  </div>
</form>


@endsection
@section('scripts')
<script src="{{public_asset('/assets/global/js/simbrief.apiv1.js')}}"></script>
<script type="text/javascript">
  // ******
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
<script type="text/javascript">
  // ******
  // Get current UTC time, add 45 minutes to it and format according to Simbrief API
  // Script also rounds the minutes to nearest 5 to avoid a Departure time like 1538 ;)
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
  dept = deph + depm;
  let dof = ("0" + d.getUTCDate()).slice(-2) + months[d.getUTCMonth()] + d.getUTCFullYear();

  document.getElementById("dof").setAttribute('value', dof);
  document.getElementById("etd").setAttribute('value', dept);
  document.getElementById("date").setAttribute('value', dof); // Sent to Simbrief
  document.getElementById("deph").setAttribute('value', deph); // Sent to SimBrief
  document.getElementById("depm").setAttribute('value', depm); // Sent to SimBrief
</script>
<script type="text/javascript">
  // ******
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
</script>
<script type="text/javascript">
  // *** Simple Aircraft Selection With Dropdown Change
  // *** Also keep Generate button hidden until a valid AC selection
  const $oldlink = document.getElementById("mylink").href;

  function checkacselection() {
    if (document.getElementById("aircraftselection").value === "ZZZZZ") {
      document.getElementById('mylink').style.visibility = 'hidden';
    } else {
      document.getElementById('mylink').style.visibility = 'visible';
    }
    var $selectedac = document.getElementById("aircraftselection").value;
    var $newlink = "&aircraft_id=".concat($selectedac);
    document.getElementById("mylink").href = $oldlink.concat($newlink);
  }
</script>
@endsection

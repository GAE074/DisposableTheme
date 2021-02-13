@extends('app')
@section('title', 'Briefing')

@section('content')
<div class="row">
  <div class="col text-left">
    <h3 class="card-title">
      {{ $simbrief->xml->general->icao_airline }}{{ $simbrief->xml->general->flight_number }} : {{ $simbrief->xml->origin->icao_code }} to {{ $simbrief->xml->destination->icao_code }}
    </h3>
  </div>
  <div class="col text-right p-0 m-0">
    @if (empty($simbrief->pirep_id))
      <a class="btn btn-sm btn-info ml-1" href="{{ url(route('frontend.simbrief.prefile', [$simbrief->id])) }}">Prefile Manual PIREP</a>
    @endif
    <a class="btn btn-sm btn-warning" href="{{ url(route('frontend.simbrief.generate_new', [$simbrief->id])) }}">Generate New OFP</a>
  </div>
</div>

<div class="row row-cols-2">
  <div class="col-7">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-info-circle float-right"></i>&nbsp;Dispatch Information</h5></div>
      <div class="card-body p-1">
        <div class="row row-cols-3 mb-1">
          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Flight</p>
            <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->general->icao_airline }}{{ $simbrief->xml->general->flight_number }}</p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Departure</p>
            <p class="border border-dark rounded p-1 small text-monospace">
              {{ $simbrief->xml->origin->icao_code }}@if(!empty($simbrief->xml->origin->plan_rwy)) / Rwy.{{ $simbrief->xml->origin->plan_rwy }} @endif
            </p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Arrival</p>
            <p class="border border-dark rounded p-1 small text-monospace">
              {{ $simbrief->xml->destination->icao_code }}@if(!empty($simbrief->xml->destination->plan_rwy)) / Rwy.{{ $simbrief->xml->destination->plan_rwy }} @endif
            </p>
          </div>
        </div>

        <div class="row row-cols-3 mb-1">
          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Aircraft</p>
            <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->aircraft->name }} / {{ $simbrief->xml->aircraft->reg }}</p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Est. Enroute Time</p>
            <p class="border border-dark rounded p-1 small text-monospace">@minutestotime($simbrief->xml->times->est_time_enroute / 60)</p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Cruise Altitude</p>
            <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->general->initial_altitude }}</p>
          </div>
        </div>

        <div class="row row-cols-3 mb-1">
          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Min Block Fuel</p>
            @php
              $bfuel = floatval($simbrief->xml->fuel->plan_ramp);
              $rbf = fmod($bfuel,100);
              $rbf = 100 - $rbf;
              $minblock = $bfuel + $rbf;
            @endphp
            <p class="border border-dark rounded p-1 small text-monospace">{{-- $simbrief->xml->fuel->plan_ramp --}} {{ $minblock }} {{ $simbrief->xml->params->units }}</p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Trip Fuel</p>
            @php
              $tfuel = floatval($simbrief->xml->fuel->enroute_burn);
              $rtf = fmod($tfuel,10);
              $rtf = 10 - $rtf;
              $tripf = $tfuel + $rtf;
            @endphp
            <p class="border border-dark rounded p-1 small text-monospace">{{-- $simbrief->xml->fuel->enroute_burn --}} {{ $tripf }} {{ $simbrief->xml->params->units }}</p>
          </div>

          <div class="col text-center">
            <p class="small text-uppercase pb-sm-0 mb-sm-1">Remaining Fuel</p>
            @php
              $rfuel = floatval($simbrief->xml->fuel->plan_landing);
              $rlf = fmod($rfuel,10);
              $rlf = 10 - $rlf;
              $remf = $rfuel + $rlf;
            @endphp
            <p class="border border-dark rounded p-1 small text-monospace">{{-- $simbrief->xml->fuel->plan_landing --}} {{ $remf }} {{ $simbrief->xml->params->units }}</p>
          </div>
        </div>

        @if (!empty($simbrief->xml->general->dx_rmk))
          <div class="row">
            <div class="col">
              <p class="small text-uppercase pb-sm-0 mb-sm-1">Dispatch Remarks</p>
              <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->general->dx_rmk  }}</p>
            </div>
          </div>
        @endif

        @if (!empty($simbrief->xml->general->sys_rmk))
          <div class="row">
            <div class="col">
              <p class="small text-uppercase pb-sm-0 mb-sm-1">System Remarks</p>
              <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->general->sys_rmk  }}</p>
            </div>
          </div>
        @endif
      </div>
    </div>

    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-plane float-right"></i>&nbsp;ATC Flight Plan</h5></div>
      <div class="card-body p-1">
        <p class="border border-dark rounded p-1 small text-monospace mb-0">
            {!!  str_replace("\n", "<br>", $simbrief->xml->atc->flightplan_text) !!}
        </p>
      </div>
      <div class="card-footer p-1">

        <div class="float-right">
          <a href="{{ $simbrief->xml->poscon_prefile }}" target="_blank" class="btn btn-sm btn-primary ml-1">File ATC for POSCON</a>
        </div>
        <div class="float-right">
          <a href="{{ $simbrief->xml->pilotedge_prefile }}" target="_blank" class="btn btn-sm btn-primary ml-1">File ATC for PilotEdge</a>
        </div>

        <div class="float-right">
          <form action="https://my.vatsim.net/pilots/flightplan" method="GET" target="_blank">
            <input type="hidden" name="raw" value="{{ $simbrief->xml->atc->flightplan_text }}">
            <input type="hidden" name="fuel_time" value="@secstohhmm($simbrief->xml->times->endurance)">
            <input type="hidden" name="speed" value="{{ substr($simbrief->xml->atc->initial_spd,1) }}">
            <input type="hidden" name="altitude" value="{{ $simbrief->xml->atc->initial_alt }}">
            <input id="vatsim_prefile" type="submit" class="btn btn-sm btn-primary ml-1" value="File ATC for VATSIM"/>
          </form>
        </div>

        <div class="float-right">
          <form action="https://fpl.ivao.aero/api/fp/load" method="POST" target="_blank">
            <input type="hidden" name="CALLSIGN" value="{{ $simbrief->xml->atc->callsign }}"/>
            <input type="hidden" name="RULES" value="I"/>
            <input type="hidden" name="FLIGHTTYPE" value="N"/>
            <input type="hidden" name="NUMBER" value="1"/>
            <input type="hidden" name="ACTYPE" value="{{ $simbrief->xml->aircraft->icaocode }}"/>
            <input type="hidden" name="WAKECAT" value="{{ $wakecat }}"/>
            <input type="hidden" name="EQUIPMENT" value="{{ $equipment }}"/>
            <input type="hidden" name="TRANSPONDER" value="{{ $transponder }}"/>
            <input type="hidden" name="DEPICAO" value="{{ $simbrief->xml->origin->icao_code}}"/>
            <input type="hidden" name="DEPTIME" value="{{ date('Hi', $simbrief->xml->times->est_out->__toString()) }}"/>
            <input type="hidden" name="SPEEDTYPE" value="{{ $simbrief->xml->atc->initial_spd_unit }}"/>
            <input type="hidden" name="SPEED" value="{{ $simbrief->xml->atc->initial_spd }}"/>
            <input type="hidden" name="LEVELTYPE" value="{{ $simbrief->xml->atc->initial_alt_unit }}"/>
            <input type="hidden" name="LEVEL" value="{{ $simbrief->xml->atc->initial_alt }}"/>
            <input type="hidden" name="ROUTE" value="{{ $simbrief->xml->general->route_ifps }}"/>
            <input type="hidden" name="DESTICAO" value="{{ $simbrief->xml->destination->icao_code }}"/>
            <input type="hidden" name="EET" value="@secstohhmm($simbrief->xml->times->est_time_enroute)"/>
            <input type="hidden" name="ALTICAO" value="{{ $simbrief->xml->alternate->icao_code}}"/>
            <input type="hidden" name="ALTICAO2" value="{{ $simbrief->xml->alternate2->icao_code}}"/>
            <input type="hidden" name="OTHER" value="{{ $simbrief->xml->atc->section18 }}"/>
            <input type="hidden" name="ENDURANCE" value="@secstohhmm($simbrief->xml->times->endurance)"/>
            <input type="hidden" name="POB" value="{{ $simbrief->xml->weights->pax_count }}"/>
            <input id="ivao_prefile" type="submit" class="btn btn-sm btn-primary" value="File ATC for IVAO"/>
          </form>
        </div>

        <div class="float-left">
          <a href="http://skyvector.com/?chart=304&fpl={{ $simbrief->xml->origin->icao_code }} {{ $simbrief->xml->general->route }} {{ $simbrief->xml->destination->icao_code }}"
             target="_blank" class="btn btn-sm btn-info">View Route At SkyVector</a>
        </div>
      </div>
    </div>

    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-cloud-moon-rain float-right"></i>&nbsp;Weather</h5></div>
      <div class="card-body p-1 mb-0">
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Departure METAR</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->orig_metar }}</p>
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Departure TAF</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->orig_taf }}</p>
        <hr/>
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Destination METAR</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->dest_metar }}</p>
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Destination TAF</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->dest_taf }}</p>
        <hr/>
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Alternate METAR</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->altn_metar }}</p>
        <p class="small text-uppercase pb-sm-0 mb-sm-1">Alternate TAF</p>
        <p class="border border-dark rounded p-1 small text-monospace">{{ $simbrief->xml->weather->altn_taf }}</p>
      </div>
    </div>
  </div>

  <div class="col-5">
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-download float-right"></i>&nbsp;Download Flight Plan</h5></div>
      <div class="card-body p-1">
        <select id="download_fms_select" class="select2 custom-select mb-1">
          @foreach($simbrief->files as $fms)
            <option value="{{ $fms['url'] }}">{{ $fms['name'] }}</option>
          @endforeach
        </select>
      </div>
      <div class="card-footer p-1">
        <input id="download_fms" type="submit" class="btn btn-sm btn-primary float-right" value="Download"/>
      </div>
    </div>
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-file-pdf float-right"></i>&nbsp;OFP</h5></div>
      <div class="card-body p-1 overflow-auto" style="max-height: 850px;">
        {!! $simbrief->xml->text->plan_html !!}
      </div>
    </div>
  </div>
</div>

<div class="card mb-2">
  <div class="card-header p-1"><h5 class="m-1 p-0"><i class="fas fa-map float-right"></i>&nbsp;Flight Maps</h5></div>
  <div class="card-body p-1">
    <div class="row row-cols-3">
      @foreach($simbrief->images as $image)
        <div class="col text-center">
          <div class="card mb-1 p-0 shadow-none border-0">
            <img class="rounded" src="{{ $image['url'] }}" alt="{{ $image['name'] }}"/>
            <p class="mb-0 text-muted">{{ $image['name'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

@endsection

@section('scripts')
  <script>
    $(document).ready(function () {
      $("#download_fms").click(e => {
        e.preventDefault();
        const select = document.getElementById("download_fms_select");
        const link = select.options[select.selectedIndex].value;
        console.log('Downloading FMS: ', link);
        window.open(link, '_blank');
      });
    });
  </script>
@endsection

@extends('app')
@section('title', __('common.profile'))

@section('content')
{{-- First Row --}}
<div class="row">
  {{-- Main Left --}}
  <div class="col-md-4">
    {{-- User Card --}}
    <div class="card mb-2">
      <div class="card-header p-1"><h5 class="m-0 p-1"><i class="flag-icon flag-icon-{{ $user->country }} float-right"></i>{{ $user->name_private }}</h5></div>
      <div class="media p-1">
        @if ($user->avatar == null)
          <img src="{{ public_asset('/image/nophoto.jpg') }}" class="mr-1 rounded border border-dark img-h125">
        @else
          <img src="{{ $user->avatar->url }}" class="mr-1 rounded border border-dark img-h125">
        @endif  
        <div class="media-body p-0">
          @if(Auth::check() && $user->id === Auth::user()->id)
            <table class="table table-sm table-borderless">
              <tr>
                <td style="width: 20%">@lang('common.email')</td>
                <td>
                  <span id="email_show" style="display: none"><i class="fas fa-eye-slash mr-1" onclick="emailHide()"></i>{{ $user->email }}</span>
                  <span id="email_hide"><i class="fas fa-eye mr-1" onclick="emailShow()"></i>Show email</span>
                </td>
              </tr>
              <tr>
                <td>@lang('profile.apikey')</td>
                <td>
                  <span id="apiKey_show" style="display: none"><i class="fas fa-eye-slash mr-1" onclick="apiKeyHide()"></i>{{ $user->api_key }}</span>
                  <span id="apiKey_hide"><i class="fas fa-eye mr-1" onclick="apiKeyShow()"></i>@lang('profile.apikey-show')&nbsp;(@lang('profile.dontshare'))</span>
                </td>
              </tr>
              <tr>
                <td>@lang('common.timezone')</td>
                <td>{{ $user->timezone }}</td>
              </tr>
              <tr>
                <td>@lang('profile.opt-in')</td>
                <td>{{ $user->opt_in ? __('common.yes') : __('common.no') }}</td>
              </tr>
            </table>
          @endif
        </div>
      </div>
      <div class="card-body p-1">
        <table class="table table-sm table-striped table-borderless text-left">
          @foreach($userFields as $field)
            @if(!$field->private && $field->value)
              <tr>
                <td>{{ $field->description }}</td>
                <td class="text-right">
                  @if($field->name === 'IVAO') <a href='https://www.ivao.aero/Member.aspx?ID={{ $field->value }}' target='_blank'><b>{{ $field->value }}</b></a>
                  @elseif($field->name === 'VATSIM') <a href='https://stats.vatsim.net/search_id.php?id={{ $field->value }}' target='_blank'><b>{{ $field->value }}</b></a>
                  @else {{ $field->value }} @endif</td>
              </tr>
            @endif
          @endforeach
        </table>
        {{-- Show Profile Action Buttons --}}
        @if(Auth::check() && $user->id === Auth::user()->id)
          <div class="float-right">
            <a href="{{ route('frontend.profile.edit', [$user->id]) }}" class="btn btn-primary btn-sm">@lang('common.edit') @lang('common.profile')</a>
            @if (isset($acars) && $acars === true)
              <a href="{{ route('frontend.profile.acars') }}" class="btn btn-info btn-sm" onclick="alert('Copy or Save to \'My Documents/phpVMS\'')">Download ACARS Config</a>
            @endif
            <a href="{{ route('frontend.profile.regen_apikey') }}" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure? This will reset your API key!')">@lang('profile.newapikey')</a> 
          </div>
        @endif
        {{-- End Profile Action Buttons --}}
      </div>
      <div class="card-footer p-1"><h6 class="m-0 p-1">{{ $user->airline->name }}<span class="float-right">{{ $user->ident }}</span></h6></div>
    </div>
    {{-- End User Card --}}
  </div>
  {{-- Main Right --}}
  <div class="col-md-8">
    <div class="row">
      {{-- First Column Group --}}
      <div class="col">
        <div class="card mb-2 text-center">
          <div class="card-body p-1">
            @if (!empty($user->rank->image_url))
              <img src="{{ $user->rank->image_url }}" title="{{ $user->rank->name }}" class="rounded img-mh30"> 
            @else
              <h5>{{ $user->rank->name }}</h5>
            @endif
          </div>
          <div class="card-footer p-1">Rank</div>
        </div>
        @if($user->home_airport)
          <div class="card mb-2 text-center">
            <div class="card-body p-1"><a href="{{route('frontend.airports.show', [$user->home_airport->icao])}}"><h5>{{ $user->home_airport->icao }}</h5></a></div>
            <div class="card-footer p-1">@lang('airports.home')</div>
          </div>
        @endif
        <div class="card mb-2 text-center">
          <div class="card-body p-1"><h5>{{ $user->flights}}</h5></div>
          <div class="card-footer p-1">Flights</div>
        </div>
        @if($user->last_pirep)
          <div class="card mb-2 text-center">
            <div class="card-body p-1"><h5>{{ $user->last_pirep->submitted_at->diffForHumans() }}</h5></div>
            <div class="card-footer p-1">Last Flight</div>
          </div>
        @endif
      </div>
      {{-- Second Column Group --}}
      <div class="col">
        <div class="card mb-2 text-center">
          <div class="card-body p-1">
            @if (!empty($user->airline->logo))
              <img src="{{ $user->airline->logo }}" title="{{ $user->airline->name }}" class="rounded img-mh30"> 
            @else
              <h5>{{ $user->airline->name }}</h5>
            @endif
          </div>
          <div class="card-footer p-1">Airline</div>
        </div>
        @if($user->current_airport)
          <div class="card mb-2 text-center">
            <div class="card-body p-1"><a href="{{route('frontend.airports.show', [$user->current_airport->icao])}}"><h5>{{ $user->current_airport->icao }}</h5></a></div>
            <div class="card-footer p-1">@lang('airports.current')</div>
          </div>
        @endif
        <div class="card mb-2 text-center">
          <div class="card-body p-1"><h5>@minutestotime($user->flight_time)</h5></div>
          <div class="card-footer p-1">@lang('flights.flighthours')</div>
        </div>
        @if(setting('pilots.allow_transfer_hours') === true)
          <div class="card mb-2 text-center">
            <div class="card-body p-1"><h5>@minutestohours($user->transfer_time)h</h5></div>
            <div class="card-footer p-1">@lang('profile.transferhours')</div>
          </div>
        @endif
      </div>
      {{-- Third and Fourth Column Groups / Enabled Only If VMSAcars Module is enabled --}}
      @if (isset($acars) && $acars === true)
        <div class="col">
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avglanding']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgtime']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgdistance']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgfuel']) }} 
        </div>
        <div class="col">
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgscore']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'tottime']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totdistance']) }}
          {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totfuel']) }}
        </div>
      @endif
    </div>
  </div>
</div>
{{-- Extended Stats / Enabled Only If VMSAcars Module is enabled --}}
@if (isset($acars) && $acars === true)
  <div class="clearfix" style="height: 10px;"></div>
  <h4 class="card-title">
    <button class="btn btn-round btn-icon" title="Show/Hide Extended Stats" type="button" data-toggle="collapse" data-target="#ExtendedStats" aria-expanded="false" aria-controls="ExtendedStats">
      <i class="fas fa-arrows-alt-v"></i>
    </button>
    Extended Statistics
  </h4>  
  <div id="ExtendedStats" class="row collapse"> 
    <div class="col">
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avglanding', 'period' => 30]) }}
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgscore', 'period' => 30]) }}
    </div>
    <div class="col">
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgtime', 'period' => 30]) }}
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'tottime', 'period' => 30]) }}
    </div>
    <div class="col">
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgdistance', 'period' => 30]) }}
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totdistance', 'period' => 30]) }}
    </div>
    <div class="col">
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'avgfuel', 'period' => 30]) }}
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totfuel', 'period' => 30]) }}
    </div>
    <div class="col">
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totflight', 'period' => 15]) }}
      {{ Widget::PersonalStats(['disp' => 'full', 'user' => $user->id, 'type' => 'totflight', 'period' => 30]) }}
    </div>
  </div>
@endif
{{-- End Extended Stats --}}
{{-- Show the user's award if they have any in a new row --}}
@if ($user->awards)
  <div class="clearfix" style="height: 10px;"></div>
  <h4 class="card-title">
    <button class="btn btn-round btn-icon" title="Show/Hide Awards" type="button" data-toggle="collapse" data-target="#Awards" aria-expanded="true" aria-controls="Awards">
      <i class="fas fa-arrows-alt-v"></i>
    </button>
    Awards
  </h4>
  <div id="Awards" class="row collapse show">
    <div class="col-sm-12">
      @foreach($user->awards->chunk(4) as $awards)
        <div class="row">
          @foreach($awards as $award)
            <div class="col-sm-3">
              <div class="card mb-2">
                <div class="card-header p-1"><h5 class="m-1 p-0">{{ $award->name }}<i class="fas fa-trophy float-right"></i></h5></div>
                <div class="card-body text-center p-1">@if ($award->image_url)<img src="{{ $award->image_url }}" alt="{{ $award->description }}" style="max-height: 150px;">@endif</div>
                <div class="card-footer p-1">{{ $award->description }}</div>
              </div>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>
@endif
{{-- End Awards --}}

@endsection

@section('scripts')
<script>
  function apiKeyShow(){
    document.getElementById("apiKey_show").style = "display:block";
    document.getElementById("apiKey_hide").style = "display:none";
  }
  function apiKeyHide(){
    document.getElementById("apiKey_show").style = "display:none";
    document.getElementById("apiKey_hide").style = "display:block";
  }
  function emailShow(){
    document.getElementById("email_show").style = "display:block";
    document.getElementById("email_hide").style = "display:none";
  }
  function emailHide(){
    document.getElementById("email_show").style = "display:none";
    document.getElementById("email_hide").style = "display:block";
  }
</script>
@endsection

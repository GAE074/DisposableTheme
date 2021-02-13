<div class="navbar-nav mr-auto d-flex">
  @if(Auth::check())
    <li class="nav-item">
      <span class="nav-link m-1 p-0 ">
        @if (Auth::user()->avatar == null)
          <img src="{{ public_asset('/image/nophoto.jpg') }}" class="rounded img-mh50 border border-dark"/>
        @else
          <img src="{{ Auth::user()->avatar->url }}" class="rounded img-mh50 border border-dark">
        @endif
        {{ Auth::user()->name_private }}
      </span>
    </li>
    <span>
      @ability('admin', 'admin-access')
        <a class="nav-link m-1 p-1 float-left" href="{{ url('/admin') }}"><i class="fas fa-circle-notch" title="@lang('common.administration')"></i></a>
      @endability
      <a class="nav-link m-1 p-1 float-left" href="{{ route('frontend.profile.index') }}"><i class="far fa-user" title="@lang('common.profile')"></i></a>
      <a class="nav-link m-1 p-1 float-left" href="{{ route('frontend.pireps.index') }}"><i class="fas fa-cloud-upload-alt" title="{{ trans_choice('common.pirep', 2) }}"></i></a>
      <a class="nav-link m-1 p-1 float-right" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt" style="color: darkred;" title="@lang('common.logout')"></i></a>
    </span>
    <div class="clearfix" style="height: 10px;"></div>

    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.dashboard.index') }}">
        <i class="fas fa-house-user mr-1"></i>@lang('common.dashboard')
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.pilots.index') }}">
        <i class="fas fa-users mr-1"></i>{{ trans_choice('common.pilot', 2) }}
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.flights.index') }}">
        <i class="fas fa-paper-plane mr-1"></i>{{ trans_choice('common.flight', 2) }}
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.livemap.index') }}">
        <i class="fas fa-globe mr-1"></i>@lang('common.livemap')
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.pireps.index') }}">
        <i class="fas fa-cloud-upload-alt mr-1"></i>{{ trans_choice('common.pirep', 2) }}
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.downloads.index') }}">
        <i class="fas fa-download mr-1"></i>{{ trans_choice('common.download', 2) }}
      </a>
    </li>
    {{-- Show the module links for being logged in --}}
    @foreach($moduleSvc->getFrontendLinks($logged_in=true) as &$link)
      <li class="nav-item">
        <a class="nav-link m-1 p-1" href="{{ url($link['url']) }}">
          <i class="{{ $link['icon'] }} mr-1"></i>{{ ($link['title']) }}
        </a>
      </li>
    @endforeach
  @endif

  {{-- Show the module links that don't require being logged in --}}
  @foreach($moduleSvc->getFrontendLinks($logged_in=false) as &$link)
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ url($link['url']) }}">
        <i class="{{ $link['icon'] }} mr-1"></i>{{ ($link['title']) }}
      </a>
    </li>
  @endforeach

  @foreach($page_links as $page)
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ $page->url }}" target="{{ $page->new_window ? '_blank':'_self' }}">
        <i class="{{ $page['icon'] }} mr-1"></i>{{ $page['name'] }}
      </a>
    </li>
  @endforeach

  @if(!Auth::check())
    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ route('frontend.livemap.index') }}">
        <i class="fas fa-globe mr-1"></i>@lang('common.livemap')
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ url('/register') }}">
        <i class="far fa-id-card mr-1"></i>@lang('common.register')
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link m-1 p-1" href="{{ url('/login') }}">
        <i class="fas fa-sign-in-alt mr-1"></i>@lang('common.login')
      </a>
    </li>
  @endif
</div>

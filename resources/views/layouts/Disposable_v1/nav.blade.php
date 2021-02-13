<ul class="navbar-nav ml-auto d-flex align-items-center">
  @if(Auth::check())
    <li class="nav-item nav-link" style="font-weight: 500;"><i class="fas fa-clock mr-1"></i><span id="clock"></span> UTC</li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('frontend.dashboard.index') }}">
        <i class="fas fa-house-user mr-1"></i>@lang('common.dashboard')
      </a>
    </li>
  @endif

  <li class="nav-item">
    <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
      <i class="fas fa-globe mr-1"></i>@lang('common.livemap')
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
      <i class="fas fa-users mr-1"></i>{{ trans_choice('common.pilot', 2) }}
    </a>
  </li>

  {{-- Show the module links that don't require being logged in --}}
  @foreach($moduleSvc->getFrontendLinks($logged_in=false) as &$link)
    <li class="nav-item">
      <a class="nav-link" href="{{ url($link['url']) }}">
        <i class="{{ $link['icon'] }} mr-1"></i>{{ ($link['title']) }}
      </a>
    </li>
  @endforeach

  @foreach($page_links as $page)
    <li class="nav-item">
      <a class="nav-link" href="{{ $page->url }}" target="{{ $page->new_window ? '_blank':'_self' }}">
        <i class="{{ $page['icon'] }} mr-1"></i>{{ $page['name'] }}
      </a>
    </li>
  @endforeach

  @if(!Auth::check())
      <li class="nav-item">
      <a class="nav-link" href="{{ url('/register') }}">
        <i class="far fa-id-card mr-1"></i>@lang('common.register')
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/login') }}">
        <i class="fas fa-sign-in-alt mr-1"></i>@lang('common.login')
      </a>
    </li>
  @else
    <li class="nav-item">
      <a class="nav-link" href="{{ route('frontend.flights.index') }}">
        <i class="fas fa-paper-plane mr-1"></i>{{ trans_choice('common.flight', 2) }}
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('frontend.pireps.index') }}">
        <i class="fas fa-cloud-upload-alt mr-1"></i>{{ trans_choice('common.pirep', 2) }}
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('frontend.downloads.index') }}">
        <i class="fas fa-download mr-1"></i>{{ trans_choice('common.download', 2) }}
      </a>
    </li>

    {{-- Show the module links for being logged in --}}
    @foreach($moduleSvc->getFrontendLinks($logged_in=true) as &$link)
      <li class="nav-item">
        <a class="nav-link" href="{{ url($link['url']) }}">
          <i class="{{ $link['icon'] }} mr-1"></i>{{ ($link['title']) }}
        </a>
      </li>
    @endforeach

    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if (Auth::user()->avatar == null)
          <img src="{{ public_asset('/image/nophoto.jpg') }}" class="rounded img-mh40 border border-dark"/>
        @else
          <img src="{{ Auth::user()->avatar->url }}" class="rounded img-mh40 border border-dark">
        @endif
      </a>
      <div class="dropdown-menu bg-dropdown dropdown-menu-right">

        <a class="dropdown-item" href="{{ route('frontend.profile.index') }}">
          <i class="far fa-user mr-1"></i>@lang('common.profile')
        </a>

        @ability('admin', 'admin-access')
        <a class="dropdown-item" href="{{ url('/admin') }}">
          <i class="fas fa-circle-notch mr-1"></i>@lang('common.administration')
        </a>
        @endability
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ url('/logout') }}">
          <i class="fas fa-sign-out-alt mr-1"></i>@lang('common.logout')
        </a>
      </div>
    </li>
  @endif

</ul>

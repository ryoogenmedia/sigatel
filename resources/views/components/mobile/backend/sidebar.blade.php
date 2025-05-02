<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('mobile.home') }}">
            <img style="margin-left: 25px" alt="logo-smart-piket" src="{{ asset('ryoogenmedia/logo-dark.png') }}">
        </a>

        <span class="bg-light-primary toggle-semi-nav mt-3">
            <i style="font-weight: bold; font-size:13px" class="ti ti-chevrons-right py-4 px-3 me-2"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            <li class="menu-title">
                <span>Menu Aplikasi</span>
            </li>

            @foreach (config('mobile-sidebar') as $item)
                @if (in_array(auth()->user()->roles, $item['roles']))
                    @if (!isset($item['sub-menus']))
                        <li
                            class="mb-2 no-sub {{ Route::is($item['route-name']) || Route::is($item['is-active']) ? 'active bg-primary-300 mx-2 rounded-4' : '' }}">
                            <a class="{{ Route::is($item['route-name']) || Route::is($item['is-active']) ? 'text-primary-dark fw-bold' : '' }}"
                                href="{{ route($item['route-name']) }}">
                                <i class="iconoir-{{ $item['icon'] }}"></i> {{ $item['title'] }}
                            </a>
                        </li>
                    @else
                        <li
                            class="mb-2 {{ Route::is($item['route-name']) || Route::is($item['is-active']) ? 'active' : '' }}">
                            <a aria-expanded="false" class="" data-bs-toggle="collapse"
                                href="#{{ $item['title'] }}">
                                <i class="iconoir-{{ $item['icon'] }}"></i>
                                {{ $item['title'] }}
                            </a>
                            <ul class="collapse" id="{{ $item['title'] }}">
                                @foreach ($item['sub-menus'] as $item => $subMenu)
                                    <li><a href="{{ route($subMenu['route-name']) }}">{{ $subMenu['title'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endif
            @endforeach
        </ul>
    </div>

    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>

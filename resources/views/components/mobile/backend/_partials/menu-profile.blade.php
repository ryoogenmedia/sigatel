<div aria-labelledby="profilecanvasRight" class="offcanvas offcanvas-end header-profile-canvas" id="profilecanvasRight"
    tabindex="-1">
    <div class="offcanvas-body app-scroll">
        <ul class="">
            <li class="d-flex gap-3 mb-3">
                <div class="d-flex-center">
                    <span class="h-45 w-45 d-flex-center b-r-10 position-relative">
                        <a aria-controls="profilecanvasRight" class="d-block head-icon"
                            data-bs-target="#profilecanvasRight" data-bs-toggle="offcanvas" href="#"
                            role="button">
                            <img alt="avtar" class="b-r-50 h-35 w-35 bg-dark"
                                src="{{ auth()->user()->avatarUrl() }}">
                        </a>
                    </span>
                </div>
                <div class="text-center mt-2">
                    <h6 class="mb-0"> {{ auth()->user()->teacher->name ?? auth()->user()->username }}
                        <img alt="instagram-check-mark" class="w-20 h-20"
                            src="{{ asset('mobile/assets/images/profile-app/01.png') }}">
                    </h6>
                    <p class="f-s-12 mb-0 text-secondary">{{ strtolower(auth()->user()->email) }}</p>
                </div>
            </li>

            <li>
                <a class="f-w-500" href="{{ route('mobile.setting.profile.index') }}">
                    <i class="iconoir-user-love pe-1 f-s-20"></i> Profil
                </a>
            </li>

            <li>
                <a class="f-w-500" href="{{ route('mobile.setting.account.index') }}">
                    <i class="iconoir-lock pe-1 f-s-20"></i> Akun
                </a>
            </li>

            <li>
                @if (auth()->user()->roles == 'teacher')
                    <p style="font-size: 14px" class="text-primary-dark">Status Piket</p>
                    <div>
                        @if (auth()->user()->teacher->duty_status)
                            <span class="badge bg-success text-white">
                                Anda Sedang Piket
                            </span>
                        @else
                            <span class="badge bg-light-danger text-danger">
                                Anda Sedang Tidak Piket
                            </span>
                        @endif
                    </div>
                @endif
            </li>

            <li>
                <a class="mb-0 btn btn-light-danger btn-sm justify-content-center"href="{{ route('mobile.logout') }}"
                    onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
                    role="button">
                    <i class="ph-duotone  ph-sign-out pe-1 f-s-20"></i> Log Out
                </a>
            </li>
        </ul>
    </div>

    <form id="logout-form" action="{{ route('mobile.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

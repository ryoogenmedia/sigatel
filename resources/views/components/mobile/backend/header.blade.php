<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-sm-4 d-flex align-items-center header-left p-0">
                <span class="header-toggle me-3">
                    <i class="iconoir-view-grid"></i>
                </span>
            </div>

            <div class="col-6 col-sm-8 d-flex align-items-center justify-content-end header-right p-0">
                <ul class="d-flex align-items-center">
                    <li class="header-dark">
                        <div class="sun-logo head-icon">
                            <i class="iconoir-sun-light"></i>
                        </div>
                        <div class="moon-logo head-icon">
                            <i class="iconoir-half-moon"></i>
                        </div>
                    </li>

                    {{-- <li class="header-notification">
                        <a aria-controls="notificationcanvasRight" class="d-block head-icon position-relative"
                            data-bs-target="#notificationcanvasRight" data-bs-toggle="offcanvas" href="#"
                            role="button">
                            <i class="iconoir-bell"></i>
                            <span
                                class="position-absolute translate-middle p-1 bg-success border border-light rounded-circle animate__animated animate__fadeIn animate__infinite animate__slower"></span>
                        </a>

                        <div aria-labelledby="notificationcanvasRightLabel"
                            class="offcanvas offcanvas-end header-notification-canvas" id="notificationcanvasRight"
                            tabindex="-1">

                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                                    Notifikasi Pemberian Tugas</h5>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas"
                                    type="button"></button>
                            </div>

                            <div class="offcanvas-body notification-offcanvas-body app-scroll p-0">
                                <div class="head-container notification-head-container">
                                    <div class="notification-message head-box">
                                        <div wire:poll.30000ms>
                                            <x-mobile.backend._partials.notification-data />
                                        </div>

                                        <div class="align-self-start text-end">
                                            <i class="iconoir-xmark close-btn"></i>
                                        </div>
                                    </div>

                                    <x-mobile.backend._partials.empty-notification />
                                </div>
                            </div>
                        </div>
                    </li> --}}

                    <li class="header-profile">
                        <x-mobile.backend._partials.img-profile />

                        <x-mobile.backend._partials.menu-profile />
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

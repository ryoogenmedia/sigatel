@props([
    'title' => '',
    'subtitle' => '',
])
<div class="row m-1">
    <div class="col-12 ">
        <h4 class="main-title">{{ $title }}</h4>

        <ul class="app-line-breadcrumbs mb-3">
            <li class="">
                <a class="f-s-14 f-w-500" href="{{ route('mobile.setting.profile.index') }}">
                    {{ $subtitle }}
                </a>
            </li>
        </ul>
    </div>
</div>

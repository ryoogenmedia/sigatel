<div class="container-fluid">
    <x-mobile.title-page title="Profil" subtitle="Kelola data profil anda." />

    @if (auth()->user()->roles == 'teacher' && isset(auth()->user()->teacher))
        <livewire:mobile.setting.profile.teacher-profile />
    @endif

    @if (auth()->user()->roles == 'parent' && isset(auth()->user()->parent))
        <livewire:mobile.setting.profile.parent-profile />
    @endif
</div>

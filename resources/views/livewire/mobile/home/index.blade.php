<div class="container-fluid mt-3">
    <x-mobile.title-page title="Beranda" subtitle="Ringkasan aplikasi anda berada disini." />

    @if (auth()->user()->roles == 'teacher')
        <livewire:mobile.home.teacher-home />
    @endif

    @if (auth()->user()->roles == 'parent')
        <livewire:mobile.home.parent-home />
    @endif
</div>

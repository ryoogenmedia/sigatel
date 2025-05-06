<div class="container-fluid mt-3">
    @if (auth()->user()->roles == 'teacher')
        <livewire:mobile.home.teacher-home />
    @endif
</div>

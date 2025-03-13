<div wire:ignore class="{{ $formGroupClass ?? 'mb-3' }}">
    @isset($label)
        <label class="form-label {{ isset($required) ? 'required' : '' }}"
            for="{{ $name }}">{{ $label }}</label>
    @endisset

    <div class="input-wrapper">
        <input class="form-control {{ $formControlClass ?? '' }} @error($name) is-invalid @enderror"
            id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" {{ $attributes }} />

        @if ($type === 'password')
            <span style="cursor: pointer" class="toggle-password">
                <i class="las la-eye fs-1 me-2 mt-1"></i>
            </span>
        @endif
    </div>

    @if (!isset($nonmessage))
        @error($name)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    @endif

    <small class="text-muted">
        @isset($optional)
            Kosongkan jika tidak ingin mengubah
        @endisset
    </small>
</div>

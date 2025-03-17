<div id="{{ $id }}" class="card-filter collapse bg-orange-lt mb-3 card px-2" wire:ignore>
    <div class="card-body">
        {{ $slot }}

        <div class="text-end">
            <button wire:click="resetFilters" class="btn btn-sm" type="button">Reset Filter</button>
        </div>
    </div>
</div>

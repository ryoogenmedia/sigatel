<?php

namespace App\Livewire\OnDuty\ViolationType;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\ViolationType;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
    ];

    public $name;
    public $description;
    public $status = true;
    public $violationType;
    public $violationTypeId;

    public function openModal($id){
        $this->violationType    = ViolationType::find($id);
        $this->violationTypeId  = $id;

        $this->name         = $this->violationType->name;
        $this->description  = $this->violationType->description;
        $this->status       = $this->violationType->status == 1 ? true : false;
    }

    public function closeModal(){
        $this->resetForm();
    }

    public function resetForm(){
        $this->reset([
            'name',
            'description',
            'status',
            'violationTypeId',
        ]);
    }

    public function saveViolationType(){
        $this->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'status'        => ['required', 'boolean'],
        ]);

        try{
            DB::beginTransaction();

            if($this->violationType){
                $this->violationType->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]);
            }else{
                ViolationType::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]);
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[jenis pelanggaran] ' .
                    auth()->user()->username .
                    ' gagal menambahkan jenis pelanggaran',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data jenis pelanggaran gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data jenis pelanggaran berhasil ditambah.",
        ]);

        $this->reset();
        return redirect()->back();
    }

    public function changeStatus($id){
        $violation = ViolationType::find($id);
        $violation->status = !$violation->status;
        $violation->save();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "Status jenis pelanggaran berhasil diubah.",
        ]);

        $this->reset();
        return redirect()->back();
    }

    public function deleteSelected()
    {
        $violation = ViolationType::whereIn('id', $this->selected)->get();
        $deleteCount = $violation->count();

        foreach ($violation as $data) {
            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data jenis pelanggaran berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = ViolationType::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->where('name', 'LIKE', "%$search%");
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return ViolationType::all();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function muatUlang()
    {
        $this->dispatch('muat-ulang');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.on-duty.violation-type.index');
    }
}

<?php

namespace App\Livewire\OnDuty\Assignment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\OnDuty;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;
    use WithBulkActions;
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;

    public $filters = [
        'search' => '',
        'status' => '',
        'scheduleTime' => '',
        'finishTime' => '',
        'category' => '',
    ];

    public $modalImageDoc = false;
    public $onDutyId;
    public $photoStudentUrl;
    public $dokumentasiKegiatanUrl;

    public $dokumentasiSiswa;
    public $dokumentasiKegiatan;

    public function showImageDoc($id)
    {
        $onDuty = OnDuty::findOrFail($id);
        $this->onDutyId = $onDuty->id;
        $this->modalImageDoc = !$this->modalImageDoc;
        $this->photoStudentUrl = $onDuty->photoStudentUrl();
        $this->dokumentasiKegiatanUrl = $onDuty->documentationFileUrl();
    }

    public function closeModal()
    {
        $this->resetData();
    }

    public function resetData()
    {
        $this->reset([
            'modalImageDoc',
            'onDutyId',
            'dokumentasiSiswa',
            'dokumentasiKegiatan',
        ]);
    }

    public function resetForm()
    {
        $this->reset([
            'dokumentasiSiswa',
            'dokumentasiKegiatan',
        ]);

        $this->dokumentasiKegiatanUrl = asset('ryoogenmedia/no-image.png');
        $this->photoStudentUrl = asset('ryoogenmedia/no-image.png');
    }

    public function saveUploadImage()
    {
        $this->validate([
            'dokumentasiSiswa' => ['nullable', 'image'],
            'dokumentasiKegiatan' => ['nullable', 'image'],
        ]);

        try {
            DB::beginTransaction();
            $onDuty = OnDuty::findOrFail($this->onDutyId);

            if ($this->dokumentasiSiswa) {
                if ($onDuty->photo_student) {
                    File::delete(public_path('storage/' . $onDuty->photo_student));
                }

                $onDuty->update([
                    'photo_student' => $this->dokumentasiSiswa->store('documentation-student', 'public'),
                ]);
            }

            if ($this->dokumentasiKegiatan) {
                if ($onDuty->documentation_file) {
                    File::delete(public_path('storage/' . $onDuty->documentation_file));
                }

                $onDuty->update([
                    'documentation_file' => $this->dokumentasiKegiatan->store('documentation-activity', 'public'),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[sunting dokumentasi file] ' .
                    auth()->user()->username .
                    ' gagal merubah dokumentasi file gambar',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data file dokumentasi gambar gagal berubah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data file dokumentasi gambar berhasil berubah.",
        ]);

        $this->resetData();
        return redirect()->back();
    }

    public function deleteSelected()
    {
        $onDutys = OnDuty::whereIn('id', $this->selected)->get();
        $deleteCount = $onDutys->count();

        foreach ($onDutys as $data) {
            if ($data->documentation_file) {
                File::delete(public_path('storage/' . $data->documentation_file));
            }

            if ($data->photo_student) {
                File::delete(public_path('storage/' . $data->photo_student));
            }

            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data piket berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[Computed()]
    public function rows()
    {
        $query = OnDuty::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['status'], function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($this->filters['scheduleTime'], function ($query, $scheduleTime) {
                $query->whereDate('schedule_time', '<=', Carbon::parse($scheduleTime)->format('Y-m-d'));
            })
            ->when($this->filters['finishTime'], function ($query, $finishTime) {
                $query->where('finish_time', '>=', Carbon::parse($finishTime)->format('Y-m-d H:i:s'));
            })
            ->when($this->filters['category'], function ($query, $category) {
                $query->where('violation_type', $category);
            })
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereHas('student', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return OnDuty::all();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function render()
    {
        return view('livewire.on-duty.assignment.index');
    }
}

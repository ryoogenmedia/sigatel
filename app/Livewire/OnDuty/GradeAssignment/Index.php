<?php

namespace App\Livewire\OnDuty\GradeAssignment;

use App\Livewire\Traits\DataTable\WithBulkActions;
use App\Livewire\Traits\DataTable\WithCachedRows;
use App\Livewire\Traits\DataTable\WithPerPagePagination;
use App\Livewire\Traits\DataTable\WithSorting;
use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use App\Models\Teacher;
use Illuminate\Support\Facades\File;
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
        'search'         => '',
        'status'         => '',
        'teacher'         => '',
        'grade'          => '',
        'school_subject' => '',
        'time'           => '',
    ];

    #[Computed()]
    public function teachers(){
        return Teacher::orderBy('name')->get(['id', 'name']);
    }

    #[Computed()]
    public function grades(){
        return Grade::orderBy('name')->get(['id', 'name']);
    }

    #[Computed()]
    public function subjects(){
        return SchoolSubject::orderBy('name')->get(['id', 'name']);
    }

    public function deleteSelected()
    {
        $gradeAssignment = GradeAssignment::whereIn('id', $this->selected)->get();
        $deleteCount = $gradeAssignment->count();

        foreach ($gradeAssignment as $data) {
            if ($data->file_assignment) {
                File::delete(public_path('storage/' . $data->file_assignment));
            }

            if($data->documentation_image){
                File::delete(public_path('storage/' . $data->documentation_image));
            }

            $data->delete();
        }

        $this->reset();

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "$deleteCount data penugasan kelas berhasil dihapus.",
        ]);

        return redirect()->back();
    }

    #[On('muat-ulang')]
    #[Computed()]
    public function rows()
    {
        $query = GradeAssignment::query()
            ->when(!$this->sorts, fn($query) => $query->first())
            ->when($this->filters['search'], function ($query, $search) {
                $query->whereHas('grade', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('teacher', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('school_subject', function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%");
                });
            })->when($this->filters['status'], function ($query, $status) {
                if($status == 'sudah'){
                    $query->where('status', true);
                }

                if($status == 'belum'){
                    $query->where('status', false);
                }
            })->when($this->filters['teacher'], function ($query, $teacher) {
                $query->where('teacher_id', $teacher);
            })->when($this->filters['grade'], function ($query, $grade) {
                $query->where('grade_id', $grade);
            })->when($this->filters['school_subject'], function ($query, $school_subject) {
                $query->where('school_subject_id', $school_subject);
            })->when($this->filters['time'], function ($query, $time) {
                switch ($time) {
                    case 'today':
                        $query->whereDate('schedule_time', now());
                        break;
                    case 'yesterday':
                        $query->whereDate('schedule_time', now()->subDay());
                        break;
                    case 'this_week':
                        $query->whereBetween('schedule_time', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'last_week':
                        $query->whereBetween('schedule_time', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                        break;
                    case 'this_month':
                        $query->whereMonth('schedule_time', now()->month);
                        break;
                    case 'last_month':
                        $query->whereMonth('schedule_time', now()->subMonth()->month);
                        break;
                    default:
                        break;
                }
            })->latest();

        return $this->applyPagination($query);
    }

    #[Computed()]
    public function allData()
    {
        return GradeAssignment::all();
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
        return view('livewire.on-duty.grade-assignment.index');
    }
}

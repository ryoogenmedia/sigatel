<?php

namespace App\Livewire\Mobile\GrandAssignment;

use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Title('Tambah Tugas Kelas')]
    #[Layout('layouts.mobile-base')]

    public $gradeId;
    public $teacherId;
    public $gradeName;

    public $mapMarker;
    public $longitude;
    public $latitude;

    public $keteranganTugas;
    public $alasanGuru;
    public $mataPelajaran;
    public $fileTugas;

    public $waktuMulai;
    public $waktuSelesai;

    public function rules(){
        return [
            'longitude'       => ['required'],
            'latitude'        => ['required'],
            'keteranganTugas' => ['required','string'],
            'alasanGuru'      => ['required','string'],
            'mataPelajaran'   => ['required'],
            'fileTugas'       => ['nullable','file'],
            'waktuMulai'      => ['required','string','min:2','max:255'],
            'waktuSelesai'    => ['required','string','min:2','max:255'],
        ];
    }

    public $filters = [
        'search' => '',
    ];

    public function cancelGrade(){
        $this->reset([
            'gradeId',
            'gradeName',
        ]);
    }

    public function resetLocation(){
        $this->reset(['longitude', 'latitude']);
        $this->dispatch('resetLocation');
    }

    #[On('changeLocation')]
    public function changeLocation($longitude, $latitude)
    {
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function chooseGrade($id)
    {
        $grade = Grade::findOrFail($id);
        $this->gradeName = $grade->name;
        $this->gradeId = $grade->id;
    }

    #[Computed()]
    public function subjects(){
        return SchoolSubject::all(['id','name']);
    }

    #[Computed()]
    public function grades(){
        return Grade::query()
            ->when($this->filters['search'], function ($query) {
                $query->where('name', 'like', '%'.$this->filters['search'].'%');
            })
            ->orderBy('name')
            ->latest()
            ->get();
    }

    public function save(){
        $this->validate();

        try{
            DB::beginTransaction();

            $gradeAssignment = GradeAssignment::create([
                'grade_id'          => $this->gradeId,
                'teacher_id'        => $this->teacherId,
                'school_subject_id' => $this->mataPelajaran,
                'longitude'         => $this->longitude,
                'latitude'          => $this->latitude,
                'reason_teacher'    => $this->alasanGuru,
                'description'       => $this->keteranganTugas,
                'schedule_time'     => $this->waktuMulai,
                'finish_time'       => $this->waktuSelesai,
            ]);

            if ($this->fileTugas) {
                $grade = Grade::find($this->gradeId);
                $teacher = auth()->user()->teacher;
                $kelas = $grade ? preg_replace('/[^A-Za-z0-9\-]/', '', $grade->name) : 'kelas';
                $guru = $teacher ? preg_replace('/[^A-Za-z0-9\-]/', '', $teacher->name) : 'guru';
                $timestamp = now()->format('d-m-Y_H-i-s');
                $extension = $this->fileTugas->getClientOriginalExtension();
                $filename = "tugas-{$kelas}-{$guru}-{$timestamp}.{$extension}";

                $path = $this->fileTugas->storeAs('file-tugas', $filename, 'public');

                $gradeAssignment->update([
                    'file_assignment' => $path,
                ]);
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[penugasan kelas oleh guru] ' .
                    auth()->user()->username .
                    ' gagal menambahkan penugasan kelas',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data penugasan kelas gagal ditambah.",
            ]);

            return redirect()->route('mobile.grand-assignment.create');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data penugasan kelas berhasil ditambah.",
        ]);

        return redirect()->route('mobile.grand-assignment.index');
    }

    public function mount(){
        $this->mapMarker = asset('ryoogenmedia/map-marker.png');
        $this->teacherId = auth()->user()->teacher->id;
    }

    public function render()
    {
        return view('livewire.mobile.grand-assignment.create');
    }
}

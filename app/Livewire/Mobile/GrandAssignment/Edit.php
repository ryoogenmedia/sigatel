<?php

namespace App\Livewire\Mobile\GrandAssignment;

use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    #[Title('Sunting Tugas Kelas')]
    #[Layout('layouts.mobile-base')]

    public $gradeId;
    public $teacherId;
    public $gradeAssignmentId;
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

    public function edit(){
        $this->validate();

        try{
            DB::beginTransaction();

            $gradeAssignment = GradeAssignment::findOrFail($this->gradeAssignmentId);

            $gradeAssignment->update([
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

            if($this->fileTugas){
                if($gradeAssignment->file_assignment){
                    File::delete(public_path('storage/' . $gradeAssignment->file_assignment));
                }

                $gradeAssignment->update([
                    'file_assignment' => $this->fileTugas->store('file-tugas', 'public'),
                ]);
            }

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[penugasan kelas oleh guru] ' .
                    auth()->user()->username .
                    ' gagal menyunting penugasan kelas',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data penugasan kelas gagal disunting.",
            ]);

            return redirect()->route('mobile.grand-assignment.edit', $this->gradeAssignmentId);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data penugasan kelas berhasil disunting.",
        ]);

        return redirect()->route('mobile.grand-assignment.index');
    }

    public function mount($id){
        $this->mapMarker = asset('ryoogenmedia/map-marker.png');
        $this->teacherId = auth()->user()->teacher->id;
        $gradeAssignment = GradeAssignment::findOrFail($id);

        $this->gradeAssignmentId = $gradeAssignment->id;
        $this->mataPelajaran     = $gradeAssignment->school_subject->id;
        $this->gradeId           = $gradeAssignment->grade->id;

        $this->longitude         = $gradeAssignment->longitude;
        $this->latitude          = $gradeAssignment->latitude;
        $this->alasanGuru        = $gradeAssignment->reason_teacher;
        $this->keteranganTugas   = $gradeAssignment->description;
        $this->waktuMulai        = $gradeAssignment->schedule_time;
        $this->waktuSelesai      = $gradeAssignment->finish_time;

    }

    public function render()
    {
        return view('livewire.mobile.grand-assignment.edit');
    }
}

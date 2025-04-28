<?php

namespace App\Livewire\OnDuty\GradeAssignment;

use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $kelas;
    public $guru;
    public $mataPelajaran;
    public $alasanGuru;
    public $tanggalMulai;
    public $tanggalSelesai;
    public $fileTugas;
    public $keteranganTugas;

    public $gradeAssignmentId;

    #[Computed()]
    public function teachers()
    {
        return Teacher::orderBy('name')->get(['id', 'name']);
    }

    #[Computed()]
    public function grades()
    {
        return Grade::orderBy('name')->get(['id', 'name']);
    }

    #[Computed()]
    public function subjects()
    {
        return SchoolSubject::orderBy('name')->get(['id', 'name']);
    }

    public function rules(){
        return [
            'kelas' => ['required'],
            'guru' => ['required'],
            'mataPelajaran' => ['required'],
            'alasanGuru' => ['required'],
            'tanggalMulai' => ['required', 'date'],
            'tanggalSelesai' => ['required', 'date'],
            'fileTugas' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png'],
            'keteranganTugas' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $this->validate();

        try{
            DB::beginTransaction();

            $gradeAssignment = GradeAssignment::findOrFail($this->gradeAssignmentId);

            $gradeAssignment->update([
                'grade_id' => $this->kelas,
                'teacher_id' => $this->guru,
                'school_subject_id' => $this->mataPelajaran,
                'reason_teacher' => $this->alasanGuru,
                'schedule_time' => $this->tanggalMulai,
                'finish_time' => $this->tanggalSelesai,
                'description' => $this->keteranganTugas,
            ]);

            if ($this->fileTugas) {
                if($gradeAssignment->file_assignment) {
                    File::delete(public_path('storage/' . $gradeAssignment->file_assignment));
                }

                $gradeAssignment->update([
                    'file_assignment' => $this->fileTugas->store('dokumentasi-tugas-kelas','public'),
                ]);
            }

            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[penugasan kelas] ' .
                    auth()->user()->username .
                    ' gagal menyunting penugasan kelas',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data penugasan kelas gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data penugasan kelas berhasil disunting.",
        ]);

        return redirect()->route('on-duty.grade-assignment.index');
    }

    public function mount($id){
        $gradeAssignment = GradeAssignment::findOrFail($id);

        $this->kelas = $gradeAssignment->grade_id;
        $this->guru = $gradeAssignment->teacher_id;
        $this->mataPelajaran = $gradeAssignment->school_subject_id;
        $this->alasanGuru = $gradeAssignment->reason_teacher;
        $this->tanggalMulai = $gradeAssignment->schedule_time;
        $this->tanggalSelesai = $gradeAssignment->finish_time;
        $this->keteranganTugas = $gradeAssignment->description;

        $this->gradeAssignmentId = $id;
        $this->dispatch('changeKeteranganTugas', $this->keteranganTugas);
    }

    public function render()
    {
        return view('livewire.on-duty.grade-assignment.edit');
    }
}

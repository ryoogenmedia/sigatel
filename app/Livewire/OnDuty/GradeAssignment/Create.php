<?php

namespace App\Livewire\OnDuty\GradeAssignment;

use App\Models\Grade;
use App\Models\GradeAssignment;
use App\Models\SchoolSubject;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
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

            $gradeAssignment = GradeAssignment::create([
                'grade_id' => $this->kelas,
                'teacher_id' => $this->guru,
                'school_subject_id' => $this->mataPelajaran,
                'reason_teacher' => $this->alasanGuru,
                'schedule_time' => $this->tanggalMulai,
                'finish_time' => $this->tanggalSelesai,
                'description' => $this->keteranganTugas,
            ]);

            if ($this->fileTugas) {
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
                    ' gagal menambahkan penugasan kelas',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data penugasan kelas gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data penugasan kelas berhasil ditambah.",
        ]);

        return redirect()->route('on-duty.grade-assignment.index');
    }

    public function render()
    {
        return view('livewire.on-duty.grade-assignment.create');
    }
}

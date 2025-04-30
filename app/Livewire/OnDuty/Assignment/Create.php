<?php

namespace App\Livewire\OnDuty\Assignment;

use App\Models\OnDuty;
use App\Models\SchoolSubject;
use App\Models\Student;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $siswa;
    public $mataPelajaran;
    public $guru;
    public $deskripsi;
    public $jadwalPelaksanaan;
    public $jadwalSelesai;
    public $jenisPelanggaran;
    public $dokumentasiKegiatan;
    public $dokumentasiSiswa;

    public $dokumentasiKegiatanUrl;
    public $photoStudentUrl;

    #[Computed()]
    public function students()
    {
        return Student::all(['id', 'name']);
    }

    #[Computed()]
    public function school_subjects()
    {
        return SchoolSubject::all(['id', 'name', 'code']);
    }

    #[Computed()]
    public function teachers()
    {
        return Teacher::all(['id', 'name']);
    }

    public function updatedMataPelajaran()
    {
        if ($this->mataPelajaran) {
            $schoolSubject  = SchoolSubject::findOrFail($this->mataPelajaran);
            $this->guru     = $schoolSubject->teacher_id;
        }
    }

    public function save()
    {
        $this->validate([
            'siswa'                 => ['required'],
            'mataPelajaran'         => ['required'],
            'guru'                  => ['required'],
            'deskripsi'             => ['required', 'string'],
            'jadwalPelaksanaan'     => ['required', 'string'],
            'jadwalSelesai'         => ['required', 'string'],
            'jenisPelanggaran'      => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.violation_type'))],
            'dokumentasiKegiatan'   => ['nullable', 'image'],
            'dokumentasiSiswa'      => ['nullable', 'image'],
        ]);

        try {
            DB::beginTransaction();
            $onDuty = OnDuty::create([
                'student_id'        => $this->siswa,
                'teacher_id'        => $this->guru,
                'school_subject_id' => $this->mataPelajaran,
                'description'       => $this->deskripsi,
                'violation_type'    => $this->jenisPelanggaran,
                'schedule_time'     => $this->jadwalPelaksanaan,
                'finish_time'       => $this->jadwalSelesai,
            ]);

            if ($this->dokumentasiSiswa) {
                $onDuty->update([
                    'photo_student' => $this->dokumentasiSiswa->store('documentation-student', 'public'),
                ]);
            }

            if ($this->dokumentasiKegiatan) {
                $onDuty->update([
                    'documentation_file' => $this->dokumentasiKegiatan->store('documentation-activity', 'public'),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[penugasan piket] ' .
                    auth()->user()->username .
                    ' gagal menambahkan penugasan piket',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data penugasan piket gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data penugasan piket berhasil ditambah.",
        ]);

        return redirect()->route('on-duty.assignment.index');
    }

    public function mount()
    {
        $this->dokumentasiKegiatanUrl = asset('ryoogenmedia/no-image.png');
        $this->photoStudentUrl        = asset('ryoogenmedia/no-image.png');
    }

    public function render()
    {
        return view('livewire.on-duty.assignment.create');
    }
}

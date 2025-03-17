<?php

namespace App\Livewire\SchoolSubject;

use App\Models\SchoolSubject;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Edit extends Component
{
    public $namaMataPelajaran;
    public $status;
    public $kode;
    public $pendidik;

    public $schoolSubjectId;

    #[Computed()]
    public function teachers()
    {
        return Teacher::all(['id', 'name']);
    }

    public function validateData()
    {
        $this->validate([
            'namaMataPelajaran' => ['required', 'string', 'min:2', 'max:255'],
            'kode' => ['required', 'string', 'min:2', 'max:255'],
            'status' => ['required'],
            'pendidik' => ['required'],
        ]);
    }

    public function edit()
    {
        $this->validateData();

        try {
            DB::beginTransaction();

            $schoolSubject = SchoolSubject::findOrFail($this->schoolSubjectId);

            $schoolSubject->update([
                'teacher_id' => $this->pendidik,
                'name' => $this->namaMataPelajaran,
                'code' => $this->kode,
                'status' => $this->status,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[mata pelajaran] ' .
                    auth()->user()->username .
                    ' gagal menyunting mata pelajaran',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data mata pelajaran gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data mata pelajaran berhasil disunting.",
        ]);

        return redirect()->route('school-subject.index');
    }

    public function mount($id)
    {
        $schoolSubject = SchoolSubject::findOrFail($id);

        $this->schoolSubjectId = $schoolSubject->id;
        $this->namaMataPelajaran = $schoolSubject->name;
        $this->kode = $schoolSubject->code;
        $this->status = (bool) $schoolSubject->status;
        $this->pendidik = $schoolSubject->teacher_id;
    }

    public function render()
    {
        return view('livewire.school-subject.edit');
    }
}

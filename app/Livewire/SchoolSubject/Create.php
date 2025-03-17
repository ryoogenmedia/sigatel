<?php

namespace App\Livewire\SchoolSubject;

use App\Models\SchoolSubject;
use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Create extends Component
{
    public $namaMataPelajaran;
    public $status;
    public $kode;
    public $pendidik;

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

    public function save()
    {
        $this->validateData();

        try {
            DB::beginTransaction();

            SchoolSubject::create([
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
                    ' gagal menambahkan mata pelajaran',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data mata pelajaran gagal ditambah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data mata pelajaran berhasil ditambah.",
        ]);

        return redirect()->route('school-subject.index');
    }

    public function render()
    {
        return view('livewire.school-subject.create');
    }
}

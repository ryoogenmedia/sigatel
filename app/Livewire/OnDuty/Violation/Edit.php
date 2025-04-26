<?php

namespace App\Livewire\OnDuty\Violation;

use App\Models\Teacher;
use App\Models\Violation;
use App\Models\ViolationType;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Edit extends Component
{
    public $siswa;
    public $guru;
    public $jenisPelanggaran;
    public $deskripsi;

    public $violationId;

    #[Computed()]
    public function teachers(){
        return Teacher::query()
            ->where('status', 'aktif')
            ->where('duty_status',true)
            ->get(['name', 'id']);
    }

    #[Computed()]
    public function violationTypes(){
        return ViolationType::query()
            ->where('status', true)
            ->get(['name', 'id']);
    }

    public function edit(){
        $this->validate([
            'siswa'            => ['required', 'exists:students,id'],
            'guru'             => ['required', 'exists:teachers,id'],
            'jenisPelanggaran' => ['required', 'exists:violation_types,name'],
            'deskripsi'        => ['required', 'string'],
        ]);

        try {
            DB::beginTransaction();

            $violation = Violation::findOrFail($this->violationId);

            $violation->update([
                'student_id'     => $this->siswa,
                'teacher_id'     => $this->guru,
                'violation_type' => $this->jenisPelanggaran,
                'description'    => $this->deskripsi,
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            logger()->error(
                '[sunting pelanggaran] ' .
                auth()->user()->username .
                ' gagal menyunting pelanggaran siswa. ' .
                $e->getMessage()
            );

            session()->flash('alert', [
                'type' => 'error',
                'message' => 'Gagal.',
                'detail' => "data pelanggaran siswa gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data pelanggaran siswa berhasil disunting.",
        ]);

        return redirect()->route('on-duty.student-violation.index');
    }

    public function mount($id){
        $violation              = Violation::findOrFail($id);

        $this->violationId      = $id;
        $this->siswa            = $violation->student_id;
        $this->guru             = $violation->teacher_id;
        $this->jenisPelanggaran = $violation->violation_type;
        $this->deskripsi        = $violation->description;
    }

    public function render()
    {
        return view('livewire.on-duty.violation.edit');
    }
}

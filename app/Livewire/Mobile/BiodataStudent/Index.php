<?php

namespace App\Livewire\Mobile\BiodataStudent;

use App\Models\Student;
use App\Models\StudentParent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Biodata Anak Anda')]
    #[Layout('layouts.mobile-base')]

    public $nama;
    public $nomorPonsel;
    public $jenisKelamin;
    public $alamat;
    public $nis;

    public $user;
    public $parent;
    public $student;

    public function rules(){
        return [
            'nama'              => ['required','string','min:2','max:255'],
            'nomorPonsel'       => ['required','string','min:2','max:255'],
            'jenisKelamin'      => ['required','string','min:2','max:255', Rule::in(config('const.sex'))],
            'alamat'            => ['required','string','min:2'],
            'nis'               => ['required','string','min:2','max:17'],
        ];
    }

    public function edit(){
        $this->validate();

        try{
            DB::beginTransaction();

            $this->student->update([
                'user_id'       => $this->user->id,
                'name'          => $this->nama,
                'phone_number'  => $this->nomorPonsel,
                'sex'           => $this->jenisKelamin,
                'address'       => $this->alamat,
                'nis'           => $this->nis,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[bio data anak] ' .
                    auth()->user()->username .
                    ' gagal menyunting biodata anak.',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data bio anak gagal disunting.",
            ]);

            return redirect()->route('mobile.biodata-student.index');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data bio anak berhasil disunting.",
        ]);

        return redirect()->route('mobile.biodata-student.index');
    }

    public function mount(){
        $this->user         = auth()->user();
        $this->parent       = StudentParent::findOrFail($this->user->parent->id);
        $this->student      = Student::findOrFail($this->parent->student->id);
        $this->nama         = $this->student->name;
        $this->jenisKelamin = $this->student->sex;
        $this->alamat       = $this->student->address;
        $this->nomorPonsel  = $this->student->phone_number;
        $this->nis          = $this->student->nis;
    }

    public function render()
    {
        return view('livewire.mobile.biodata-student.index');
    }
}

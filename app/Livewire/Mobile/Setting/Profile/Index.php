<?php

namespace App\Livewire\Mobile\Setting\Profile;

use App\Models\Teacher;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Pengaturan Akun')]
    #[Layout('layouts.mobile-base')]

    public $userId;
    public $teacherId;

    public $nama;
    public $nomorPonsel;
    public $alamat;
    public $jenisKelamin;

    public function rules()
    {
        return [
            'nama' => ['required','string','min:2','max:255'],
            'nomorPonsel' => ['required','string','min:2','max:255'],
            'alamat' => ['required','string','min:2','max:255'],
            'jenisKelamin' => ['required','string','max:255',Rule::in(config('const.sex'))],
        ];
    }

    public function edit(){
        $this->validate();

        try{
            DB::beginTransaction();

            $teacher = Teacher::findOrFail($this->teacherId);

            $teacher->update([
                'name'          => $this->nama,
                'phone_number'  => $this->nomorPonsel,
                'address'       => $this->alamat,
                'sex'           => $this->jenisKelamin,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[mobile profile] ' .
                    auth()->user()->username .
                    ' gagal menyunting profile',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data profil gagal disunting.",
            ]);

            return redirect()->route('mobile.setting.profile.index');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data profil berhasil disunting.",
        ]);

        return redirect()->route('mobile.setting.profile.index');
    }

    public function mount()
    {
        $user = auth()->user();
        $this->userId = $user->id;

        $teacher = Teacher::where('user_id', $this->userId)->firstOrFail();
        $this->teacherId = $teacher->id;

        $this->nama = $teacher->name;
        $this->nomorPonsel = $teacher->phone_number;
        $this->alamat = $teacher->address;
        $this->jenisKelamin = $teacher->sex;
    }

    public function render()
    {
        return view('livewire.mobile.setting.profile.index');
    }
}

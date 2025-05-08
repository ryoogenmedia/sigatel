<?php

namespace App\Livewire\Mobile\Setting\Profile;

use App\Models\StudentParent;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ParentProfile extends Component
{
    public $nama;
    public $nomorPonsel;
    public $statusOrangTua;

    public $parentId;
    public $userId;
    public $studentId;

    public function rules(){
        return [
            'nama' => ['required','string','min:2','max:255'],
            'nomorPonsel' => ['required','string','min:2','max:255'],
            'statusOrangTua' => ['required','string','min:2','max:255', Rule::in(config('const.guardian_status'))],
        ];
    }

    public function edit(){
        $this->validate();

        try{
            DB::beginTransaction();

            $parent = StudentParent::findOrFail($this->parentId);

            $parent->update([
                'user_id'           => $this->userId,
                'student_id'        => $this->studentId,
                'name'              => $this->nama,
                'phone_number'      => $this->nomorPonsel,
                'guardian_status'   => $this->statusOrangTua,
            ]);

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();

            logger()->error(
                '[profile orang tua siswa] ' .
                    auth()->user()->username .
                    ' gagal menyunting profile orang tua siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data profile orang tua siswa gagal disunting.",
            ]);

            return redirect()->route('mobile.setting.profile.index');
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data profile orang tua siswa berhasil disunting.",
        ]);

        return redirect()->route('mobile.setting.profile.index');
    }

    public function mount(){
        $user                   = auth()->user();
        $parent                 = StudentParent::where('user_id',$user->id)->firstOrFail();
        $this->userId           = $user->id;
        $this->parentId         = $parent->id;
        $this->studentId        = $parent->student->id;
        $this->nama             = $parent->name;
        $this->nomorPonsel      = $parent->phone_number;
        $this->statusOrangTua   = $parent->guardian_status;
    }

    public function render()
    {
        return view('livewire.mobile.setting.profile.parent-profile');
    }
}

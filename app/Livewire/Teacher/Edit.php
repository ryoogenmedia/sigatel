<?php

namespace App\Livewire\Teacher;

use App\Models\Teacher;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $namaGuru;
    public $nomorPonsel;
    public $jenisKelamin;
    public $status;
    public $alamat;

    public $email;
    public $kataSandi;
    public $avatar;
    public $konfirmasiKataSandi;
    public $roles = 'teacher';

    public $userId;
    public $teacherId;
    public $avatarUrl;

    public function rules()
    {
        return [
            'namaGuru' => ['required', 'string', 'min:2', 'max:255'],
            'nomorPonsel' => ['required', 'string', 'min:2', 'max:255'],
            'jenisKelamin' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.sex'))],
            'status' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.teacher_status'))],
            'alamat' => ['required', 'string'],
            'email' => ['required', 'string'],
            'kataSandi' => ['nullable', 'same:konfirmasiPassword', 'min:2', 'max:255', Password::default()],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function edit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($this->userId);
            $teacher = Teacher::findOrFail($this->teacherId);

            $user->update([
                'username' => $this->namaGuru,
                'roles' => $this->roles,
                'email' => $this->email,
                'password' => bcrypt($this->kataSandi),
                'email_verified_at' => now(),
            ]);

            if ($this->avatar) {
                $user->update([
                    'avatar' => $this->avatar->store('avatars', 'public'),
                ]);
            }

            $teacher->update([
                'user_id' => $user->id,
                'name' => $this->namaGuru,
                'phone_number' => $this->nomorPonsel,
                'address' => $this->alamat,
                'sex' => $this->jenisKelamin,
                'status' => $this->status,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[sunting guru] ' .
                    auth()->user()->username .
                    ' gagal menyunting guru',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data guru gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data guru berhasil disunting.",
        ]);

        return redirect()->route('teacher.index');
    }

    public function mount($id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user->id);

        $this->teacherId = $teacher->id;
        $this->namaGuru = $teacher->name;
        $this->nomorPonsel = $teacher->phone_number;
        $this->alamat = $teacher->address;
        $this->jenisKelamin = $teacher->sex;
        $this->status = $teacher->status;

        $this->userId = $user->id;
        $this->email = $user->email;
        $this->avatarUrl = $user->avatarUrl();
    }

    public function render()
    {
        return view('livewire.teacher.edit');
    }
}

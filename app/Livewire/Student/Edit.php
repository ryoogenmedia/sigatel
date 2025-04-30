<?php

namespace App\Livewire\Student;

use App\Models\Student;
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

    public $namaSiswa;
    public $nomorPonsel;
    public $jenisKelamin;
    public $status;
    public $alamat;

    public $email;
    public $kataSandi;
    public $avatar;
    public $nis;
    public $konfirmasiKataSandi;
    public $roles = 'student';

    public $userId;
    public $studentId;
    public $kelas;
    public $avatarUrl;

    public function rules()
    {
        return [
            'namaSiswa'     => ['required', 'string', 'min:2', 'max:255'],
            'nomorPonsel'   => ['required', 'string', 'min:2', 'max:255'],
            'jenisKelamin'  => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.sex'))],
            'status'        => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.teacher_status'))],
            'alamat'        => ['required', 'string'],
            'nis'           => ['required', 'string', 'min:2', 'max:8'],
            'email'         => ['required', 'string'],
            'kataSandi'     => ['nullable', 'same:konfirmasiPassword', 'min:2', 'max:255', Password::default()],
            'avatar'        => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function edit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($this->userId);
            $student = Student::findOrFail($this->studentId);

            $user->update([
                'username'          => $this->namaSiswa,
                'roles'             => $this->roles,
                'email'             => $this->email,
                'password'          => bcrypt($this->kataSandi),
                'email_verified_at' => now(),
            ]);

            if ($this->avatar) {
                $user->update([
                    'avatar' => $this->avatar->store('avatars', 'public'),
                ]);
            }

            $student->update([
                'user_id'       => $user->id,
                'grade_id'      => $this->kelas,
                'name'          => $this->namaSiswa,
                'nis'           => $this->nis,
                'phone_number'  => $this->nomorPonsel,
                'address'       => $this->alamat,
                'sex'           => $this->jenisKelamin,
                'status'        => $this->status,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[sunting siswa] ' .
                    auth()->user()->username .
                    ' gagal menyunting siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data siswa gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data siswa berhasil disunting.",
        ]);

        return redirect()->route('student.index');
    }

    public function mount($id)
    {
        $student = Student::findOrFail($id);
        $user = User::findOrFail($student->user->id);

        $this->studentId    = $student->id;
        $this->namaSiswa    = $student->name;
        $this->nomorPonsel  = $student->phone_number;
        $this->alamat       = $student->address;
        $this->nis          = $student->nis;
        $this->jenisKelamin = $student->sex;
        $this->status       = $student->status;
        $this->kelas        = $student->grade_id;

        $this->userId       = $user->id;
        $this->email        = $user->email;
        $this->avatarUrl    = $user->avatarUrl();
    }

    public function render()
    {
        return view('livewire.student.edit');
    }
}

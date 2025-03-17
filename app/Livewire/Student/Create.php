<?php

namespace App\Livewire\Student;

use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $namaSiswa;
    public $nomorPonsel;
    public $jenisKelamin;
    public $status;
    public $alamat;

    public $caraBuatAkun;

    public $email;
    public $kataSandi;
    public $avatar;
    public $roles = 'teacher';
    public $konfirmasiKataSandi;

    public $user;
    public $pengguna;
    public $kelas;

    #[Computed()]
    public function grades()
    {
        return Grade::all(['id', 'name']);
    }

    #[Computed()]
    public function users()
    {
        return User::where('roles', 'teacher')
            ->whereDoesntHave('student')
            ->get(['id', 'username', 'email']);
    }

    public function rules()
    {
        $rules = [
            'namaSiswa' => ['required', 'string', 'min:2', 'max:255'],
            'nomorPonsel' => ['required', 'string', 'min:2', 'max:255'],
            'jenisKelamin' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.sex'))],
            'status' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.teacher_status'))],
            'alamat' => ['required', 'string'],
        ];

        if ($this->caraBuatAkun == 'buat akun') {
            $rules['email'] = ['required', 'string', 'min:2', 'max:255', 'email'];
            $rules['kataSandi'] = ['required', 'string', 'same:konfirmasiKataSandi', Password::default()];
            $avatar['avatar'] = ['nullable', 'image', 'max:2048'];
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            if ($this->caraBuatAkun == 'buat akun') {
                $this->user = User::create([
                    'username' => $this->namaSiswa,
                    'roles' => $this->roles,
                    'email' => $this->email,
                    'password' => bcrypt($this->kataSandi),
                    'email_verified_at' => now(),
                ]);

                if ($this->avatar) {
                    $this->user->update([
                        'avatar' => $this->avatar->store('avatars', 'public'),
                    ]);
                }
            }

            if ($this->caraBuatAkun == 'pilih akun') {
                $this->user = User::findOrFail($this->pengguna);
            }

            Student::create([
                'user_id' => $this->user->id,
                'grade_id' => $this->kelas,
                'name' => $this->namaSiswa,
                'phone_number' => $this->nomorPonsel,
                'address' => $this->alamat,
                'sex' => $this->jenisKelamin,
                'status' => $this->status,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[tambah siswa] ' .
                    auth()->user()->username .
                    ' gagal menambahkan siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data siswa gagal ditambahkan.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data siswa berhasil ditambah.",
        ]);

        return redirect()->route('student.index');
    }

    public function render()
    {
        return view('livewire.student.create');
    }
}

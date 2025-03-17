<?php

namespace App\Livewire\StudentParent;

use App\Models\Student;
use App\Models\StudentParent;
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

    public $namaOrangTuaSiswa;
    public $nomorPonsel;
    public $statusHubungan;

    public $caraBuatAkun;

    public $email;
    public $kataSandi;
    public $avatar;
    public $roles = 'parent';
    public $konfirmasiKataSandi;

    public $user;
    public $pengguna;
    public $siswa;

    #[Computed()]
    public function students()
    {
        return Student::query()
            ->whereDoesntHave('parent')->get(['id', 'name']);
    }

    #[Computed()]
    public function users()
    {
        return User::where('roles', 'teacher')
            ->whereDoesntHave('parent')
            ->get(['id', 'username', 'email']);
    }

    public function rules()
    {
        $rules = [
            'namaOrangTuaSiswa' => ['required', 'string', 'min:2', 'max:255'],
            'nomorPonsel' => ['required', 'string', 'min:2', 'max:255'],
            'statusHubungan' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.guardian_status'))],
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
                    'username' => $this->namaOrangTuaSiswa,
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

            StudentParent::create([
                'user_id' => $this->user->id,
                'student_id' => $this->siswa,
                'name' => $this->namaOrangTuaSiswa,
                'phone_number' => $this->nomorPonsel,
                'guardian_status' => $this->statusHubungan,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[tambah orang tua siswa] ' .
                    auth()->user()->username .
                    ' gagal menambahkan orang tua siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data orang tua siswa gagal ditambahkan.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data orang tua siswa berhasil ditambah.",
        ]);

        return redirect()->route('guardian-parent.index');
    }

    public function render()
    {
        return view('livewire.student-parent.create');
    }
}

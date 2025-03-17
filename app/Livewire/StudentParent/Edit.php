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
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $namaOrangTuaSiswa;
    public $nomorPonsel;
    public $statusHubungan;

    public $email;
    public $kataSandi;
    public $avatar;
    public $konfirmasiKataSandi;
    public $roles = 'parent';

    public $userId;
    public $studentParentId;
    public $siswa;
    public $avatarUrl;

    #[Computed()]
    public function students()
    {
        return Student::query()
            ->whereDoesntHave('parent')
            ->orWhere('id', $this->siswa)
            ->get(['id', 'name']);
    }

    public function rules()
    {
        return [
            'namaOrangTuaSiswa' => ['required', 'string', 'min:2', 'max:255'],
            'nomorPonsel' => ['required', 'string', 'min:2', 'max:255'],
            'statusHubungan' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.guardian_status'))],
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
            $studentParent = StudentParent::findOrFail($this->studentParentId);

            $user->update([
                'username' => $this->namaOrangTuaSiswa,
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

            $studentParent->update([
                'user_id' => $user->id,
                'student_id' => $this->siswa,
                'name' => $this->namaOrangTuaSiswa,
                'phone_number' => $this->nomorPonsel,
                'guardian_status' => $this->statusHubungan,
            ]);

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[sunting orang tua siswa] ' .
                    auth()->user()->username .
                    ' gagal menyunting orang tua siswa',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data orang tua siswa gagal disunting.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data orang tua siswa berhasil disunting.",
        ]);

        return redirect()->route('guardian-parent.index');
    }

    public function mount($id)
    {
        $studentParent = StudentParent::findOrFail($id);
        $user = User::findOrFail($studentParent->user->id);

        $this->studentParentId = $studentParent->id;
        $this->namaOrangTuaSiswa = $studentParent->name;
        $this->nomorPonsel = $studentParent->phone_number;
        $this->siswa = $studentParent->student->id;
        $this->statusHubungan = $studentParent->guardian_status;

        $this->userId = $user->id;
        $this->email = $user->email;
        $this->avatarUrl = $user->avatarUrl();
    }

    public function render()
    {
        return view('livewire.student-parent.edit');
    }
}

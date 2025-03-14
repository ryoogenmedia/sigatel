<?php

namespace App\Livewire\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $username;
    public $email;
    public $kataSandi;
    public $avatar;
    public $roles;
    public $konfirmasiKataSandi;

    public function validateData()
    {
        $this->validate([
            'username' => ['required', 'string', 'min:2', 'max:255'],
            'roles' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.roles'))],
            'email' => ['required', 'string', 'min:2', 'unique:users,email'],
            'kataSandi' => ['required', 'string', 'same:konfirmasiKataSandi', Password::default()],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);
    }

    public function save()
    {
        $this->validateData();

        try {
            DB::beginTransaction();

            $user = User::create([
                'username' => $this->username,
                'email' => strtolower($this->email),
                'password' => bcrypt($this->kataSandi),
                'roles' => $this->roles,
                'email_verified_at' => now(),
            ]);

            if ($this->avatar) {
                $user->update([
                    'avatar' => $this->avatar->store('avatars', 'public'),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data user gagal ditambah.",
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data user berhasil ditambah.",
        ]);

        return redirect()->route('user.index');
    }

    public function render()
    {
        return view('livewire.user.create');
    }
}

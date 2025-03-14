<?php

namespace App\Livewire\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $username;
    public $email;
    public $password;
    public $avatar;
    public $roles;

    public $userId;
    public $avatarUrl;

    public function rules()
    {
        return [
            'username' => ['required', 'string', 'min:2', 'max:255'],
            'roles' => ['required', 'string', 'min:2', 'max:255', Rule::in(config('const.roles'))],
            'email' => ['nullable', 'string', 'min:2', 'unique:users,email,' . $this->userId],
            'password' => ['nullable', 'string', Password::default()],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function edit()
    {
        $this->validate();

        $user = User::whereId($this->userId)->first();

        try {
            DB::beginTransaction();

            $user->update([
                'username' => $this->username,
                'roles' => $this->roles,
                'email_verified_at' => now(),
            ]);

            if ($this->email) {
                $user->update([
                    'email' => strtolower($this->email),
                ]);
            }

            if ($this->password) {
                $user->update(['password' => bcrypt($this->password)]);
            }

            if ($this->avatar) {
                if ($user->avatar) {
                    File::delete(public_path('storage/' . $user->avatar));
                }

                $user->update(['avatar' => $this->avatar->store('avatars', 'public')]);
            }

            DB::commit();
        } catch (Exception $e) {
            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data user gagal disunting.",
            ]);
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data user berhasil disunting.",
        ]);

        return redirect()->route('user.index');
    }

    public function mount($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->roles = $user->roles;
        $this->avatarUrl = $user->avatarUrl();
    }

    public function render()
    {
        return view('livewire.user.edit');
    }
}

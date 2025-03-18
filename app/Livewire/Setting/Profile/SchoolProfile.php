<?php

namespace App\Livewire\Setting\Profile;

use App\Models\School;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class SchoolProfile extends Component
{
    use WithFileUploads;

    public $logoSekolah;
    public $namaSekolah;
    public $emailSekolah;
    public $nomorTelepon;
    public $nomorPonsel;
    public $alamatSekolah;
    public $kodePos;
    public $akreditasSekolah;

    public $logoUrl;
    public $schoolId;

    public function edit()
    {
        $this->validate([
            'logoSekolah' => ['nullable', 'image'],
            'namaSekolah' => ['nullable', 'string', 'min:2', 'max:255'],
            'emailSekolah' => ['nullable', 'string', 'email'],
            'nomorTelepon' => ['nullable', 'string', 'min:2', 'max:16'],
            'nomorPonsel' => ['nullable', 'string', 'min:2', 'max:13'],
            'alamatSekolah' => ['nullable', 'string'],
            'kodePos' => ['nullable', 'string', 'min:2', 'max:7'],
            'akreditasSekolah' => ['nullable', 'string', 'min:1', 'max:3'],
        ]);

        try {
            DB::beginTransaction();

            if ($this->schoolId) {
                $school = School::findOrFail($this->schoolId);

                $school->update([
                    'name_school' => $this->namaSekolah,
                    'address' => $this->alamatSekolah,
                    'email' => $this->emailSekolah,
                    'telp' => $this->nomorTelepon,
                    'phone' => $this->nomorPonsel,
                    'postal_code' => $this->kodePos,
                    'accreditation' => $this->akreditasSekolah,
                ]);
            } else {
                $school = School::create([
                    'name_school' => $this->namaSekolah,
                    'address' => $this->alamatSekolah,
                    'email' => $this->emailSekolah,
                    'telp' => $this->nomorTelepon,
                    'phone' => $this->nomorPonsel,
                    'postal_code' => $this->kodePos,
                    'accreditation' => $this->akreditasSekolah,
                ]);
            }

            if ($this->logoSekolah) {
                if ($school->logo) {
                    File::delete(public_path('storage/' . $school->logo));
                }
                $school->update([
                    'logo' => $this->logoSekolah->store('logo-school', 'public'),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            logger()->error(
                '[profil sekolah] ' .
                    auth()->user()->username .
                    ' gagal merubah profil sekolah',
                [$e->getMessage()]
            );

            session()->flash('alert', [
                'type' => 'danger',
                'message' => 'Gagal.',
                'detail' => "data profil sekolah gagal dirubah.",
            ]);

            return redirect()->back();
        }

        session()->flash('alert', [
            'type' => 'success',
            'message' => 'Berhasil.',
            'detail' => "data profil sekolah berhasil dirubah.",
        ]);

        $this->refreshData();
        return redirect()->back();
    }

    public function refreshData()
    {
        $school = School::first();
        $this->schoolId = $school->id ?? null;
        $this->namaSekolah = $school->name_school ?? null;
        $this->emailSekolah = $school->email ?? null;
        $this->nomorPonsel = $school->phone ?? null;
        $this->nomorTelepon = $school->telp ?? null;
        $this->alamatSekolah = $school->address ?? null;
        $this->kodePos = $school->postal_code ?? null;
        $this->akreditasSekolah = $school->accreditation ?? null;

        if ($school) {
            $this->logoUrl = $school->logoUrl();
        } else {
            $this->logoUrl = asset('ryoogenmedia/no-image.png');
        }
    }

    public function mount()
    {
        $this->refreshData();
    }

    public function render()
    {
        return view('livewire.setting.profile.school-profile');
    }
}

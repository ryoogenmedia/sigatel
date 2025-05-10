<x-layouts.mobile-auth>
    @section('title', 'Login')

    <form class="app-form rounded-control" action="{{ route('login') }}" method="POST">

        @csrf

        <div class="mb-3 text-center">
            <h3 class="text-primary-dark">Masuk Ke Akun Anda</h3>
            <p class="f-s-12 text-secondary">Masukkan data akun anda, untuk masuk ke aplikasi.</p>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat Surel (Email)</label>
            <input class="form-control" type="email" name="email" placeholder="example@gmail.com"
                value="{{ old('email') }}" required autofocus>
            <div class="form-text text">Kami tidak akan pernah membagikan email Anda kepada orang lain.
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Kata Sandi</label>
            <input class="form-control" type="password" name="password" placeholder="********" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Ingat Saya</label>
        </div>

        <div>
            <button class="btn btn-light-primary w-100" type="submit">Submit</button>
        </div>
    </form>

</x-layouts.mobile-auth>

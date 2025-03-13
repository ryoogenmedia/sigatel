<x-layouts.auth title="Login">
    <div class="card card-md">
        <div class="card-body">
            <div class="text-center">
                <h2 class="h4 text-center mb-5 title-login">Masuk Ke Aplikasi</h2>
            </div>

            <form class="mt-5" action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Alamat Surel (email)</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                        placeholder="contoh@email.com" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">
                        <span>Kata Sandi</span>

                        <span>
                            @if (Route::has('password.request'))
                                <span class="form-label-description">
                                    <a href="{{ route('password.request') }}">
                                        Lupa kata sandi?
                                    </a>
                                </span>
                            @endif
                        </span>
                    </label>

                    <input class="form-control" type="password" name="password" placeholder="************" required>
                </div>

                <div class="mb-2">
                    <label class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" />
                        <span class="form-check-label">Ingat akun saya</span>
                    </label>
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-warning w-100">Masuk</button>
                </div>

            </form>
        </div>
    </div>
</x-layouts.auth>

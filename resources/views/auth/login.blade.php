<x-layouts.auth title="Login - POS System">

    <div class="bg-white rounded-lg shadow-lg p-8">

        <div class="text-center mb-8">
            <div class="text-3xl font-bold text-blue-600 mb-2">LOGO</div>
        </div>

        @if(session('status'))
            <div class="bg-blue-100 text-blue-700 p-3 rounded-md mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600">
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Masuk
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray-600 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-medium">Daftar di sini</a>
            </p>
        </div>

    </div>

</x-layouts.auth>

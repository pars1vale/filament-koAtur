<x-layouts.auth title="Reset Password - POS System">

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="text-2xl font-bold text-blue-600 mb-2">Lupa Password?</div>
            <p class="text-gray-600 text-sm">Masukkan email yang terdaftar untuk mengatur ulang password.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                <x-input-error :messages="$errors->get('email')" class="text-red-500 text-sm mt-1" />
            </div>

            <button type="submit"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kirim Link Reset Password
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Kembali ke halaman <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
        </p>

    </div>

</x-layouts.auth>

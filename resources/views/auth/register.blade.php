<x-layouts.auth title="Daftar Akun - POS System">

    <div class="bg-white rounded-lg shadow-lg p-8">

        <div class="text-center mb-8">
            <div class="text-3xl font-bold text-blue-600 mb-2">LOGO</div>
            <p class="text-gray-600 text-sm">Buat akun baru untuk mulai menggunakan POS System</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                <x-input-error :messages="$errors->get('name')" class="text-red-500 text-sm mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                <x-input-error :messages="$errors->get('email')" class="text-red-500 text-sm mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                <x-input-error :messages="$errors->get('password')" class="text-red-500 text-sm mt-1" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk</a>
        </p>

    </div>

</x-layouts.auth>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-8">
    <main class="w-full max-w-lg rounded-xl bg-white p-8 shadow-md">
        <h1 class="mb-2 text-center text-2xl font-bold text-gray-800">Buat Akun Peminjam</h1>
        <p class="mb-6 text-center text-sm text-gray-600">Daftar untuk menggunakan sistem peminjaman alat.</p>

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-semibold text-gray-700">Nama</label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label for="no_hp" class="mb-1 block text-sm font-semibold text-gray-700">Nomor HP (opsional)</label>
                <input id="no_hp" name="no_hp" value="{{ old('no_hp') }}" maxlength="15" autocomplete="tel"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label for="alamat" class="mb-1 block text-sm font-semibold text-gray-700">Alamat (opsional)</label>
                <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('alamat') }}</textarea>
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-gray-700">Password</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <button type="submit" class="w-full rounded-lg bg-blue-600 py-2.5 font-semibold text-white transition hover:bg-blue-700">
                Daftar
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-600">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Masuk</a>
        </p>
    </main>
</body>
</html>

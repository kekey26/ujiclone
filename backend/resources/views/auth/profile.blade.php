@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header-title', 'Profil Saya')

@section('content')
    @php
        $user = auth()->user();
        $initial = mb_strtoupper(mb_substr($user->name, 0, 1));
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <section class="flex flex-col items-start gap-5 rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 p-7 text-white shadow-lg sm:flex-row sm:items-center sm:px-8 sm:py-9">
            @if ($user->foto_profile)
                <img src="{{ asset('storage/' . $user->foto_profile) }}" alt="Foto profil {{ $user->name }}"
                    class="h-24 w-24 shrink-0 rounded-2xl border-4 border-white/20 object-cover">
            @else
                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border-4 border-white/20 bg-white/10 text-4xl font-bold">
                    {{ $initial }}
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-200">Akun Saya</p>
                <h2 class="mt-2 truncate text-2xl font-bold sm:text-3xl">{{ $user->name }}</h2>
                <p class="mt-1 truncate text-sm text-slate-300">{{ $user->email }}</p>
                <span class="mt-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold capitalize text-slate-100">
                    <svg class="h-4 w-4 text-indigo-200" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 1.5 17 4v5.3c0 4.2-2.9 7.3-7 9.2-4.1-1.9-7-5-7-9.2V4l7-2.5Zm3.2 6.7a1 1 0 0 0-1.4-1.4L9 9.6 8.2 8.8a1 1 0 0 0-1.4 1.4l1.5 1.5a1 1 0 0 0 1.4 0l3.5-3.5Z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $user->role }}
                </span>
            </div>

            <a href="#informasi-profil"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-indigo-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.651-1.651a2.121 2.121 0 0 1 3 3l-9.193 9.193a4 4 0 0 1-1.688 1.009l-3.163.948.948-3.163a4 4 0 0 1 1.009-1.688l7.436-7.648ZM19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h6"></path>
                </svg>
                Edit Profil
            </a>
        </section>

        <section id="informasi-profil" class="scroll-mt-24 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4H8a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2ZM9 9h6m-6 4h6m-6 4h3"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Informasi Profil</h3>
                    <p class="mt-1 text-sm text-slate-500">Informasi akun dan kontak Anda.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-7 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="foto_profile" class="mb-2 block text-sm font-semibold text-slate-700">Foto Profil</label>
                    <input id="foto_profile" type="file" name="foto_profile" accept="image/jpeg,image/png,image/webp"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-600 file:mr-4 file:border-0 file:bg-indigo-50 file:px-4 file:py-3 file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-2 text-xs text-slate-500">JPG, PNG, atau WEBP; maksimal 2 MB. Kosongkan jika tidak ingin mengganti.</p>
                    @error('foto_profile') <span class="mt-1 block text-sm text-red-600">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="email"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Telepon</label>
                        <input id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" maxlength="15" autocomplete="tel"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
                        <input id="role" value="{{ ucfirst($user->role) }}" disabled
                            class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500">
                        <p class="mt-2 text-xs text-slate-500">Role hanya dapat diubah oleh administrator.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end border-t border-slate-100 pt-5">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection

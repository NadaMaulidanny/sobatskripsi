<x-app-layout>
    <x-slot name="title">Tambah Mahasiswa - Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Tambah Mahasiswa</h3>
        <p class="text-xs text-gray-400 mt-0.5">Daftarkan akun login serta informasi data akademik mahasiswa baru</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('super_admin.mahasiswa.store') }}" method="POST">
            @csrf
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2">Akun Login</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Mahasiswa</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('name') border-rose-500 @enderror">
                        @error('name') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="nim@student.kampus.ac.id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('email') border-rose-500 @enderror">
                        @error('email') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Password</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('password') border-rose-500 @enderror">
                        @error('password') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2">Data Akademik</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NIM (Nomor Induk Mahasiswa)</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" placeholder="Nomor Induk Mahasiswa" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('nim') border-rose-500 @enderror">
                        @error('nim') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Program Studi</label>
                        <select name="prodi_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('prodi_id') border-rose-500 @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('super_admin.mahasiswa.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
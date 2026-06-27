<x-app-layout>
    <x-slot name="title">Tambah Dosen - Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Tambah Dosen Pembimbing</h3>
        <p class="text-xs text-gray-400 mt-0.5">Buat data login beserta profil lengkap dosen baru</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('super_admin.dosen.store') }}" method="POST">
            @csrf
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2">Informasi Akun Login</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Dr. John Doe, M.T." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('name') border-rose-500 @enderror">
                        @error('name') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Email Resmi</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: johndoe@kampus.ac.id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('email') border-rose-500 @enderror">
                        @error('email') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Password Default</label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('password') border-rose-500 @enderror">
                        @error('password') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-blue-600 uppercase tracking-wider border-b border-gray-100 pb-2">Data Kepegawaian</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NIDN</label>
                        <input type="text" name="nidn" value="{{ old('nidn') }}" placeholder="Nomor Induk Dosen Nasional" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('nidn') border-rose-500 @enderror">
                        @error('nidn') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Program Studi Homebase</label>
                        <select name="prodi_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('prodi_id') border-rose-500 @enderror">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                        @error('prodi_id') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Kuota Maksimal Bimbingan</label>
                        <input type="number" name="kuota" value="{{ old('kuota', 10) }}" min="0" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition @error('kuota') border-rose-500 @enderror">
                        @error('kuota') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div class="mt-4 flex items-center gap-2">
                        <input type="checkbox" name="is_kaprodi" value="1" id="is_kaprodi"
                            {{ old('is_kaprodi', isset($dosen) && $dosen->user->role === 'kaprodi' ? 'checked' : '') }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_kaprodi" class="text-xs font-bold text-gray-700 uppercase tracking-wide select-none cursor-pointer">
                            Jadikan Dosen ini sebagai Kaprodi
                        </label>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('super_admin.dosen.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
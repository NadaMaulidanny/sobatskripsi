<x-app-layout>
    <x-slot name="title">Edit Mahasiswa - Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Edit Data Mahasiswa</h3>
        <p class="text-xs text-gray-400 mt-0.5">Ubah rincian profil akademik mahasiswa</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('super_admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-amber-600 uppercase tracking-wider border-b border-gray-100 pb-2">Akun Login</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Mahasiswa</label>
                        <input type="text" name="name" value="{{ old('name', $mahasiswa->user->name) }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $mahasiswa->user->email) }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Ganti Password (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="font-bold text-xs text-amber-600 uppercase tracking-wider border-b border-gray-100 pb-2">Data Akademik</h4>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Program Studi</label>
                        <select name="prodi_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition">
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('super_admin.mahasiswa.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
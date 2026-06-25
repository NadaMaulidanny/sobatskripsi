<x-app-layout>
    <x-slot name="title">Tambah Prodi - Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Tambah Program Studi</h3>
        <p class="text-xs text-gray-400 mt-0.5">Input data program studi baru ke dalam sistem</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('super_admin.prodi.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-4 w-full">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Program Studi</label>
                    <input type="text" name="nama_prodi" value="{{ old('nama_prodi') }}" placeholder="Contoh: Teknik Informatika" 
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800 placeholder-gray-400 @error('nama_prodi') border-rose-500 @enderror">
                    @error('nama_prodi') 
                        <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('super_admin.prodi.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
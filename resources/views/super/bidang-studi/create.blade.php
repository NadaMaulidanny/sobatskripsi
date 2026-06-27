<x-app-layout>
    <x-slot name="title">Tambah Bidang Studi - Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Tambah Bidang Studi</h3>
        <p class="text-xs text-gray-400 mt-0.5">Input bidang studi baru dan kaitkan dengan program studinya</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('super_admin.bidang-studi.store') }}" method="POST">
            @csrf
            
            <div class="p-6 space-y-4 w-full">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Program Studi</label>
                    <select name="prodi_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800 @error('prodi_id') border-rose-500 @enderror">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    @error('prodi_id') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Bidang Studi / Konsentrasi</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Artificial Intelligence / Rekayasa Perangkat Lunak" 
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800 placeholder-gray-400 @error('nama') border-rose-500 @enderror">
                    @error('nama') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('super_admin.bidang-studi.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
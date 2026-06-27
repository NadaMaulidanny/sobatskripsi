<x-app-layout>
    <x-slot name="title">Booking Jadwal Bimbingan</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Booking Jadwal Bimbingan</h3>
        <p class="text-xs text-gray-400 mt-0.5">Ajukan rencana tanggal pertemuan dan sertakan file draf perkembangan skripsi Anda</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 space-y-4 w-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Tujukan ke Dosen Pembimbing</label>
                        <select name="dosen_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800" required>
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                            @foreach($dosens as $dsn)
                                <option value="{{ $dsn->id }}" {{ old('dosen_id') == $dsn->id ? 'selected' : '' }}>{{ $dsn->user->name }}</option>
                            @endforeach
                        </select>
                        @error('dosen_id') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Bab Pembahasan</label>
                        <select name="bab" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800" required>
                            <option value="Bab 1" {{ old('bab') == 'Bab 1' ? 'selected' : '' }}>Bab 1: Pendahuluan</option>
                            <option value="Bab 2" {{ old('bab') == 'Bab 2' ? 'selected' : '' }}>Bab 2: Tinjauan Pustaka</option>
                            <option value="Bab 3" {{ old('bab') == 'Bab 3' ? 'selected' : '' }}>Bab 3: Metodologi Penelitian</option>
                            <option value="Bab 4" {{ old('bab') == 'Bab 4' ? 'selected' : '' }}>Bab 4: Hasil dan Pembahasan</option>
                            <option value="Bab 5" {{ old('bab') == 'Bab 5' ? 'selected' : '' }}>Bab 5: Penutup (Kesimpulan)</option>
                            <option value="Lainnya" {{ old('bab') == 'Lainnya' ? 'selected' : '' }}>Diskusi Umum / Tambahan</option>
                        </select>
                        @error('bab') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Rencana Tanggal Pertemuan</label>
                        <input type="date" name="tanggal_bimbingan" value="{{ old('tanggal_bimbingan', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800" required>
                        @error('tanggal_bimbingan') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Upload File Draf Bab (PDF/DOCX, Max 5MB)</label>
                        <input type="file" name="file_bab" 
                               class="w-full px-3 py-1.5 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition font-medium text-gray-800 file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                        @error('file_bab') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Rencana Bahasan / Poin Kemajuan</label>
                    <textarea name="kegiatan" rows="5" placeholder="Tuliskan poin penting yang ingin dikonsultasikan atau draf revisi yang sudah Anda kerjakan..." 
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition text-gray-800 font-medium" required>{{ old('kegiatan') }}</textarea>
                    @error('kegiatan') <p class="text-rose-500 text-[10px] mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('mahasiswa.logbook.index') }}" class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold rounded-xl border border-gray-200 transition">
                    Kembali
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition">
                    Booking Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
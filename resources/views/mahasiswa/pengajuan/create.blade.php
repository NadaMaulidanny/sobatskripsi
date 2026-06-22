<x-app-layout>
    <x-slot name="title">Ajukan Judul Skripsi - Portal Skripsi</x-slot>

    <div class="mb-6 w-full">
        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center mb-2 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat Pengajuan
        </a>
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Formulir Pengajuan Judul Skripsi</h3>
        <p class="text-xs text-gray-400 mt-0.5">Isi detail usulan judul riset serta request dosen pembimbing</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-6 md:p-8">
         
        <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" class="space-y-6 w-full">
            @csrf

            <div class="space-y-1.5 w-full">
                <label for="judul" class="text-xs font-bold text-gray-700 uppercase tracking-wide block">Judul Usulan Skripsi</label>
                <input type="text" name="judul" id="judul" required value="{{ old('judul') }}"
                       placeholder="Contoh: Penerapan Algoritma K-Means Untuk Klasterisasi Data Penjualan..." 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition font-medium text-gray-800 placeholder-gray-400">
                @error('judul') <p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5 w-full">
                <label for="bidang_studi_id" class="text-xs font-bold text-gray-700 uppercase tracking-wide block">Fokus Bidang Studi / Keahlian</label>
                <select name="bidang_studi_id" id="bidang_studi_id" required onchange="filterDosenByBidang(this.value)"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition font-medium text-gray-700 bg-white">
                    <option value="" disabled selected>-- Pilih Bidang Studi yang Sesuai --</option>
                    @foreach($bidangStudis as $bidang)
                        <option value="{{ $bidang->id }}" {{ old('bidang_studi_id') == $bidang->id ? 'selected' : '' }}>{{ $bidang->nama }}</option>
                    @endforeach
                </select>
                @error('bidang_studi_id') <p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div class="space-y-1.5 relative">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wide block">Usulan Dosen Pembimbing 1</label>
                    
                    <input type="hidden" name="pembimbing_1_id" id="pembimbing_1_id" value="{{ old('pembimbing_1_id') }}">
                    
                    <button type="button" id="btn_pembimbing_1" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-left flex items-center justify-between transition focus:ring-4 focus:ring-blue-50 focus:border-blue-500">
                        <span id="text_pembimbing_1" class="text-xs font-medium text-gray-400">-- Pilih Pembimbing 1 --</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>

                    <div id="options_pembimbing_1" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl z-50 max-h-60 overflow-y-auto divide-y divide-gray-50 p-1.5">
                        <div class="p-3 text-xs text-gray-400 font-medium italic">Silakan pilih bidang studi terlebih dahulu...</div>
                    </div>
                    @error('pembimbing_1_id') <p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5 relative">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wide block">Usulan Dosen Pembimbing 2</label>
                    
                    <input type="hidden" name="pembimbing_2_id" id="pembimbing_2_id" value="{{ old('pembimbing_2_id') }}">
                    
                    <button type="button" id="btn_pembimbing_2" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-left flex items-center justify-between transition focus:ring-4 focus:ring-blue-50 focus:border-blue-500">
                        <span id="text_pembimbing_2" class="text-xs font-medium text-gray-400">-- Pilih Pembimbing 2 --</span>
                        <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                    </button>

                    <div id="options_pembimbing_2" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl z-40 max-h-60 overflow-y-auto divide-y divide-gray-50 p-1.5">
                        <div class="p-3 text-xs text-gray-400 font-medium italic">Silakan pilih bidang studi terlebih dahulu...</div>
                    </div>
                    @error('pembimbing_2_id') <p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="space-y-1.5 w-full">
                <label for="deskripsi" class="text-xs font-bold text-gray-700 uppercase tracking-wide block">Deskripsi Singkat / Abstrak Riset</label>
                <textarea name="deskripsi" id="deskripsi" rows="6" required 
                          placeholder="Jelaskan secara singkat latar belakang masalah, metode yang diusulkan, serta tujuan akhir penelitian Anda..."
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 text-xs focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition font-medium text-gray-800 placeholder-gray-400 leading-relaxed">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-[11px] mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="border-t border-gray-100 pt-5 flex justify-end space-x-3 w-full">
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="px-5 py-3 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold rounded-xl text-xs transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>

    <script>
    // Ambil data seluruh dosen dari controller (berisi properti kuota & total_bimbingan)
    const semuaDosen = @json($daftarDosen);

    document.addEventListener('DOMContentLoaded', function() {
        const btn1 = document.getElementById('btn_pembimbing_1');
        const options1 = document.getElementById('options_pembimbing_1');
        const btn2 = document.getElementById('btn_pembimbing_2');
        const options2 = document.getElementById('options_pembimbing_2');

        // Buka tutup dropdown 1
        btn1.addEventListener('click', function(e) {
            e.stopPropagation();
            options1.classList.toggle('hidden');
            options2.classList.add('hidden');
        });

        // Buka tutup dropdown 2
        btn2.addEventListener('click', function(e) {
            e.stopPropagation();
            options2.classList.toggle('hidden');
            options1.classList.add('hidden');
        });

        // Tutup semua dropdown jika klik di luar halaman
        document.addEventListener('click', function() {
            options1.classList.add('hidden');
            options2.classList.add('hidden');
        });
    });

    // FUNGSI UTAMA: Memfilter dosen sebidang dan merender ulang tampilan list custom dropdown
    function filterDosenByBidang(bidangStudiId) {
        // Filter dosen yang memiliki bidang_studi_id yang cocok
        const dosenTerfilter = semuaDosen.filter(dosen => {
            return dosen.bidang_studis.some(b => b.id == bidangStudiId);
        });

        // Reset pilihan terpilih mahasiswa sebelumnya
        resetCustomDropdown(1);
        resetCustomDropdown(2);

        // Ambil elemen container opsi bimbingan 1 & 2
        const container1 = document.getElementById('options_pembimbing_1');
        const container2 = document.getElementById('options_pembimbing_2');

        if (dosenTerfilter.length === 0) {
            const emptyHtml = `<div class="p-3 text-xs text-gray-400 font-medium italic">Tidak ada dosen di bidang studi ini...</div>`;
            container1.innerHTML = emptyHtml;
            container2.innerHTML = emptyHtml;
            return;
        }

        // Render HTML untuk masing-masing dosen yang lolos filter
        let htmlContent1 = '';
        let htmlContent2 = '';

        dosenTerfilter.forEach(dosen => {
            const totalBimbingan = dosen.total_bimbingan ?? 0;
            const sisaKuota = dosen.kuota - totalBimbingan;
            
            // Tentukan warna badge kuota (Hijau jika ada, merah jika habis bimbingannya)
            const badgeClass = sisaKuota > 0 
                ? 'bg-emerald-50 text-emerald-600 border-emerald-100' 
                : 'bg-rose-50 text-rose-600 border-rose-100';

            // Template baris card kecil dosen bimbingan 1
            htmlContent1 += `
                <div onclick="selectCustomDosen(1, '${dosen.id}', '${dosen.user.name}')" class="p-2.5 hover:bg-blue-50/60 rounded-lg cursor-pointer flex items-center justify-between gap-2 group transition">
                    <div>
                        <p class="text-xs font-bold text-gray-800 group-hover:text-blue-700 transition">${dosen.user.name}</p>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">NIDN: ${dosen.nidn ?? '-'}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border ${badgeClass}">
                            Sisa Kuota: ${sisaKuota}/${dosen.kuota}
                        </span>
                    </div>
                </div>`;

            // Template baris card kecil dosen bimbingan 2
            htmlContent2 += `
                <div onclick="selectCustomDosen(2, '${dosen.id}', '${dosen.user.name}')" class="p-2.5 hover:bg-blue-50/60 rounded-lg cursor-pointer flex items-center justify-between gap-2 group transition">
                    <div>
                        <p class="text-xs font-bold text-gray-800 group-hover:text-blue-700 transition">${dosen.user.name}</p>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">NIDN: ${dosen.nidn ?? '-'}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border ${badgeClass}">
                            Sisa Kuota: ${sisaKuota}/${dosen.kuota}
                        </span>
                    </div>
                </div>`;
        });

        container1.innerHTML = htmlContent1;
        container2.innerHTML = htmlContent2;
    }

    // Handler ketika salah satu baris dosen di-klik oleh mahasiswa
    function selectCustomDosen(type, id, name) {
        document.getElementById('pembimbing_' + type + '_id').value = id;
        
        const textSpan = document.getElementById('text_pembimbing_' + type);
        textSpan.innerText = name;
        textSpan.className = "text-xs font-bold text-gray-800"; // Ubah warna jadi solid ketika terpilih
    }

    // Fungsi pembantu untuk mengosongkan pilihan bimbingan
    function resetCustomDropdown(type) {
        document.getElementById('pembimbing_' + type + '_id').value = '';
        const textSpan = document.getElementById('text_pembimbing_' + type);
        textSpan.innerText = '-- Pilih Pembimbing ' + type + ' --';
        textSpan.className = "text-xs font-medium text-gray-400";
    }
</script>
</x-app-layout>
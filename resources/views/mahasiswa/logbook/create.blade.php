<x-app-layout>
    <x-slot name="title">Ajukan Bimbingan Baru</x-slot>

    <div class="mb-6">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Form Pengajuan Bimbingan</h3>
        <p class="text-xs text-gray-400 mt-0.5">Silakan pilih dosen pembimbing tujuan, tentukan jadwal, dan sertakan draf laporan progres Anda.</p>
    </div>

    <div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('mahasiswa.logbook.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- 1. PILIH DOSEN PEMBIMBING SASARAN --}}
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">Pilih Dosen Pembimbing Tujuan</label>
                <select name="dosen_id" id="select_dosen" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-50 focus:border-blue-500" required onchange="loadJadwalDosen(this.value)">
                    <option value="">-- Pilih Dosen Pembimbing --</option>
                    @if($pembimbing1)
                        <option value="{{ $pembimbing1->id }}" {{ old('dosen_id') == $pembimbing1->id ? 'selected' : '' }}>Pembimbing 1: {{ $pembimbing1->user->name }}</option>
                    @endif
                    @if($pembimbing2)
                        <option value="{{ $pembimbing2->id }}" {{ old('dosen_id') == $pembimbing2->id ? 'selected' : '' }}>Pembimbing 2: {{ $pembimbing2->user->name }}</option>
                    @endif
                </select>
                @error('dosen_id') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- 2. INPUT TANGGAL (DINAMIS BERDASARKAN JAVASCRIPT FETCH) --}}
            <div id="wrapper_tanggal" class="p-4 bg-slate-50 rounded-2xl border border-gray-200/60 transition duration-300">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5 flex items-center gap-1">
                    <i class="fa-solid fa-calendar-day text-gray-400"></i> Rencana Tanggal Pertemuan
                </label>
                
                <input type="date" id="tanggal_bimbingan" name="tanggal_bimbingan" 
                       value="{{ old('tanggal_bimbingan') }}" 
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition font-bold text-gray-800 shadow-sm" 
                       required 
                       disabled
                       onchange="validasiHariDosen(this)">

                <p id="info_hari_kerja" class="text-[10px] text-gray-500 mt-2 font-semibold bg-white px-2 py-1.5 rounded-lg border border-gray-100 hidden items-center gap-1">
                    <span>📌 Hari Kerja Dosen Terpilih:</span> 
                    <span id="text_hari_dosen" class="text-blue-800 font-extrabold">-</span>
                </p>
                @error('tanggal_bimbingan') <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">Fokus Pembahasan Bab</label>
                <select name="bab" class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 focus:ring-2 focus:ring-blue-50 focus:border-blue-500" required>
                    <option value="Judul / Latar Belakang" {{ old('bab') == 'Judul / Latar Belakang' ? 'selected' : '' }}>Judul & Latar Belakang Masalah</option>
                    <option value="BAB 1 (Pendahuluan)" {{ old('bab') == 'BAB 1 (Pendahuluan)' ? 'selected' : '' }}>BAB 1 - Pendahuluan</option>
                    <option value="BAB 2 (Tinjauan Pustaka)" {{ old('bab') == 'BAB 2 (Tinjauan Pustaka)' ? 'selected' : '' }}>BAB 2 - Tinjauan Pustaka</option>
                    <option value="BAB 3 (Metodologi)" {{ old('bab') == 'BAB 3 (Metodologi)' ? 'selected' : '' }}>BAB 3 - Metodologi Penelitian</option>
                    <option value="BAB 4 (Hasil & Analisis)" {{ old('bab') == 'BAB 4 (Hasil & Analisis)' ? 'selected' : '' }}>BAB 4 - Hasil dan Analisis Penelitian</option>
                    <option value="BAB 5 (Penutup/Kesimpulan)" {{ old('bab') == 'BAB 5 (Penutup/Kesimpulan)' ? 'selected' : '' }}>BAB 5 - Kesimpulan dan Saran</option>
                </select>
                @error('bab') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">Rencana Kegiatan & Deskripsi Bahasan</label>
                <textarea name="kegiatan" rows="4" placeholder="Tuliskan poin-poin yang ingin Anda tanyakan atau laporkan pada bimbingan kali ini..." class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-xs text-gray-800 font-medium focus:ring-2 focus:ring-blue-50 focus:border-blue-500" required>{{ old('kegiatan') }}</textarea>
                @error('kegiatan') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">Lampiran File Draf Bab (Opsional)</label>
                <input type="file" name="file_bab" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer" required>
                <p class="text-[9px] text-gray-400 mt-1">*Format yang diterima: PDF, DOC, DOCX (Maksimal ukuran file: 2MB)</p>
                @error('file_bab') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- PANEL AKSI FORM --}}
            <div class="flex justify-end gap-2 border-t border-gray-50 pt-4 mt-2">
                <a href="{{ route('mahasiswa.logbook.index') }}" class="px-4 py-2 bg-white text-gray-600 border border-gray-200 text-xs font-bold rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-blue-100">
                    Kirim Ajuan Jadwal
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    let hariValidDosen = []; 

    // Mapping nama hari dari format database/JS ke angka index Date JavaScript
    // 0 = Minggu, 1 = Senin, 2 = Selasa, 3 = Rabu, 4 = Kamis, 5 = Jumat, 6 = Sabtu
    const mapHariKeAngka = {
        'sunday': 0, 'minggu': 0,
        'monday': 1, 'senin': 1,
        'tuesday': 2, 'selasa': 2,
        'wednesday': 3, 'rabu': 3,
        'thursday': 4, 'kamis': 4,
        'friday': 5, 'jumat': 5,
        'saturday': 6, 'sabtu': 6
    };

    const namaHariIndo = { 
        'Sunday': 'Minggu', 'Monday': 'Senin', 'Tuesday': 'Selasa', 
        'Wednesday': 'Rabu', 'Thursday': 'Kamis', 'Friday': 'Jumat', 'Saturday': 'Sabtu' 
    };

    function loadJadwalDosen(dosenId) {
        const inputTanggal = document.getElementById('tanggal_bimbingan');
        const wrapper = document.getElementById('wrapper_tanggal');
        const infoBox = document.getElementById('info_hari_kerja');
        const textHari = document.getElementById('text_hari_dosen');

        // Reset input tanggal setiap kali dosen diubah
        inputTanggal.value = '';

        if (!dosenId) {
            inputTanggal.disabled = true;
            infoBox.classList.add('hidden');
            wrapper.className = "p-4 bg-slate-50 rounded-2xl border border-gray-200/60 transition duration-300";
            return;
        }

        fetch(`/mahasiswa/get-hari-dosen/${dosenId}`)
            .then(response => response.json())
            .then(data => {
                // SINKRONISASI UTAMA: Ubah string hari dari DB ke lowercase
                hariValidDosen = data.map(hari => hari.toLowerCase());
                
                // Aktifkan kembali kolom kalender
                inputTanggal.disabled = false;
                wrapper.className = "p-4 bg-blue-50/50 rounded-2xl border border-blue-100/80 transition duration-300";
                
                if (hariValidDosen.length > 0) {
                    const namaHariOri = data.map(h => namaHariIndo[h] || h);
                    textHari.innerText = namaHariOri.join(', ');
                } else {
                    textHari.innerText = 'Bebas (Dosen belum mengatur jadwal rutin)';
                }
                
                infoBox.classList.remove('hidden');
                infoBox.classList.add('inline-flex');
            })
            .catch(err => {
                console.error("Gagal mengambil data jadwal:", err);
                inputTanggal.disabled = false; 
            });
    }

    // INTERCEPTOR SEBELUM FORM SUBMIT & REAL-TIME VALIDATION
    function validasiHariDosen(inputElement) {
        if (!inputElement.value) return;

        // Jika dosen belum mengatur jadwal sama sekali, bebaskan mahasiswa memilih tanggal
        if (hariValidDosen.length === 0) return;

        const tanggalTerpilih = new Date(inputElement.value);
        const daftarNamaHariEng = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        const namaHariEng = daftarNamaHariEng[tanggalTerpilih.getDay()]; // Contoh: 'Monday'
        const namaHariIndoTerpilih = namaHariIndo[namaHariEng]; // Contoh: 'Senin'

        const hariEngLower = namaHariEng.toLowerCase();
        const hariIndoLower = namaHariIndoTerpilih.toLowerCase();

        // Cek kecocokan hari
        const apakahHariValid = hariValidDosen.includes(hariEngLower) || hariValidDosen.includes(hariIndoLower);

        if (!apakahHariValid) {
            // Berikan notifikasi pop-up yang memotong tindakan mahasiswa secara tegas
            alert(`⚠️ Pilihan Tanggal Tidak Valid!\n\nDosen pembimbing tujuan Anda tidak membuka slot bimbingan pada hari tersebut.\n\nSilakan tentukan hari operasional lainnya.`);
            
            // Kosongkan kembali nilai kalender secara paksa agar tidak bisa di-submit
            inputElement.value = ''; 
        }
    }

    // Daftarkan fungsi ke form submit sebagai pengamanan lapis ganda
    document.querySelector('form').addEventListener('submit', function(e) {
        const inputTanggal = document.getElementById('tanggal_bimbingan');
        if (inputTanggal && !inputTanggal.value && !inputTanggal.disabled) {
            alert('Silakan pilih rencana tanggal pertemuan terlebih dahulu.');
            e.preventDefault();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const selectedDosen = document.getElementById('select_dosen').value;
        if (selectedDosen) {
            loadJadwalDosen(selectedDosen);
        }
    });
</script>
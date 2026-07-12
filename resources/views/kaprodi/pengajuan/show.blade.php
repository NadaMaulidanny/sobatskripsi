<x-app-layout>
    <x-slot name="title">Tinjau Pengajuan Judul - Portal Skripsi</x-slot>

    <!-- Header Section -->
    <div class="mb-6 w-full">
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center mb-2 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Antrean
        </a>
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Lembar Penilaian Usulan Judul</h3>
        <p class="text-xs text-gray-400 mt-0.5">Periksa keselarasan topik riset serta silakan sesuaikan plot dosen pembimbing</p>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full items-start">
        
        <!-- Sisi Kiri: Informasi Judul & Abstrak Penelitian -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 space-y-6">
            <div>
                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-bold text-[10px] border border-blue-100 uppercase tracking-wide">
                    {{ $pengajuan->bidangStudi->nama ?? '-' }}
                </span>
                <h1 class="text-lg font-bold text-gray-900 mt-3 leading-snug">{{ $pengajuan->judul }}</h1>
                <p class="text-[11px] text-gray-400 mt-1.5">
                    Diajukan oleh: <strong class="text-gray-700">{{ $pengajuan->mahasiswa->user->name ?? 'N/A' }}</strong> 
                    <span class="mx-1">•</span> NIM: {{ $pengajuan->mahasiswa->nim ?? '-' }}
                </p>
            </div>

            <hr class="border-gray-100">

            <div>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Abstrak / Deskripsi Usulan</h4>
                <div class="text-xs text-gray-600 font-medium leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 whitespace-pre-line">
                    {{ $pengajuan->deskripsi }}
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Keputusan & Pengaturan Pembimbing -->
        <div class="space-y-6">
            
            @if($pengajuan->status === 'menunggu')
                <form action="{{ route('kaprodi.pengajuan.update', $pengajuan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Card 1: Pilihan Penyesuaian Dosen Pembimbing Resmi -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Penetapan Pembimbing</h4>
                            <span class="text-[9px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-md font-bold uppercase">Butuh Plotting</span>
                        </div>
                        
                        @php
                            // Ambil usulan awal mahasiswa dari tabel pivot untuk default value
                            $reqPmb1 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'request1');
                            $reqPmb2 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'request2');
                        @endphp

                        <div class="space-y-4">
                            <div class="space-y-1 relative">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wide block">Dosen Pembimbing 1</label>
                                
                                <input type="hidden" name="pembimbing_1_id" id="pembimbing_1_id" value="{{ $reqPmb1->id ?? '' }}">
                                
                                <button type="button" id="btn_pembimbing_1" class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/80 border border-gray-200/80 rounded-xl text-left flex items-center justify-between transition focus:ring-4 focus:ring-blue-50 focus:border-blue-500">
                                    <span id="text_pembimbing_1" class="text-xs font-bold text-gray-700">
                                        {{ $reqPmb1->user->name ?? '-- Pilih Pembimbing 1 --' }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                                </button>

                                <div id="options_pembimbing_1" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl z-50 max-h-60 overflow-y-auto divide-y divide-gray-50 p-1.5">
                                    <div onclick="selectDosen(1, '', '-- Pilih Pembimbing 1 --')" class="p-2 text-xs text-gray-400 hover:bg-slate-50 rounded-lg cursor-pointer font-medium">-- Batalkan Pilihan --</div>
                                    @foreach($daftarDosen as $dosen)
                                        @php
                                            $sisaKuota = $dosen->kuota - $dosen->total_bimbingan;
                                            $bidangList = $dosen->bidangStudis->pluck('nama')->implode(', ') ?: 'Tanpa Bidang';
                                        @endphp
                                        <div onclick="selectDosen(1, '{{ $dosen->id }}', '{{ $dosen->user->name }}')" class="p-2.5 hover:bg-blue-50/60 rounded-lg cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-2 group transition">
                                            <div>
                                                <p class="text-xs font-bold text-gray-800 group-hover:text-blue-700 transition">{{ $dosen->user->name }}</p>
                                                <p class="text-[10px] text-gray-400 font-medium mt-0.5"><i class="fa-solid fa-tags text-[9px] mr-1"></i>{{ $bidangList }}</p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md {{ $sisaKuota > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }} border">
                                                    Sisa Kuota: {{ $sisaKuota }}/{{ $dosen->kuota }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-1 relative">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wide block">Dosen Pembimbing 2</label>
                                
                                <input type="hidden" name="pembimbing_2_id" id="pembimbing_2_id" value="{{ $reqPmb2->id ?? '' }}">
                                
                                <button type="button" id="btn_pembimbing_2" class="w-full px-4 py-3 bg-slate-50 hover:bg-slate-100/80 border border-gray-200/80 rounded-xl text-left flex items-center justify-between transition focus:ring-4 focus:ring-blue-50 focus:border-blue-500">
                                    <span id="text_pembimbing_2" class="text-xs font-bold text-gray-700">
                                        {{ $reqPmb2->user->name ?? '-- Pilih Pembimbing 2 --' }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-[10px]"></i>
                                </button>

                                <div id="options_pembimbing_2" class="hidden absolute left-0 right-0 mt-1 bg-white border border-gray-100 shadow-xl rounded-xl z-40 max-h-60 overflow-y-auto divide-y divide-gray-50 p-1.5">
                                    <div onclick="selectDosen(2, '', '-- Pilih Pembimbing 2 --')" class="p-2 text-xs text-gray-400 hover:bg-slate-50 rounded-lg cursor-pointer font-medium">-- Batalkan Pilihan --</div>
                                    @foreach($daftarDosen as $dosen)
                                        @php
                                            $sisaKuota = $dosen->kuota - $dosen->total_bimbingan;
                                            $bidangList = $dosen->bidangStudis->pluck('nama')->implode(', ') ?: 'Tanpa Bidang';
                                        @endphp
                                        <div onclick="selectDosen(2, '{{ $dosen->id }}', '{{ $dosen->user->name }}')" class="p-2.5 hover:bg-blue-50/60 rounded-lg cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-2 group transition">
                                            <div>
                                                <p class="text-xs font-bold text-gray-800 group-hover:text-blue-700 transition">{{ $dosen->user->name }}</p>
                                                <p class="text-[10px] text-gray-400 font-medium mt-0.5"><i class="fa-solid fa-tags text-[9px] mr-1"></i>{{ $bidangList }}</p>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <span class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-md {{ $sisaKuota > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }} border">
                                                    Sisa Kuota: {{ $sisaKuota }}/{{ $dosen->kuota }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-[10px] text-gray-400 leading-normal italic pt-1 border-t border-gray-50">
                            * Informasi menu dropdown di atas mencakup nama lengkap, fokus bidang keahlian, beserta sisa slot bimbingan aktif dosen.
                        </p>
                    </div>

                    <!-- Card 2: Form Catatan & Tombol Keputusan Final -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Aksi Keputusan</h4>

                        <div class="space-y-1.5">
                            <label for="catatan_kaprodi" class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Catatan / Alasan / Revisi</label>
                            <textarea name="catatan_kaprodi" id="catatan_kaprodi" rows="4" 
                                      placeholder="Tulis alasan jika menolak, atau masukkan instruksi tambahan jika menyetujui judul..."
                                      class="w-full px-3 py-2 border border-gray-200 text-xs rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition font-medium text-gray-800"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <button type="submit" name="status" value="ditolak" 
                                    class="py-2.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-bold rounded-xl text-xs transition text-center focus:ring-4 focus:ring-rose-50">
                                <i class="fa-solid fa-xmark mr-1"></i> Tolak Judul
                            </button>
                            <button type="submit" name="status" value="disetujui" 
                                    class="py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-100 transition text-center focus:ring-4 focus:ring-emerald-50">
                                <i class="fa-solid fa-check mr-1"></i> Setujui (ACC)
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <!-- TAMPILAN LOCK READ-ONLY (JIKA SUDAH DIVALIDASI) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Dosen Pembimbing Resmi</h4>
                    
                    @php
                        $pmb1 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'pembimbing1');
                        $pmb2 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'pembimbing2');
                    @endphp
                    
                    <div class="space-y-2 text-xs font-bold text-gray-800">
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-medium">Pembimbing 1</p>
                                <p class="mt-0.5">{{ $pmb1->user->name ?? 'Belum Ditentukan' }}</p>
                            </div>
                            <i class="fa-solid fa-user-tie text-gray-300 text-sm"></i>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-medium">Pembimbing 2</p>
                                <p class="mt-0.5">{{ $pmb2->user->name ?? 'Belum Ditentukan' }}</p>
                            </div>
                            <i class="fa-solid fa-user-tie text-gray-300 text-sm"></i>
                        </div>
                    </div>

                    <div class="p-3 rounded-xl text-xs font-bold text-center {{ $pengajuan->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                        STATUS KEPUTUSAN: {{ strtoupper($pengajuan->status) }}
                    </div>
                    
                    @if($pengajuan->catatan_kaprodi)
                        <div class="mt-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide block mb-1">Catatan Anda:</span>
                            <p class="text-xs bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-600 font-medium italic">
                                "{{ $pengajuan->catatan_kaprodi }}"
                            </p>
                        </div>
                    @endif
                </div>
            @endif

            <div class="space-y-5 mt-5">
                {{-- BAGIAN 1: LIST SEMUA CATATAN/REVIEW DOSEN YANG SUDAH MASUK --}}
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm w-full">
                    <h5 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5 mb-4">
                        <i class="fa-solid fa-comments text-slate-400"></i> Catatan & Review Dosen Bidang
                    </h5>

                    @if($pengajuan->catatan_dosen && count($pengajuan->catatan_dosen) > 0)
                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                            @foreach($pengajuan->catatan_dosen as $rev)
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-[11px] font-bold text-gray-800">{{ $rev['nama_dosen'] }}</span>
                                        <span class="text-[9px] text-gray-400">{{ \Carbon\Carbon::parse($rev['tanggal'])->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[11px] text-gray-600 leading-relaxed whitespace-pre-line">{{ $rev['catatan'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-gray-400 text-xs">
                            <i class="fa-solid fa-comment-slash text-lg text-gray-300 mb-1 block"></i>
                            Belum ada catatan dari dosen bidang.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn1 = document.getElementById('btn_pembimbing_1');
        const options1 = document.getElementById('options_pembimbing_1');
        const btn2 = document.getElementById('btn_pembimbing_2');
        const options2 = document.getElementById('options_pembimbing_2');

        // Toggle Dropdown 1
        btn1.addEventListener('click', function(e) {
            e.stopPropagation();
            options1.classList.toggle('hidden');
            options2.classList.add('hidden'); // Tutup dropdown 2 jika terbuka
        });

        // Toggle Dropdown 2
        btn2.addEventListener('click', function(e) {
            e.stopPropagation();
            options2.classList.toggle('hidden');
            options1.classList.add('hidden'); // Tutup dropdown 1 jika terbuka
        });

        // Tutup semua dropdown jika klik di luar area menu
        document.addEventListener('click', function() {
            options1.classList.add('hidden');
            options2.classList.add('hidden');
        });
    });

    // Fungsi handle ketika salah satu opsi dosen di-klik
    function selectDosen(type, id, name) {
        // Set value ke input hidden agar bisa dibaca saat submit form
        document.getElementById('pembimbing_' + type + '_id').value = id;
        // Ganti teks pemicu tombol dengan nama dosen terpilih
        document.getElementById('text_pembimbing_' + type).innerText = name;
    }
</script>
</x-app-layout>
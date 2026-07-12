<x-app-layout>
    <x-slot name="title">Detail Pengajuan Skripsi - Portal Skripsi</x-slot>

    <div class="mb-6 w-full">
        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center mb-2 transition">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Riwayat Pengajuan
        </a>
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Detail Usulan Judul Skripsi</h3>
        <p class="text-xs text-gray-400 mt-0.5">Rincian lengkap data usulan serta status review dari program studi</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full items-start">
        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8 space-y-6">
            <div>
                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-bold text-[10px] border border-blue-100 uppercase tracking-wide">
                    {{ $pengajuan->bidangStudi->nama ?? '-' }}
                </span>
                <h1 class="text-lg font-bold text-gray-900 mt-3 leading-snug">{{ $pengajuan->judul }}</h1>
                <p class="text-[11px] text-gray-400 mt-1">
                    <i class="fa-regular fa-calendar mr-1"></i> Diajukan pada: {{ $pengajuan->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>

            <hr class="border-gray-100">

            <div>
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Deskripsi / Abstrak Penelitian</h4>
                <p class="text-xs text-gray-600 font-medium leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100 whitespace-pre-line">
                    {{ $pengajuan->deskripsi }}
                </p>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Status Tinjauan</h4>
                
                @if($pengajuan->status === 'menunggu')
                    <div class="bg-amber-50 border border-amber-100 text-amber-700 p-4 rounded-xl text-xs font-medium">
                        <i class="fa-regular fa-clock mr-1.5 text-amber-500 font-bold"></i> Judul sedang antre dalam proses peninjauan oleh Kaprodi.
                    </div>
                @elseif($pengajuan->status === 'disetujui')
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-xl text-xs font-medium">
                        <i class="fa-regular fa-circle-check mr-1.5 text-emerald-500 font-bold"></i> Usulan judul skripsi telah disetujui.
                    </div>
                @else
                    <div class="bg-rose-50 border border-rose-100 text-rose-700 p-4 rounded-xl text-xs font-medium">
                        <i class="fa-regular fa-circle-xmark mr-1.5 text-rose-500 font-bold"></i> Usulan judul skripsi ditolak.
                    </div>
                @endif

                @if($pengajuan->catatan_kaprodi)
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide block mb-1">Catatan Kaprodi:</span>
                        <p class="text-xs bg-gray-50 p-3 rounded-lg border border-gray-100 text-gray-600 font-medium italic">
                            "{{ $pengajuan->catatan_kaprodi }}"
                        </p>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">Usulan Pembimbing</h4>
                    <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md font-bold uppercase tracking-tight">Status Request</span>
                </div>
                
                @php
                    // Ambil dosen berdasarkan status request/pembimbing dari data pivot secara fleksibel
                    $pmb1 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'request1') 
                            ?? $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'pembimbing1');

                    $pmb2 = $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'request2') 
                            ?? $pengajuan->pembimbingDosens->firstWhere('pivot.status', 'pembimbing2');
                @endphp

                <div class="space-y-3">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Dosen Pembimbing 1</p>
                            <p class="text-xs font-bold text-gray-800 mt-0.5">
                                {{ $pmb1->user->name ?? 'Tidak Mengusulkan' }}
                            </p>
                        </div>
                        <i class="fa-solid fa-user-tie text-gray-300 text-sm"></i>
                    </div>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Dosen Pembimbing 2</p>
                            <p class="text-xs font-bold text-gray-800 mt-0.5">
                                {{ $pmb2->user->name ?? 'Tidak Mengusulkan' }}
                            </p>
                        </div>
                        <i class="fa-solid fa-user-tie text-gray-300 text-sm"></i>
                    </div>
                </div>
                
                <p class="text-[10px] text-gray-400 italic leading-snug pt-1">
                    * Status dosen di atas akan berubah dari request menjadi resmi setelah divalidasi oleh Kaprodi.
                </p>
            </div>
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

                {{-- BAGIAN 2: FORM INPUT TAMBAH CATATAN BARU --}}
                @if(Auth::user()->dosen && in_array($pengajuan->bidang_studi_id, Auth::user()->dosen->bidangStudis->pluck('id')->toArray()))
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col w-full">
                        <h5 class="text-xs font-bold text-gray-900 uppercase tracking-wider flex items-center gap-1.5 mb-3">
                            <i class="fa-solid fa-comment-medical text-blue-500"></i> Tulis Catatan Baru
                        </h5>

                        @if(session('success'))
                            <div class="mb-3 p-3 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl text-[11px] font-medium flex items-center gap-1">
                                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('dosen.review.store', $pengajuan->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <textarea 
                                    name="catatan_input" 
                                    rows="3" 
                                    required
                                    placeholder="Berikan masukan atau rekomendasi Anda sebagai dosen bidang..."
                                    class="w-full p-3 text-xs bg-slate-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 placeholder-gray-400 shadow-inner resize-none"></textarea>
                                @error('catatan_input')
                                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-paper-plane text-[10px]"></i> Kirim Catatan Saya
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
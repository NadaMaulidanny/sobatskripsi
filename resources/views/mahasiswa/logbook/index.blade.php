<x-app-layout>
    <x-slot name="title">Riwayat Logbook Bimbingan</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Logbook Bimbingan Anda</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pantau status persetujuan jadwal bimbingan dan riwayat review draf bab skripsi Anda di sini.</p>
        </div>
        <a href="{{ route('mahasiswa.logbook.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-blue-100 flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-[10px]"></i> Ajukan Jadwal Baru
        </a>
    </div>

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-xs text-emerald-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($logbooks as $log)
            <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-6 transition hover:shadow-md hover:shadow-gray-100/50">
                
                {{-- HEADER LOGBOOK --}}
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 border-b border-gray-50 pb-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col items-center justify-center bg-slate-50 border border-gray-100 rounded-xl p-2.5 min-w-[65px] text-center">
                            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('M') }}</span>
                            <span class="text-lg font-extrabold text-gray-800 leading-none my-0.5">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('d') }}</span>
                            <span class="text-[9px] font-medium text-gray-400">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('Y') }}</span>
                        </div>

                        <div>
                            <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase border border-blue-100/50 inline-block mb-1">
                                {{ $log->bab }}
                            </span>
                            <p class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                <i class="fa-solid fa-calendar text-[10px] text-gray-400"></i> Tanggal Pertemuan: 
                                <span class="text-gray-800 font-bold">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('l, d F Y') }}</span>
                            </p>
                        </div>
                    </div>

                    <div>
                        {{-- STATUS BADGE DENGAN STYLE WARNA VARIATIF --}}
                        @if($log->status == 'pending')
                            <span class="bg-amber-50 text-amber-600 border border-amber-100 text-[10px] font-bold px-2.5 py-1 rounded-full inline-block shadow-sm">⏳ Menunggu Persetujuan</span>
                        @elseif($log->status == 'disetujui')
                            <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[10px] font-bold px-2.5 py-1 rounded-full inline-block shadow-sm">📅 Jadwal Disetujui (Siap Bertemu)</span>
                        @elseif($log->status == 'ditolak')
                            <span class="bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-bold px-2.5 py-1 rounded-full inline-block shadow-sm">❌ Jadwal Ditolak / Cancel</span>
                        @elseif($log->status == 'revisi')
                            <span class="bg-orange-50 text-orange-600 border border-orange-100 text-[10px] font-bold px-2.5 py-1 rounded-full inline-block shadow-sm">🔴 Hasil: Perlu Revisi</span>
                        @else
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-bold px-2.5 py-1 rounded-full inline-block shadow-sm">🟢 Hasil: Progres ACC</span>
                        @endif
                    </div>
                </div>

                {{-- ISI MATERI YANG DIAJUKAN MAHASISWA --}}
                <div class="text-xs text-gray-700 font-medium bg-slate-50/50 border border-slate-100 rounded-xl p-4 mb-4">
                    <p class="font-bold text-[9px] uppercase text-gray-400 tracking-wider mb-1">Catatan Kegiatan / Bahasan Anda:</p>
                    {!! nl2br(e($log->kegiatan)) !!}
                </div>

                {{-- DOKUMEN LAMPIRAN JIKA ADA --}}
                @if($log->file_bab)
                    <div class="mb-4">
                        <a href="{{ asset('storage/' . $log->file_bab) }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-700 font-bold text-xs bg-blue-50/50 px-2.5 py-1.5 rounded-lg border border-blue-100/50 transition">
                            <i class="fa-solid fa-file-lines text-[10px]"></i> Lihat File Lampiran Anda
                        </a>
                    </div>
                @endif

                {{-- HASIL CATATAN FEEDBACK DARI DOSEN PEMBIMBING --}}
                @if($log->catatan_dosen)
                    <div class="text-xs font-medium border-l-4 @if($log->status == 'ditolak') border-rose-500 bg-rose-50/30 @elseif($log->status == 'revisi') border-orange-500 bg-orange-50/30 @else border-emerald-500 bg-emerald-50/30 @endif pl-4 py-2.5 rounded-r-xl">
                        <p class="font-bold text-[9px] uppercase @if($log->status == 'ditolak') text-rose-600 @elseif($log->status == 'revisi') text-orange-600 @else text-emerald-600 @endif tracking-wider mb-0.5">
                            Feedback / Alasan Pembimbing:
                        </p>
                        <p class="text-gray-800 italic font-medium">
                            "{!! nl2br(e($log->catatan_dosen)) !!}"
                        </p>
                    </div>
                @endif

            </div>
        @empty
            <div class="w-full bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400 text-xs italic">
                <i class="fa-solid fa-folder-open text-gray-300 text-2xl mb-2 block"></i>
                Belum ada riwayat pengajuan logbook bimbingan skripsi.
            </div>
        @endforelse

        {{-- PAGINATION LINK --}}
        <div class="mt-4">
            {{ $logbooks->links() }}
        </div>
    </div>
</x-app-layout>
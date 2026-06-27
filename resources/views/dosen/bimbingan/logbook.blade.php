<x-app-layout>
    <x-slot name="title">Logbook - {{ $mahasiswa->user->name }}</x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Logbook Bimbingan</h3>
            <p class="text-xs text-gray-400 mt-0.5">Mahasiswa: <span class="font-bold text-gray-700">{{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})</span></p>
        </div>
        @if(auth()->user()->role === 'kaprodi')
            <a href="{{ route('kaprodi.bimbingan.index') }}" class="px-3 py-2 bg-white text-gray-600 border border-gray-200 text-xs font-bold rounded-xl hover:bg-gray-50 transition">
                Kembali
            </a>
        @else
            <a href="{{ route('dosen.bimbingan.index') }}" class="px-3 py-2 bg-white text-gray-600 border border-gray-200 text-xs font-bold rounded-xl hover:bg-gray-50 transition">
                Kembali
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-xs text-emerald-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($logbooks as $log)
        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            
            {{-- HEADER CARD: HIGHLIGHT TANGGAL DAN STATUS --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 border-b border-gray-50 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col items-center justify-center bg-blue-50 border border-blue-100 rounded-xl p-2.5 min-w-[70px] text-center shadow-sm">
                        <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('M') }}</span>
                        <span class="text-xl font-extrabold text-blue-900 leading-none my-0.5">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('d') }}</span>
                        <span class="text-[9px] font-medium text-blue-600/80">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('Y') }}</span>
                    </div>

                    <div>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-extrabold px-2 py-0.5 rounded-md uppercase tracking-wide border border-slate-200/60 inline-block mb-1">
                            {{ $log->bab }}
                        </span>
                        <p class="text-xs font-bold text-gray-800 flex items-center gap-1">
                            <i class="fa-solid fa-clock text-blue-500 text-[10px]"></i> Rencana Pertemuan: 
                            <span class="text-blue-700">{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('l, d F Y') }}</span>
                        </p>
                    </div>
                </div>

                <div>
                    {{-- Badge Status Dinamis --}}
                    @if($log->status == 'pending')
                        <span class="bg-amber-50 text-amber-600 border border-amber-100 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">⏳ Request Booking</span>
                    @elseif($log->status == 'disetujui')
                        <span class="bg-blue-600 text-white border border-blue-700 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm shadow-blue-100">📅 Jadwal Disetujui</span>
                    @elseif($log->status == 'ditolak')
                        <span class="bg-neutral-100 text-neutral-600 border border-neutral-200 text-[10px] font-bold px-2.5 py-1 rounded-full">❌ Booking Ditolak</span>
                    @elseif($log->status == 'revisi')
                        <span class="bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">🔴 Perlu Revisi</span>
                    @else
                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">🟢 Progres ACC</span>
                    @endif
                </div>
            </div>

            <div class="text-xs text-gray-700 font-medium bg-slate-50/50 rounded-xl p-4 mb-3">
                <p class="font-bold text-[10px] uppercase text-gray-400 tracking-wider mb-1">Rencana Kegiatan / Bahasan Mahasiswa:</p>
                {!! nl2br(e($log->kegiatan)) !!}
            </div>

            <div class="mb-4">
                @if($log->file_bab)
                    <a href="{{ asset('storage/' . $log->file_bab) }}" target="_blank" 
                       class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 border border-blue-100 text-blue-600 hover:bg-blue-100 text-[11px] font-bold rounded-xl transition">
                        <i class="fa-solid fa-download text-[10px]"></i> 📥 Download Berkas {{ $log->bab }}
                    </a>
                @else
                    <span class="text-[11px] text-gray-400 italic font-medium">Tidak ada file berkas yang disertakan.</span>
                @endif
            </div>

            {{-- FORM AKSI DINAMIS BERDASARKAN STATUS --}}
            @if($log->status == 'pending')
                {{-- FASE 1: Dosen merespon booking jadwal pertemuan --}}
                <form action="{{ route(auth()->user()->role . '.bimbingan.logbook.review', $log->id) }}" method="POST" class="mt-4 border-t border-gray-100 pt-4">
                    @csrf
                    @method('PUT')
                    <p class="text-[11px] text-gray-500 mb-3">Konfirmasi kesediaan Anda untuk bimbingan pada rencana tanggal di atas:</p>
                    <div class="flex justify-end gap-2">
                        <button type="submit" name="status" value="ditolak" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold rounded-lg transition">
                            Tolak Jadwal
                        </button>
                        <button type="submit" name="status" value="disetujui" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold rounded-lg transition shadow-md">
                            Setujui Jadwal
                        </button>
                    </div>
                </form>

            @elseif($log->status == 'disetujui')
                {{-- FASE 2: Pertemuan selesai, saatnya memberikan review isi bab --}}
                <form action="{{ route(auth()->user()->role . '.bimbingan.logbook.review', $log->id) }}" method="POST" class="mt-4 border-t border-gray-100 pt-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-1.5">Catatan Hasil Review Bimbingan</label>
                        <textarea name="catatan_dosen" rows="2" placeholder="Tulis instruksi revisi atau poin evaluasi penting setelah pertemuan di sini..." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 transition text-gray-800 font-medium" required></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="submit" name="status" value="revisi" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-[11px] font-bold rounded-lg transition">
                            Minta Revisi Bab
                        </button>
                        <button type="submit" name="status" value="acc" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-lg transition shadow-md">
                            ACC Bab & Selesai
                        </button>
                    </div>
                </form>

            @else
                {{-- FASE 3: Bimbingan Selesai (ACC / Revisi / Ditolak) --}}
                <div class="text-xs font-medium border-l-4 border-blue-500 pl-4 py-1 mt-2">
                    <p class="font-bold text-[10px] uppercase text-blue-500 tracking-wider mb-0.5">Catatan/Feedback Anda:</p>
                    <p class="text-gray-800 italic">
                        "{!! $log->catatan_dosen ? nl2br(e($log->catatan_dosen)) : 'Jadwal pertemuan ditolak/dibatalkan.' !!}"
                    </p>
                </div>
            @endif
        </div>
        @empty
        <div class="w-full bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400 text-xs">
            Mahasiswa ini belum pernah mengisi logbook bimbingan.
        </div>
        @endforelse
    </div>
</x-app-layout>
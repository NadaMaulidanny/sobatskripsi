<x-app-layout>
    <x-slot name="title">Logbook Bimbingan Skripsi</x-slot>

    <div class="flex justify-between items-center mb-6 w-full">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Logbook Anda</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pantau riwayat asistensi bimbingan dan feedback catatan dari dosen</p>
        </div>
        <a href="{{ route('mahasiswa.logbook.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-[10px]"></i> Isi Logbook Baru
        </a>
    </div>

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-xs font-medium text-emerald-600">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="w-full bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-xs font-medium text-rose-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($logbooks as $log)
        <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-start border-b border-gray-50 pb-3 mb-3">
                <div>
                    <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase border border-blue-100">{{ $log->bab }}</span>
                    <p class="text-[11px] text-gray-400 mt-1">Dosen: <span class="font-bold text-gray-600">{{ $log->dosen->user->name }}</span> | Tanggal: {{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    @if($log->status == 'pending')
                        <span class="bg-amber-50 text-amber-600 border border-amber-100 text-[10px] font-bold px-2 py-1 rounded-full">⏳ Menunggu Review</span>
                    @elseif($log->status == 'revisi')
                        <span class="bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-bold px-2 py-1 rounded-full">🔴 Perlu Revisi</span>
                    @else
                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-bold px-2 py-1 rounded-full">🟢 ACC</span>
                    @endif
                </div>
            </div>

            <div class="text-xs text-gray-700 font-medium bg-slate-50/50 rounded-xl p-4">
                <p class="font-bold text-[10px] uppercase text-gray-400 tracking-wider mb-1">Uraian Progres yang Anda Laporkan:</p>
                {!! nl2br(e($log->kegiatan)) !!}
            </div>

            @if($log->status != 'pending')
                <div class="text-xs font-medium border-l-4 border-amber-500 pl-4 py-1 mt-4">
                    <p class="font-bold text-[10px] uppercase text-amber-600 tracking-wider mb-0.5">Catatan/Arahan Dosen:</p>
                    <p class="text-gray-800 italic">"{!! nl2br(e($log->catatan_dosen)) !!}"</p>
                </div>
            @endif
        </div>
        @empty
        <div class="w-full bg-white rounded-2xl border border-gray-100 p-12 text-center text-gray-400 text-xs">
            Anda belum pernah mengisi logbook bimbingan.
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">{{ $logbooks->links() }}</div>
</x-app-layout>
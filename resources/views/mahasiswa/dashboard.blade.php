<x-app-layout>
     <x-slot name="title">Dashboard Mahasiswa - Portal Skripsi</x-slot>

    
            @if($pengajuanTerakhir)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6 w-full">
                    
                    {{-- Kondisi: DISETUJUI --}}
                    @if($pengajuanTerakhir->status === 'disetujui')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-emerald-500">
                            <div>
                                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Status Judul</p>
                                <h4 class="text-base font-bold text-gray-900 mt-1">{{ ucfirst($pengajuanTerakhir->status) }}</h4>
                                <p class="text-[10px] text-emerald-600 font-medium mt-1 flex items-center">
                                    <i class="fa-regular fa-clock mr-1"></i> Sudah disetujui kaprodi
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-file-invoice text-sm"></i>
                        </div>

                    {{-- Kondisi: DITOLAK --}}
                    @elseif($pengajuanTerakhir->status === 'Ditolak' || $pengajuanTerakhir->status === 'ditolak')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-red-500">
                            <div>
                                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Status Judul</p>
                                <h4 class="text-base font-bold text-gray-900 mt-1">{{ ucfirst($pengajuanTerakhir->status) }}</h4>
                                <p class="text-[10px] text-red-600 font-medium mt-1 flex items-center">
                                    <i class="fa-regular fa-clock mr-1"></i> Silakan ajukan judul baru
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                                <i class="fa-solid fa-file-invoice text-sm"></i>
                        </div>

                    {{-- Kondisi: MENUNGGU --}}
                    @elseif($pengajuanTerakhir->status === 'menunggu')
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-amber-500">
                            <div>
                                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Status Judul</p>
                                <h4 class="text-base font-bold text-gray-900 mt-1">{{ ucfirst($pengajuanTerakhir->status) }}</h4>
                                <p class="text-[10px] text-amber-500 font-medium mt-1 flex items-center">
                                    <i class="fa-regular fa-clock mr-1"></i> Menunggu persetujuan kaprodi
                                </p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                                <i class="fa-solid fa-file-invoice text-sm"></i>
                        </div>
                    @endif

                </div>
            @else
                {{-- Kondisi: BELUM PERNAH MENGAJUKAN (Null) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6 w-full">
                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-gray-300">
                        <div>
                            <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Status Judul</p>
                            <h4 class="text-base font-bold text-gray-900 mt-1">Belum Ada</h4>
                            <p class="text-[10px] text-gray-500 font-medium mt-1 flex items-center">
                                <i class="fa-regular fa-clock mr-1"></i> Silakan ajukan judul baru
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="fa-solid fa-file-invoice text-sm"></i>
                    </div>
                </div>
            @endif

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-blue-600">
            <div>
                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Pembimbing Utama</p>
                @if($pengajuanTerakhir && $pengajuanTerakhir->pembimbingDosens->isNotEmpty())
                    <h4 class="text-xs font-bold text-gray-800 mt-2 line-clamp-1">{{ $pengajuanTerakhir->pembimbingDosens->first()->user->name }}</h4>
                    <p class="text-[10px] text-gray-400 font-medium mt-1">Dosen Pembimbing</p>
                @else
                    <h4 class="text-xs font-bold text-gray-800 mt-2 line-clamp-1">Belum Di-plot</h4>
                    <p class="text-[10px] text-gray-400 font-medium mt-1">Menunggu judul di-ACC</p>
                @endif
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-graduation-cap text-sm"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-gray-600">
            <div>
                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Pembimbing Pendamping</p>
                @if($pengajuanTerakhir && $pengajuanTerakhir->pembimbingDosens->isNotEmpty())
                    <h4 class="text-xs font-bold text-gray-800 mt-2 line-clamp-1">{{ $pengajuanTerakhir->pembimbingDosens->last()->user->name }}</h4>
                    <p class="text-[10px] text-gray-400 font-medium mt-1">Dosen Pembimbing</p>
                @else
                    <h4 class="text-xs font-bold text-gray-800 mt-2 line-clamp-1">Belum Di-plot</h4>
                    <p class="text-[10px] text-gray-400 font-medium mt-1">Menunggu judul di-ACC</p>
                @endif
            </div>
            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-600">
                <i class="fa-solid fa-user-tie text-sm"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between border-l-4 border-l-purple-600">
            <div>
                <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Total Usulan</p>
                <h4 class="text-xs font-bold text-purple-800 mt-2 line-clamp-1">{{ $pengajuans->count() }}</h4>
                <p class="text-[10px] text-gray-400 font-medium mt-1">Judul yang diajukan</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                <i class="fa-solid fa-folder-open text-sm"></i>
            </div>
        </div>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="max-w-2xl space-y-2">
            <div class="inline-flex items-center bg-blue-50 border border-blue-100 text-blue-600 rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider">
                <i class="fa-solid fa-bullhorn mr-1.5"></i> Langkah Selanjutnya
            </div>
            <h4 class="text-lg font-bold text-gray-900 tracking-tight">Segera Ajukan Draf Judul & Abstrak Riset Anda</h4>
            <p class="text-xs text-gray-400 leading-relaxed">
                Sistem mendeteksi Anda belum mengajukan atau memiliki judul skripsi yang disetujui. Silakan persiapkan judul utama, opsi judul cadangan, serta deskripsi abstrak untuk ditinjau oleh Kepala Program Studi.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ asset('dokumen/pedoman_judul_ta.pdf') }}" target="_blank" class="flex-1 md:flex-none border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold text-sm px-4 py-3 rounded-xl transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-book text-gray-400"></i> Pedoman Judul
            </a>
        </div>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h4 class="text-sm font-bold text-gray-900">Riwayat Pengajuan Judul Anda</h4>
                <p class="text-[11px] text-gray-400 mt-0.5">Daftar usulan topik tugas akhir yang pernah Anda ajukan</p>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6">Judul Usulan</th>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4">Dosen Pembimbing Diusulkan</th>
                        <th class="p-4 pr-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse ($pengajuans as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 pl-6 max-w-sm">
                                <p class="font-semibold text-gray-900 truncate">{{ $item->judul }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5 truncate">Abstrak: {{ Str::limit($item->deskripsi, 50) }}</p>
                            </td>
                            <td class="p-4 font-medium text-gray-500">
                                {{ date('d M Y', strtotime($item->created_at)) }}
                            </td>
                            <td class="p-4 font-medium text-gray-700">
                                {{ $item->pembimbingDosens->pluck('user.name')->join(', ') }}
                            </td>
                            <td class="p-4 pr-6 text-center">
                                @if($item->status === 'disetujui')
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-semibold px-2.5 py-1 rounded-full text-[10px]">
                                        {{ $item->status }}
                                    </span>
                                @elseif($item->status === 'ditolak')
                                    <span class="bg-red-50 text-red-600 border border-red-100 font-semibold px-2.5 py-1 rounded-full text-[10px]">
                                        {{ $item->status }}
                                    </span>
                                @elseif($item->status === 'menunggu')
                                    <span class="bg-amber-50 text-amber-600 border border-amber-100 font-semibold px-2.5 py-1 rounded-full text-[10px]">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-magnifying-glass text-lg block mb-1.5 text-gray-300"></i>
                                Tidak ada data pengajuan judul skripsi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
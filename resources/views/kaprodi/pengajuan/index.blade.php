<x-app-layout>
    <x-slot name="title">Daftar Pengajuan Judul - Dashboard Kaprodi</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Tinjauan Pengajuan Judul Skripsi</h3>
        <p class="text-xs text-gray-400 mt-0.5">Validasi usulan topik riset dan alokasi request dosen pembimbing mahasiswa</p>
    </div>

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-xs font-medium text-emerald-600">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6">Mahasiswa</th>
                        <th class="p-4">Usulan Judul</th>
                        <th class="p-4">Bidang Studi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($pengajuans as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6">
                            <p class="font-bold text-gray-900">{{ $item->mahasiswa->user->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">NIM: {{ $item->mahasiswa->nim ?? '-' }}</p>
                        </td>
                        <td class="p-4 max-w-xs">
                            <p class="font-semibold text-gray-800 truncate" title="{{ $item->judul }}">{{ $item->judul }}</p>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-semibold text-[10px] border border-blue-100">
                                {{ $item->bidangStudi->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($item->status === 'menunggu')
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 font-bold px-2.5 py-1 rounded-full text-[10px]">Menunggu</span>
                            @elseif($item->status === 'disetujui')
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold px-2.5 py-1 rounded-full text-[10px]">Disetujui</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 border border-rose-100 font-bold px-2.5 py-1 rounded-full text-[10px]">Ditolak</span>
                            @endif
                        </td>
                        <td class="p-4 text-center pr-6">
                            <a href="{{ route('kaprodi.pengajuan.show', $item->id) }}" class="px-3 py-1.5 {{ $item->status === 'menunggu' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-file-signature mr-1"></i> Periksa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-gray-400 text-xs">Belum ada pengajuan judul masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
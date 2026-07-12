<x-app-layout>
    <x-slot name="title">Dashboard Dosen - Portal Skripsi</x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-blue-600 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Mahasiswa</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalMahasiswa ?? '0' }}</h3>
                </div>
                <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-users"></i></div>
            </div>
            <p class="text-[11px] text-blue-600 font-semibold mt-4"><span class="text-gray-400 font-normal">Total Mahasiswa yang Dibimbing</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-indigo-600 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Pengajuan</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $totalBidang ?? '0' }}</h3>
                </div>
                <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-id-card"></i></div>
            </div>
            <p class="text-[11px] text-gray-400 font-medium mt-4">Pada Bidang Studi {{ $pengajuans->first()->bidangStudi->nama ?? 'Tidak Diketahui' }}</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-amber-500 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Pending</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{$totalPending ?? '0'}}</h3>
                </div>
                <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-file-lines"></i></div>
            </div>
            <p class="text-[11px] text-red-500 font-semibold mt-4">{{$totalPending ?? '0'}}<span class="text-gray-400 font-normal"> butuh review</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between w-full border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between w-full">
                <div>
                    <p class="text-[11px] font-medium text-gray-400 uppercase tracking-wider">Kuota Bimbingan</p>
                    <h4 class="text-2xl font-bold text-gray-900 mt-1">
                        {{ $totalMahasiswaBimbingan }} <span class="text-xs text-gray-400 font-normal">/ {{ $kuotaMaksimal }} Mhs</span>
                    </h4>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
            </div>
            
            {{-- Progress Bar --}}
            <div class="w-full mt-4">
                <div class="flex justify-between items-center mb-1 text-[10px] font-semibold text-gray-500">
                    <span>Kapasitas Terisi</span>
                    <span>{{ number_format($persentaseKuota, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" 
                        style="width: {{ $persentaseKuota }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6">NIM</th>
                        <th class="p-4 pl-6">Nama</th>
                        <th class="p-4">Judul Usulan</th>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4 pr-6 text-center">Status</th>
                        <th class="p-4 pr-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse ($pengajuans as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            
                            <td class="p-4 font-medium text-gray-500">
                                {{ $item->mahasiswa->nim }}
                            </td>
                            
                            <td class="p-4 font-medium text-gray-500">
                                {{ $item->mahasiswa->user->name }}
                            </td>
                            <td class="p-4 pl-6 max-w-sm">
                                <p class="font-semibold text-gray-900 truncate">{{ $item->judul }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5 truncate">Abstrak: {{ Str::limit($item->deskripsi, 50) }}</p>
                            </td>
                            <td class="p-4 font-medium text-gray-500">
                                {{ date('d M Y', strtotime($item->created_at)) }}
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
                            <td class="p-4 text-center pr-6">
                                <a href="#" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                    <i class="fa-solid fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-magnifying-glass text-lg block mb-1.5 text-gray-300"></i>
                                Tidak ada data pengajuan judul skripsi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
</x-app-layout>
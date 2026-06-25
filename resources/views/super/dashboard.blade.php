<x-app-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    <div class="mb-6 w-full">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Dashboard Super Admin</h3>
        <p class="text-xs text-gray-400 mt-0.5">Ringkasan data master dan monitoring keaktifan sistem skripsi</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl text-lg">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total Mahasiswa</p>
                <h4 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalMahasiswa }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl text-lg">
                <i class="fa-solid fa-user-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Mhs Terverifikasi</p>
                <h4 class="text-xl font-bold text-gray-900 mt-0.5">{{ $mhsAktif }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl text-lg">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Dosen Pembimbing</p>
                <h4 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalDosen }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl text-lg">
                <i class="fa-solid fa-book-bookmark"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total Usulan Judul</p>
                <h4 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalPengajuan }}</h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full">
    
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-sm text-gray-800 mb-4">Mahasiswa Baru Terdaftar</h4>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                            <th class="p-3 pl-4">Nama Mahasiswa</th>
                            <th class="p-3">NIM</th>
                            <th class="p-3 text-center pr-4">Status Akun</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs text-gray-600 divide-y divide-gray-50">
                        @forelse($mahasiswaTerbaru as $mhs)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3 pl-4 font-semibold text-gray-900">{{ $mhs->user->name ?? 'N/A' }}</td>
                            <td class="p-3 text-gray-500">{{ $mhs->nim }}</td>
                            <td class="p-3 text-center pr-4">
                                @if($mhs->user && $mhs->user->is_verified)
                                    <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold px-2 py-0.5 rounded-full text-[9px]">🟢 Aktif</span>
                                @else
                                    <span class="bg-rose-50 text-rose-600 border border-rose-100 font-bold px-2 py-0.5 rounded-full text-[9px]">🔴 Pasif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-400 text-xs">Belum ada mahasiswa terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-sm text-gray-800 mb-4">Monitoring Kuota Dosen</h4>
            
            <div class="space-y-4">
                @forelse($dosenKuotaKritis as $dsn)
                <div>
                    <div class="flex justify-between items-center text-xs mb-1">
                        <span class="font-semibold text-gray-800">{{ $dsn->user->name ?? 'N/A' }}</span>
                        <span class="text-gray-400 font-medium">Sisa Kuota: <b class="text-blue-600">{{ $dsn->kuota }}</b></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        @php
                            // Asumsi kuota maksimal dosen adalah 10, silakan sesuaikan angkanya
                            $maxKuota = 10; 
                            $persentase = ($dsn->kuota / $maxKuota) * 100;
                            // Jaga agar tidak error jika kuota minus atau lebih dari max
                            $persentase = max(0, min(100, $persentase)); 
                        @endphp
                        <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $persentase }}%"></div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-400 text-xs py-6">Belum ada data dosen.</p>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
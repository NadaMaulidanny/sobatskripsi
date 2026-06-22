<x-app-layout>
    <x-slot name="title">Dashboard Admin - Portal Skripsi</x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-blue-600 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Mahasiswa</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $total_mahasiswa ?? '0' }}</h3>
                </div>
                <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-users"></i></div>
            </div>
            <p class="text-[11px] text-blue-600 font-semibold mt-4"><i class="fa-solid fa-arrow-up mr-1"></i> +12% <span class="text-gray-400 font-normal">dari bulan lalu</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-indigo-600 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Dosen</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $total_dosen ?? '0' }}</h3>
                </div>
                <div class="bg-indigo-50 text-indigo-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-id-card"></i></div>
            </div>
            <p class="text-[11px] text-gray-400 font-medium mt-4">Aktif Mengajar</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-amber-500 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Total Pengajuan</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">452</h3>
                </div>
                <div class="bg-amber-50 text-amber-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-file-lines"></i></div>
            </div>
            <p class="text-[11px] text-red-500 font-semibold mt-4">24 Pending <span class="text-gray-400 font-normal">butuh review</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between relative overflow-hidden before:absolute before:w-1 before:h-full before:bg-emerald-500 before:left-0 before:top-0">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-400">Tingkat Approval</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">78.4%</h3>
                </div>
                <div class="bg-emerald-50 text-emerald-600 p-2.5 rounded-xl text-xs"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-4">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: 78%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols gap-5">
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between min-h-[300px]">
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-sm text-gray-900">Tren Pengajuan Judul</h4>
                    <p class="text-xs text-gray-400 mt-0.5">Data statistik 6 bulan terakhir</p>
                </div>
            </div>
            <div class="flex items-end justify-between h-40 pt-6 px-4">
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-100 rounded-md h-8"></div><span class="text-[10px] text-gray-400 font-medium">Jan</span></div>
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-100 rounded-md h-12"></div><span class="text-[10px] text-gray-400 font-medium">Feb</span></div>
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-100 rounded-md h-20"></div><span class="text-[10px] text-gray-400 font-medium">Mar</span></div>
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-100 rounded-md h-16"></div><span class="text-[10px] text-gray-400 font-medium">Apr</span></div>
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-100 rounded-md h-28"></div><span class="text-[10px] text-gray-400 font-medium">Mei</span></div>
                <div class="flex flex-col items-center space-y-2 w-full"><div class="w-6 bg-blue-600 rounded-md h-36"></div><span class="text-[10px] text-gray-400 font-medium">Jun</span></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 flex justify-between items-center border-b border-gray-100">
            <div>
                <h3 class="font-bold text-sm text-gray-900">Manajemen Mahasiswa & Dosen</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar pengguna terdaftar di sistem</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">Tambah User</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[11px] uppercase font-bold tracking-wider">
                        <th class="p-4 pl-6">Nama Lengkap</th>
                        <th class="p-4">NIM/NIDN</th>
                        <th class="p-4">Peran</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6 flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-[10px]">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                        </td>
                        <td class="p-4 font-medium text-gray-500">{{ $user->role === 'dosen' ? '198506121003' : '2010412034' }}</td>
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-semibold text-[10px]">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="p-4 text-center space-x-2 text-gray-400">
                            <button class="hover:text-blue-600"><i class="fa-solid fa-pen text-xs"></i></button>
                            <button class="hover:text-red-600"><i class="fa-solid fa-trash text-xs"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
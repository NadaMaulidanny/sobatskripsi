<x-app-layout>
    <x-slot name="title">Dashboard Dosen - Portal Skripsi</x-slot>

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

</x-app-layout>
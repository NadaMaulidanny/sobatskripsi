<header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Sistem Pengajuan Judul Skripsi</h2>
    </div>
    
    <div class="flex items-center space-x-6">

        <div class="flex items-center space-x-3 pl-4 border-l border-gray-200">
            <div class="text-right">
                <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-[10px] font-medium text-gray-400">{{ Auth::user()->role }}</p>
            </div>
            <div class="w-9 h-9 bg-gradient-to-tr from-blue-500 to-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </div>
</header>
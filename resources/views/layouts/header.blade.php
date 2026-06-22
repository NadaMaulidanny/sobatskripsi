<header class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Sistem Pengajuan Judul Skripsi</h2>
    </div>
    
    <div class="flex items-center space-x-6">
        <div class="relative w-64">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" placeholder="Cari data skripsi..." class="w-full bg-[#f1f5f9] border-0 pl-9 pr-4 py-2 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white transition text-gray-700">
        </div>

        <div class="flex items-center space-x-4 text-gray-400 text-sm">
            <button class="hover:text-gray-600"><i class="fa-regular fa-bell"></i></button>
            <button class="hover:text-gray-600"><i class="fa-regular fa-circle-question"></i></button>
            <button class="hover:text-gray-600"><i class="fa-solid fa-grip"></i></button>
        </div>

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
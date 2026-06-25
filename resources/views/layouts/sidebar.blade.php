<div class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between p-5 shrink-0 z-20">
    <div>
        <div class="flex items-center space-x-3 my-3 px-2">
            <div class="bg-blue-600 text-white p-2 rounded-xl shadow-md shadow-blue-200">
                <i class="fa-solid fa-graduation-cap text-lg"></i>
            </div>
            <div>
                <h1 class="text-blue-600 font-bold text-base leading-tight">Portal Skripsi</h1>
                <p class="text-[11px] text-gray-400 font-medium">Akademik & Riset</p>
            </div>
        </div>

        <nav class="mt-8 space-y-1">
            
            @if(auth()->user()->role === 'kaprodi')
                <a href="{{ route('kaprodi.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                   {{ request()->routeIs('kaprodi.dashboard') ? 'bg-blue-600 text-white shadow-sm shadow-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-table-columns w-5 text-center {{ request()->routeIs('kaprodi.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span>Dashboard Kaprodi</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('kaprodi.dosen*') || request()->routeIs('kaprodi.daftar-mhs*') || request()->routeIs('kaprodi.mahasiswa*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition text-gray-500 hover:bg-gray-50 hover:text-gray-900">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-database w-5 text-center text-gray-400"></i>
                            <span>Manajemen Data</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="mt-1 pl-11 space-y-1" style="display: none;"> 
                        <a href="{{ route('kaprodi.dosen.index') }}" 
                           class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('kaprodi.dosen*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('kaprodi.dosen*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Dosen
                        </a>
                        <a href="{{ route('kaprodi.daftar-mhs') }}" 
                           class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('kaprodi.daftar-mhs*') || request()->routeIs('kaprodi.mahasiswa*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('kaprodi.daftar-mhs*') || request()->routeIs('kaprodi.mahasiswa*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Mahasiswa
                        </a>
                    </div>
                </div>

                <a href="{{ route('kaprodi.pengajuan.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                   {{ request()->routeIs('kaprodi.pengajuan.index') ? 'bg-blue-600 text-white shadow-sm shadow-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-file-signature w-5 text-center {{ request()->routeIs('kaprodi.pengajuan.index') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span>Pengajuan</span>
                </a>
            @endif


            @if(auth()->user()->role === 'mahasiswa')
                <a href="{{ route('mahasiswa.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                   {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-600 text-white shadow-sm shadow-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-table-columns w-5 text-center {{ request()->routeIs('mahasiswa.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span>Dashboard Mhs</span>
                </a>

                <a href="{{ route('mahasiswa.pengajuan.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                   {{ request()->routeIs('mahasiswa.pengajuan*') ? 'bg-blue-600 text-white shadow-sm shadow-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-paper-plane w-5 text-center {{ request()->routeIs('mahasiswa.pengajuan*') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span>Pengajuan Judul</span>
                </a>

                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition text-gray-400 cursor-not-allowed">
                    <i class="fa-solid fa-book w-5 text-center text-gray-300"></i>
                    <span>Logbook Bimbingan</span>
                </a>
            @endif

            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('super_admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition
                   {{ request()->routeIs('super_admin.dashboard') ? 'bg-blue-600 text-white shadow-sm shadow-blue-100' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fa-solid fa-table-columns w-5 text-center {{ request()->routeIs('super_admin.dashboard') ? 'text-white' : 'text-gray-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('admin.prodi*') || request()->routeIs('admin.bidang-studi*') || request()->routeIs('admin.dosen*') || request()->routeIs('admin.mahasiswa*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-semibold transition text-gray-500 hover:bg-gray-50 hover:text-gray-900">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-database w-5 text-center text-gray-400"></i>
                            <span>Manajemen Data</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse class="mt-1 pl-11 space-y-1" style="display: none;"> 
                        
                        <a href="{{ route('super_admin.prodi.index') }}" 
                        class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('super_admin.prodi*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('super_admin.prodi*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Prodi
                        </a>

                        <a href="{{ route('super_admin.bidang-studi.index') }}" 
                        class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('super_admin.bidang-studi*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('super_admin.bidang-studi*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Bidang Studi
                        </a>

                        <a href="#" 
                        class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('super_admin.dosen*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('super_admin.dosen*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Dosen
                        </a>

                        <a href="#" 
                        class="block py-2 px-3 rounded-lg text-xs font-medium transition {{ request()->routeIs('super_admin.mahasiswa*') ? 'text-blue-600 font-bold bg-blue-50/50' : 'text-gray-400 hover:text-gray-900' }}">
                            <i class="fa-solid fa-circle text-[6px] mr-2 {{ request()->routeIs('super_admin.mahasiswa*') ? 'text-blue-600' : 'text-transparent' }}"></i>
                            Data Mahasiswa
                        </a>

                    </div>
                </div>
            @endif

        </nav>
    </div>

    <div class="space-y-3 pt-4 border-t border-gray-100">
        <a href="#" class="flex items-center space-x-3 text-gray-500 hover:bg-gray-50 px-4 py-2.5 rounded-xl text-sm font-medium transition">
            <i class="fa-solid fa-gear text-gray-400"></i>
            <span>Pengaturan</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 text-red-500 hover:bg-red-50 px-4 py-2.5 rounded-xl text-sm font-medium transition text-left">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</div>
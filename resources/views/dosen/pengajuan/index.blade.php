<x-app-layout>
    <x-slot name="title">Riwayat Pengajuan - Portal Skripsi</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Riwayat Pengajuan Judul</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pantau terus status perkembangan judul tugas akhir Mahasiswa</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('info_proses'))
        <div class="w-full bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex items-start space-x-3 shadow-sm">
            <div class="text-amber-500 mt-0.5 flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation text-base"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-amber-800 uppercase tracking-wide">Akses Pengajuan Dikunci</h5>
                <p class="text-xs text-amber-600 mt-0.5 font-medium leading-relaxed">
                    {{ session('info_proses') }} Silakan pantau perkembangan review berkas Anda pada tabel riwayat di bawah ini.
                </p>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 flex items-start space-x-3 shadow-sm">
            <div class="text-emerald-500 mt-0.5 flex-shrink-0">
                <i class="fa-solid fa-circle-check text-base"></i>
            </div>
            <div>
                <h5 class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Berhasil</h5>
                <p class="text-xs text-emerald-600 mt-0.5 font-medium">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    @endif

    <form action="{{ url()->current() }}" method="GET" class="mb-5 flex flex-col sm:flex-row gap-3 w-full">
        {{-- Input Search --}}
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari judul skripsi..." 
                class="w-full pl-9 pr-4 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 shadow-sm">
        </div>

        {{-- Filter Status --}}
        <div class="w-full sm:w-44">
            <select name="status" class="w-full px-3 py-2 text-xs bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 shadow-sm">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1">
                <i class="fa-solid fa-filter text-[10px]"></i> Filter
            </button>
            
            @if(request('search') || request('status'))
                <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold text-xs rounded-xl transition flex items-center justify-center">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- TABEL ANDA (Slightly adjusted colspan at @empty) --}}
    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        
                        <th class="p-4 pl-6">NIM</th>
                        <th class="p-4 pl-6">Nama Mahasiswa</th>
                        <th class="p-4 pl-6">Judul Skripsi</th>
                        <th class="p-4">Bidang Studi</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($pengajuans as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-semibold text-[10px] border border-blue-100">
                                {{ $item->mahasiswa->nim ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-semibold text-[10px] border border-blue-100">
                                {{ $item->mahasiswa->user->name ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4 pl-6 max-w-xs">
                            <p class="font-bold text-gray-900 truncate" title="{{ $item->judul }}">{{ $item->judul }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Klik detail untuk melihat abstrak</p>
                        </td>

                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-semibold text-[10px] border border-blue-100">
                                {{ $item->bidangStudi->nama ?? '-' }}
                            </span>
                        </td>

                        <td class="p-4 text-center">
                            @if($item->status === 'menunggu')
                                <span class="bg-amber-50 text-amber-600 border border-amber-100 font-bold px-2.5 py-1 rounded-full text-[10px] inline-block">
                                    Menunggu
                                </span>
                            @elseif($item->status === 'disetujui')
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold px-2.5 py-1 rounded-full text-[10px] inline-block">
                                    Disetujui
                                </span>
                            @else
                                <span class="bg-rose-50 text-rose-600 border border-rose-100 font-bold px-2.5 py-1 rounded-full text-[10px] inline-block">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="p-4 text-center pr-6">
                            <a href="{{ route('dosen.pengajuan.show', $item->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        {{-- Diubah menjadi colspan="6" agar sesuai jumlah th --}}
                        <td colspan="6" class="p-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                                <p class="text-xs">
                                    @if(request('search') || request('status'))
                                        Data pengajuan tidak ditemukan berdasarkan filter pencarian.
                                    @else
                                        Anda belum pernah mengajukan usulan judul skripsi.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    
</x-app-layout>
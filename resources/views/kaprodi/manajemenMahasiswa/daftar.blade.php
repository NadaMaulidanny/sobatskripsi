<x-app-layout>
    <x-slot name="title">Daftar Mahasiswa - Portal Skripsi</x-slot>

    @if(session('success'))
    <div class="w-full bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl text-xs font-semibold flex items-center space-x-2 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if ($errors->any())
    <div class="w-full bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl text-xs font-semibold shadow-sm mb-4">
        <div class="flex items-center space-x-2 text-red-600 mb-2 font-bold uppercase tracking-wider">
            <i class="fa-solid fa-circle-exclamation text-sm"></i>
            <span>Pendaftaran Gagal</span>
        </div>
        <ul class="list-disc pl-5 space-y-1 text-gray-600 font-medium">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="w-full bg-red-50 border border-red-200 text-red-800 p-5 rounded-2xl text-xs font-semibold shadow-sm mb-4 flex items-center space-x-2">
        <i class="fa-solid fa-circle-exclamation text-red-500 text-sm"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif


    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 flex justify-between items-center border-b border-gray-100">
            <div>
                <h3 class="font-bold text-sm text-gray-900">Manajemen Mahasiswa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar mahasiswa terdaftar di sistem</p>
            </div>
            <a href="{{ route('kaprodi.mahasiswa.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition flex items-center">
                <i class="fa-solid fa-plus mr-1.5"></i> Tambah Mahasiswa
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-gray-100 text-gray-400 text-[11px] uppercase font-bold tracking-wider">
                        <th class="p-4 pl-6">Nama Lengkap</th>
                        <th class="p-4">NIM</th>
                        <th class="p-4">Program Studi</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($mahasiswas as $mhs)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6 flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-[10px]">
                                {{ strtoupper(substr($mhs->user->name ?? 'M', 0, 2)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-900">{{ $mhs->user->name ?? '-'}}</span>
                                <span class="text-[10px] text-gray-400">ID: #{{ $mhs->id }}</span>
                            </div>
                        </td>
                        
                        <td class="p-4 font-medium text-gray-500">{{ $mhs->nim }}</td>
                        
                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg font-semibold text-[10px]">
                                {{ $mhs->prodi->nama_prodi ?? '-' }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-500 font-medium">{{ $mhs->user->email ?? '-' }}</td>

                        <td class="p-4">
                            @if($mhs->user->is_verified)
                                <span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded font-medium text-[10px] border border-emerald-200">
                                    <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i>Aktif
                                </span>
                            @else
                                <span class="bg-rose-50 text-rose-600 px-2 py-0.5 rounded font-medium text-[10px] border border-rose-200">
                                    <i class="fa-solid fa-circle text-[6px] mr-1 align-middle"></i>Bukan Aktif
                                </span>
                            @endif
                        </td>
                        
                        @if(auth()->user()->role !== 'dosen')
                        <td class="p-4 text-center space-x-2 text-gray-400">
                            
                            {{-- AKSES KHUSUS KAPRODI (Tombol Verifikasi Akun) --}}
                            @if(auth()->user()->role === 'kaprodi')
                                @if(!$mhs->user->is_verified)
                                    <form action="{{ route('kaprodi.mahasiswa.verify', $mhs->id) }}" method="POST" class="inline-block mr-2">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 px-2 py-1 rounded font-semibold text-[10px] transition inline-flex items-center"
                                                onclick="return confirm('Verifikasi mahasiswa {{ $mhs->user->name }}?')">
                                            <i class="fa-solid fa-user-check mr-1"></i> Verifikasi
                                        </button>
                                    </form>
                                @else
                                    <span class="text-emerald-600 bg-emerald-50/50 px-2 py-1 rounded border border-transparent text-[10px] font-medium mr-2">
                                        <i class="fa-solid fa-check-double mr-1"></i> Terverifikasi
                                    </span>
                                @endif
                            @endif

                            {{-- AKSES ADMIN & KAPRODI (Edit & Delete) --}}
                            @if(in_array(auth()->user()->role, ['super_admin', 'kaprodi']))
                                {{-- Tombol Edit --}}
                                <a href="{{ route(auth()->user()->role . '.mahasiswa.edit', $mhs->id) }}" class="hover:text-blue-600 transition" title="Edit Data">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                
                                {{-- Tombol Delete --}}
                                <form action="{{ route(auth()->user()->role . '.mahasiswa.destroy', $mhs->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hover:text-red-600 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            @endif

                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'dosen' ? 5 : 6 }}" class="p-8 text-center text-gray-400 font-medium">
                            Belum ada data mahasiswa terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
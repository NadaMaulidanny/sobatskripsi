<x-app-layout>
    <x-slot name="title">Manajemen Mahasiswa - Admin</x-slot>

    <div class="flex justify-between items-center mb-6 w-full">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Data Induk Mahasiswa</h3>
            <p class="text-xs text-gray-400 mt-0.5">Kelola data login mahasiswa, NIM, prodi, dan pantau status verifikasi akun</p>
        </div>
        <a href="{{ route('super_admin.mahasiswa.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Mahasiswa
        </a>
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
                    <tr class="bg-slate-50/70 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6 w-16">No</th>
                        <th class="p-4">NIM</th>
                        <th class="p-4">Nama Mahasiswa</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Program Studi</th>
                        <th class="p-4 text-center">Status Akun</th>
                        <th class="p-4 text-center pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($mahasiswas as $mhs)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6 font-semibold text-gray-500">
                            {{ ($mahasiswas->currentPage() - 1) * $mahasiswas->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-4 text-gray-500 font-mono">{{ $mhs->nim }}</td>
                        <td class="p-4 font-bold text-gray-900">{{ $mhs->user->name ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-500">{{ $mhs->user->email ?? 'N/A' }}</td>
                        <td class="p-4 text-gray-500">{{ $mhs->prodi->nama_prodi ?? 'N/A' }}</td>
                        <td class="p-4 text-center">
                            @if($mhs->user && $mhs->user->is_verified)
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 font-bold px-2.5 py-1 rounded-full text-[9px]">🟢 Aktif</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 border border-rose-100 font-bold px-2.5 py-1 rounded-full text-[9px]">🔴 Pasif</span>
                            @endif
                        </td>
                        <td class="p-4 text-center pr-6 flex items-center justify-center gap-1">
                            <a href="{{ route('super_admin.mahasiswa.edit', $mhs->id) }}" class="px-2.5 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <form action="{{ route('super_admin.mahasiswa.destroy', $mhs->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-gray-400 text-xs">Data mahasiswa belum ada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $mahasiswas->links() }}</div>
</x-app-layout>
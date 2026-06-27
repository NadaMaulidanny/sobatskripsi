<x-app-layout>
    <x-slot name="title">Manajemen Dosen - Admin</x-slot>

    <div class="flex justify-between items-center mb-6 w-full">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Data Dosen Pembimbing</h3>
            <p class="text-xs text-gray-400 mt-0.5">Kelola data master dosen akun login, NIDN, dan alokasi kuota bimbingan</p>
        </div>
        <a href="{{ route('super_admin.dosen.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5">
            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Dosen
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
                        <th class="p-4">NIDN</th>
                        <th class="p-4">Nama Dosen</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Program Studi</th>
                        <th class="p-4 text-center">Kuota Sisa</th>
                        <th class="p-4 text-center pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($dosens as $dsn)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6 font-semibold text-gray-500">
                            {{ ($dosens->currentPage() - 1) * $dosens->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-4 text-gray-500 font-mono">{{ $dsn->nidn }}</td>
                        <td class="p-4 font-bold text-gray-900">
                            {{ $dsn->user->name ?? 'N/A' }}
                            @if($dsn->user && $dsn->user->role === 'kaprodi')
                                <span class="ml-1 bg-amber-50 text-amber-600 border border-amber-200 text-[9px] px-1.5 py-0.5 rounded-md font-bold">
                                    KAPRODI
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-500">{{ $dsn->user->email ?? 'N/A' }}</td>
                        <td class="p-4">
                            <span class="bg-slate-100 text-gray-600 border border-gray-200 px-2 py-0.5 font-medium rounded-md text-[10px]">
                                {{ $dsn->prodi->nama_prodi ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="p-4 text-center font-bold text-blue-600 bg-blue-50/20">{{ $dsn->kuota }}</td>
                        <td class="p-4 text-center pr-6 flex items-center justify-center gap-1">
                            <a href="{{ route('super_admin.dosen.edit', $dsn->id) }}" class="px-2.5 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                            </a>
                            <form action="{{ route('super_admin.dosen.destroy', $dsn->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun dosen ini? Seluruh riwayat bimbingannya akan terhapus.')">
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
                        <td colspan="7" class="p-12 text-center text-gray-400 text-xs">Data dosen pembimbing belum ada.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $dosens->links() }}</div>
</x-app-layout>
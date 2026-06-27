<x-app-layout>
    <x-slot name="title">Daftar Mahasiswa Bimbingan</x-slot>

    <div class="mb-6">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Mahasiswa Bimbingan Anda</h3>
        <p class="text-xs text-gray-400 mt-0.5">Daftar mahasiswa skripsi yang sedang Anda bimbing aktif</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-slate-50/70 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 pl-6 w-16">No</th>
                    <th class="p-4">NIM</th>
                    <th class="p-4">Nama Mahasiswa</th>
                    <th class="p-4">Program Studi</th>
                    <th class="p-4 text-center pr-6">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                @forelse($mahasiswas as $mhs)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="p-4 pl-6 font-semibold text-gray-500">{{ $loop->iteration }}</td>
                    <td class="p-4 font-mono text-gray-500">{{ $mhs->nim }}</td>
                    <td class="p-4 font-bold text-gray-900">{{ $mhs->user->name }}</td>
                    <td class="p-4 text-gray-500">{{ $mhs->prodi->nama_prodi ?? 'N/A' }}</td>
                    
                    <td class="p-4 text-center pr-6">
                        @if (auth()->user()->role === 'kaprodi')
                            <a href="{{ route('kaprodi.bimbingan.logbook', $mhs->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-book mr-1"></i> Lihat Logbook (Kaprodi)
                            </a>
                        @else
                            <a href="{{ route('dosen.bimbingan.logbook', $mhs->id) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-book mr-1"></i> Lihat Logbook
                            </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-12 text-center text-gray-400">Belum ada mahasiswa yang diplotting ke Anda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
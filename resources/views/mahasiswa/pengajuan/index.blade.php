<x-app-layout>
    <x-slot name="title">Riwayat Pengajuan - Portal Skripsi</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Riwayat Pengajuan Judul</h3>
            <p class="text-xs text-gray-400 mt-0.5">Pantau terus status perkembangan judul tugas akhir Anda</p>
        </div>

        @php
            // Cari apakah ada pengajuan yang masih berjalan (menunggu / disetujui)
            $pengajuanAktif = $pengajuans->whereIn('status', ['menunggu', 'disetujui'])->first();
        @endphp

        @if(!$pengajuanAktif)
            <a href="{{ route('mahasiswa.pengajuan.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Pengajuan
            </a>
        @else
            <button type="button" onclick="showLockedAlert('{{ $pengajuanAktif->judul }}', '{{ $pengajuanAktif->status }}')" 
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Tambah Pengajuan
            </button>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function showLockedAlert(judulSkripsi, status) {
        const statusText = status === 'menunggu' ? 'Sedang Ditinjau' : 'Sudah Disetujui';
        const statusColor = status === 'menunggu' ? '#eab308' : '#10b981'; // Kuning atau Hijau

        Swal.fire({
            title: '<span class="text-lg font-bold text-gray-800">Akses Pengajuan Terkunci</span>',
            html: `
                <div class="text-left bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-xs text-gray-600 space-y-2 mt-2">
                    <p>Anda belum dapat membuat pengajuan baru karena saat ini memiliki usulan judul yang sedang berjalan:</p>
                    <div class="p-2.5 bg-white border border-gray-100 rounded-lg shadow-sm">
                        <p class="font-bold text-gray-800">"${judulSkripsi}"</p>
                        <p class="text-[10px] font-semibold mt-1 flex items-center gap-1">
                            Status: <span style="color: ${statusColor}">● ${statusText}</span>
                        </p>
                    </div>
                    <p class="text-[11px] text-gray-400 italic pt-1">*) Jika usulan sebelumnya ditolak oleh program studi, tombol akan otomatis terbuka kembali.</p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#f59e0b',
            confirmButtonText: 'Paham, Kembali',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs px-5 py-2.5 font-bold'
            }
        });
    }
    </script>

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

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6">Judul Skripsi</th>
                        <th class="p-4">Bidang Studi</th>
                        <th class="p-4">Usulan Pembimbing 1</th>
                        <th class="p-4">Usulan Pembimbing 2</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($pengajuans as $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        
                        <td class="p-4 pl-6 max-w-xs">
                            <p class="font-bold text-gray-900 truncate" title="{{ $item->judul }}">{{ $item->judul }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">Klik detail untuk melihat abstrak</p>
                        </td>

                        <td class="p-4">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md font-semibold text-[10px] border border-blue-100">
                                {{ $item->bidangStudi->nama ?? '-' }}
                            </span>
                        </td>

                        @php
                            // Ambil data dosen pembimbing secara spesifik dari pivot status
                            $pmb1 = $item->pembimbingDosens->firstWhere('pivot.status', 'request1') 
                                    ?? $item->pembimbingDosens->firstWhere('pivot.status', 'pembimbing1');

                            $pmb2 = $item->pembimbingDosens->firstWhere('pivot.status', 'request2') 
                                    ?? $item->pembimbingDosens->firstWhere('pivot.status', 'pembimbing2');
                        @endphp

                        <td class="p-4 font-medium text-gray-700">
                            {{ $pmb1->user->name ?? 'Belum Ditentukan' }}
                        </td>

                        <td class="p-4 font-medium text-gray-700">
                            {{ $pmb2->user->name ?? 'Belum Ditentukan' }}
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
                            <a href="{{ route('mahasiswa.pengajuan.show', $item->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[11px] font-bold rounded-lg transition inline-flex items-center">
                                <i class="fa-solid fa-eye mr-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-gray-400 font-medium">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <i class="fa-solid fa-folder-open text-2xl text-gray-300"></i>
                                <p class="text-xs">Anda belum pernah mengajukan usulan judul skripsi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
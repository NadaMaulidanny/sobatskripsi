<x-app-layout>
    <x-slot name="title">Manajemen Mahasiswa - Admin</x-slot>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-xl flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6 w-full">
        <div class="flex justify-between items-start w-full">
            <div>
                <h3 class="font-bold text-xl text-gray-900 tracking-tight">Data Induk Mahasiswa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Kelola data login mahasiswa, NIM, prodi, dan pantau status verifikasi akun</p>
            </div>
            
            <div class="flex items-center gap-3">
                <form action="{{ route('super_admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 bg-gray-50 border border-gray-200 p-1.5 pl-3 rounded-xl shadow-sm">
                    @csrf
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">IMPORT:</span>
                    <input type="file" name="file_excel" required
                        class="block w-44 text-xs text-gray-500
                        file:mr-3 file:py-1 file:px-2.5
                        file:rounded-lg file:border-0
                        file:text-xs file:font-medium
                        file:bg-white file:text-gray-700 file:shadow-sm
                        hover:file:bg-gray-100 file:cursor-pointer cursor-pointer" />
                    
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white px-3 py-1.5 rounded-lg font-bold text-xs transition shadow-sm">
                        Import
                    </button>
                </form>

                <a href="{{ route('super_admin.mahasiswa.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs shadow-md shadow-blue-100 transition flex items-center gap-1.5 whitespace-nowrap">
                    <i class="fa-solid fa-plus text-[10px]"></i> Tambah Mahasiswa
                </a>
            </div>
        </div>

        <div class="mt-4">
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" type="button" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-100 transition flex items-center gap-1.5 whitespace-nowrap">
                    <i class="fa-solid fa-file-excel text-[12px]"></i> Export Mahasiswa <i class="fa-solid fa-chevron-down text-[10px] ml-1"></i>
                </button>

                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute left-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 divide-y divide-gray-100 focus:outline-none" 
                    style="display: none;">
                    <div class="py-1">
                        <button type="button" onclick="openExportModal('excel')" class="flex items-center gap-2 w-full px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <i class="fa-solid fa-file-excel text-emerald-600 text-sm"></i> Export ke Excel (.xlsx)
                        </button>
                        <button type="button" onclick="openExportModal('pdf')" class="flex items-center gap-2 w-full px-4 py-2.5 text-xs font-semibold text-gray-700 hover:bg-rose-50 hover:text-rose-700 transition">
                            <i class="fa-solid fa-file-pdf text-rose-600 text-sm"></i> Export ke PDF (.pdf)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="dynamicExportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full px-4">
        <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 border border-gray-100 transform transition-all">
            
            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-900">Filter Periode Tanggal Export (<span id="exportTypeText" class="uppercase text-xs"></span>)</h3>
                <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form id="exportForm" action="" method="GET" onsubmit="closeExportModal()">
                <div class="mt-4 space-y-4">
                    <p class="text-[11px] text-gray-400">Kosongkan kolom tanggal jika ingin mengeksport seluruh riwayat data tanpa filter rentang waktu.</p>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="start_date" class="w-full text-sm bg-gray-50 border border-gray-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="w-full text-sm bg-gray-50 border border-gray-200 p-2.5 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-6 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeExportModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="px-4 py-2 text-white font-bold rounded-xl text-xs shadow-md transition">
                        Unduh File
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
        function openExportModal(type) {
            const modal = document.getElementById('dynamicExportModal');
            const form = document.getElementById('exportForm');
            const typeText = document.getElementById('exportTypeText');
            const submitBtn = document.getElementById('submitBtn');

            typeText.innerText = type;

            if (type === 'excel') {
                // Set Form mengarah ke Route Excel Mahasiswa
                form.action = "{{ route('super_admin.mahasiswa.export') }}"; 
                submitBtn.className = "px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-100 transition";
                submitBtn.innerText = "Unduh Excel";
            } else {
                // Set Form mengarah ke Route PDF Mahasiswa
                form.action = "{{ route('super_admin.mahasiswa.export_pdf') }}"; 
                submitBtn.className = "px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs shadow-md shadow-rose-100 transition";
                submitBtn.innerText = "Unduh PDF";
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeExportModal() {
            const modal = document.getElementById('dynamicExportModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>


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
<x-app-layout>
    <x-slot name="title">Daftar Dosen Prodi - Portal Skripsi</x-slot>
    
    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
         <div class="p-6 flex justify-between items-center border-b border-gray-100">
            <div>
                <h3 class="font-bold text-sm text-gray-900">Manajemen Dosen</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar dosen terdaftar di sistem</p>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-gray-400 font-bold text-[10px] uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 pl-6">Nama Dosen</th>
                        <th class="p-4">NIDN</th>
                        <th class="p-4">Email Resmi</th>
                        <th class="p-4">Bidang Keahlian</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-600 divide-y divide-gray-100">
                    @forelse($dosens as $dsn)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 pl-6 flex items-center space-x-3">
                            <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-600 font-bold flex items-center justify-center text-[10px]">
                                {{ strtoupper(substr($dsn->user->name ?? 'D', 0, 2)) }}
                            </div>
                            <span class="font-semibold text-gray-900">{{ $dsn->user->name ?? '-' }}</span>
                        </td>
                        
                        <td class="p-4 font-medium text-gray-500">{{ $dsn->nidn ?? '-' }}</td>
                        
                        <td class="p-4 text-gray-500 font-medium">{{ $dsn->user->email ?? '-' }}</td>
                        
                        <td class="p-4">
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                @forelse($dsn->bidangStudis as $bidang)
                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-medium text-[10px] border border-blue-100">
                                        {{ $bidang->nama }} </span>
                                @empty
                                    <span class="text-gray-400 text-[11px] italic">Belum ada bidang keahlian</span>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400 font-medium">
                            Belum ada data dosen di program studi ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
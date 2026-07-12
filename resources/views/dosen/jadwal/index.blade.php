<x-app-layout>
    <x-slot name="title">Manajemen Jadwal Bimbingan</x-slot>

    <div class="mb-6">
        <h3 class="font-bold text-xl text-gray-900 tracking-tight">Atur Jadwal Rutin Mingguan</h3>
        <p class="text-xs text-gray-400 mt-0.5">Tentukan hari dan jam ketersediaan Anda agar mahasiswa hanya bisa memesan bimbingan di waktu tersebut.</p>
    </div>

    @if(session('success'))
        <div class="w-full bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-xs text-emerald-600 font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORM INPUT JADWAL -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 h-fit">
            <h4 class="font-bold text-sm text-gray-800 mb-4 flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-plus text-blue-500"></i> Tambah Slot Jadwal
            </h4>

            <form action="{{ route(auth()->user()->role . '.jadwal.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Pilih Hari</label>
                    <select name="hari" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-50 focus:border-blue-500 text-gray-700 font-medium" required>
                        <option value="Monday">Senin</option>
                        <option value="Tuesday">Selasa</option>
                        <option value="Wednesday">Rabu</option>
                        <option value="Thursday">Kamis</option>
                        <option value="Friday">Jumat</option>
                        <option value="Saturday">Sabtu</option>
                        <option value="Sunday">Minggu</option>
                    </select>
                    @error('hari') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 font-medium" required>
                        @error('jam_mulai') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 font-medium" required>
                        @error('jam_selesai') <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5">Kuota Mahasiswa (Per Hari)</label>
                    <input type="number" name="kuota_mahasiswa" value="3" min="1" max="10" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs text-gray-700 font-medium" required>
                    <p class="text-[9px] text-gray-400 mt-1">*Maksimal slot bimbingan yang masuk dalam satu hari tersebut</p>
                </div>

                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-md shadow-blue-100">
                    Simpan Slot Jadwal
                </button>
            </form>
        </div>

        <!-- LIST JADWAL YANG ADA -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h4 class="font-bold text-sm text-gray-800 mb-4">Daftar Ketersediaan Waktu Anda</h4>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 font-bold uppercase tracking-wider text-[10px]">
                            <th class="pb-3">Hari</th>
                            <th class="pb-3">Jam Operasional</th>
                            <th class="pb-3 text-center">Kuota</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                        @forelse($jadwal as $j)
                            <tr>
                                <td class="py-3.5">
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md font-bold text-[10px]">
                                        {{ array_search($j->hari, ['Senin'=>'Monday','Selasa'=>'Tuesday','Rabu'=>'Wednesday','Kamis'=>'Thursday','Jumat'=>'Friday','Sabtu'=>'Saturday','Minggu'=>'Sunday']) ?: __($j->hari) }}
                                    </span>
                                </td>
                                <td class="py-3.5 font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }} WIB
                                </td>
                                <td class="py-3.5 text-center text-gray-500">
                                    {{ $j->kuota_mahasiswa }} Mhs
                                </td>
                                <td class="py-3.5 text-right">
                                    <form action="{{ route(auth()->user()->role . '.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus slot jadwal rutin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-[11px] transition">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-400 italic">
                                    Anda belum mengonfigurasi jadwal rutin ketersediaan bimbingan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="title">Tambah Mahasiswa Baru - Portal Skripsi</x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 w-full">
        <div class="order-2 sm:order-1">
            <h3 class="font-bold text-xl text-gray-900 tracking-tight">Form Pendaftaran Mahasiswa</h3>
            <p class="text-xs text-gray-400 mt-0.5">Input data akun dan informasi akademik mahasiswa baru</p>
        </div>
        <div class="order-1 sm:order-2 self-start sm:self-auto">
            <a href="{{ route('kaprodi.daftar-mhs') }}" class="bg-white hover:bg-gray-50 text-gray-600 border border-gray-200 px-4 py-2.5 rounded-xl text-xs font-semibold transition flex items-center justify-center shadow-sm">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Tampilkan Alert Error Jika Validasi Gagal -->
    @if ($errors->any())
        <div class="mb-5 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-xs text-rose-600 space-y-1">
            <p class="font-bold">Periksa kembali inputan Anda:</p>
            <ul class="list-disc pl-4 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <form action="{{ route('kaprodi.mahasiswa.store') }}" method="POST" class="p-8 space-y-8">
            @csrf

            <!-- Section 1: Kredensial Akun -->
            <div class="space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-user-lock mr-2 text-sm"></i> Informasi Kredensial Akun
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-600">Alamat Email Resmi</label>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <i class="fa-regular fa-envelope text-xs"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh: mhs@kampus.ac.id" 
                                class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-4 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-gray-600">Password Akun</label>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-key text-xs"></i>
                            </span>
                            <input type="password" name="password" required placeholder="Minimal 8 karakter" 
                                class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-4 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Akademik -->
            <div class="space-y-4 pt-2">
                <h4 class="text-xs font-bold uppercase tracking-wider text-blue-600 flex items-center border-b border-gray-50 pb-2">
                    <i class="fa-solid fa-graduation-cap mr-2 text-sm"></i> Data Akademik Mahasiswa
                </h4>
                <div class="space-y-6 w-full">
                    <div class="space-y-1.5 w-full">
                        <label class="text-xs font-semibold text-gray-600">Nama Lengkap Mahasiswa</label>
                        <div class="relative w-full">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                <i class="fa-regular fa-user text-xs"></i>
                            </span>
                            <!-- Diubah dari nama_mahasiswa menjadi name agar klop dengan Controller -->
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap tanpa singkatan" 
                                class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-4 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-600">Nomor Induk Mahasiswa (NIM)</label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-id-card-clip text-xs"></i>
                                </span>
                                <input type="text" name="nim" value="{{ old('nim') }}" required placeholder="contoh: 2010412034" 
                                    class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-4 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium shadow-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-gray-600">Program Studi / Jurusan</label>
                            <div class="relative w-full">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                                    <i class="fa-solid fa-building-columns text-xs"></i>
                                </span>
                                <!-- Mengunci nama prodi secara otomatis dari Kaprodi yang sedang login -->
                                <input type="text" readonly value="{{ auth()->user()->dosen->prodi->nama_prodi ?? 'Prodi Belum Diatur' }}" 
                                    class="w-full border border-gray-200 bg-gray-50 pl-11 pr-4 py-3 rounded-xl text-xs text-gray-400 font-semibold shadow-sm cursor-not-allowed">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Otomatis didaftarkan ke Program Studi Anda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100 w-full">
                <a href="{{ route('kaprodi.daftar-mhs') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-xl transition">
                    Batalkan
                </a>
                <button type="submit" class="px-7 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-100 transition">
                    <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i> Simpan Data Mahasiswa
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
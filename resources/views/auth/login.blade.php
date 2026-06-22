<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Portal Skripsi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-800 antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12">
        
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-600 text-white text-xl shadow-md shadow-blue-100 mb-3">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Sistem Pengajuan Judul Skripsi</h2>
            <p class="text-xs text-gray-400 mt-1">Silahkan masuk menggunakan akun terdaftar Anda</p>
        </div>

        <div class="w-full max-w-md bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            
            @if (session('status'))
                <div class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-semibold text-gray-600">Alamat Email</label>
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            placeholder="nama@kampus.ac.id" 
                            class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-4 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium outline-none">
                    </div>
                    @if($errors->has('email'))
                        <p class="text-[11px] text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="space-y-1.5" x-data="{ showPass: false }">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-xs font-semibold text-gray-600">Password Akun</label>
                        @if (Route::has('password.request'))
                            <a class="text-[11px] text-blue-600 hover:underline font-medium transition" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-400">
                            <i class="fa-solid fa-key text-xs"></i>
                        </span>

                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required autocomplete="current-password"
                            placeholder="••••••••" 
                            class="w-full border border-gray-200 bg-[#f8fafc] pl-11 pr-12 py-3 rounded-xl text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white transition text-gray-700 font-medium outline-none">
                        
                        <button type="button" @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                            <i class="fa-solid text-xs" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @if($errors->has('password'))
                        <p class="text-[11px] text-red-500 font-medium mt-1"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first('password') }}</p>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" name="remember" 
                            class="w-4 h-4 rounded border-gray-200 bg-[#f8fafc] text-blue-600 focus:ring-blue-500/20 focus:ring-2 transition">
                        <span class="ms-2 text-xs text-gray-400 font-medium select-none">Ingat akun saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-100 transition duration-200 flex items-center justify-center space-x-2">
                        <span>Masuk ke Akun</span>
                        <i class="fa-solid fa-arrow-right-to-bracket text-[11px]"></i>
                    </button>
                </div>
            </form>
            
        </div>

        <p class="text-[10px] text-gray-400 mt-8 font-medium">© 2026 Portal Skripsi Kampus • Hak Cipta Dilindungi.</p>
    </div>

</body>
</html>
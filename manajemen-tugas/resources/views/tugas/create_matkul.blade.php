<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - REGISTRATION_COURSE</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    body { 
        font-family: 'Montserrat', sans-serif; 
    }
    .mono-font { 
        font-family: 'Share Tech Mono', monospace; /* Tetap dipertahankan untuk teks ala terminal/angka */
    }
    .scanlines {
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.3));
        background-size: 100% 4px;
    }
    </style>
</head>

<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col relative selection:bg-amber-500 selection:text-black">
    
    <!-- Garis Scanlines -->
    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <!-- Header / Navigasi Taktis -->
    <x-header :showReturn="true" returnUrl="/" />

    <main class="max-w-7xl mx-auto px-6 pt-32 pb-16 relative z-20 flex-grow w-full">
        
        <!-- Notifikasi Sukses / Error -->
        @if (session('success'))
            <div class="mb-6 bg-zinc-900 border-l-4 border-emerald-500 text-emerald-400 px-4 py-3 text-xs mono-font uppercase flex justify-between items-center">
                <span>[SUCCESS]: {{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-zinc-900 border-l-4 border-rose-500 text-rose-400 px-4 py-3 text-xs mono-font uppercase flex justify-between items-center">
                <span>[ERROR]: {{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Form Registrasi Matkul (Kiri) -->
            <div class="md:col-span-2 bg-zinc-900/90 border border-zinc-800 p-6 h-fit">
                <div class="border-b border-zinc-800 pb-4 mb-6">
                    <h2 class="text-lg font-bold tracking-widest text-white uppercase mono-font">// INPUT_COURSE_DATA</h2>
                    <p class="text-xs text-zinc-500">Register new educational matrix to the system database.</p>
                </div>
                
                @if ($errors->any())
                    <div class="mb-6 bg-zinc-950 border border-rose-900/60 text-rose-400 p-4 text-xs mono-font">
                        <p class="font-bold mb-1">// VALIDATION_FAILED:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/matkul" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Kode Mata Kuliah</label>
                        <input type="text" name="kode_matkul" value="{{ old('kode_matkul') }}" placeholder="Contoh: IF101" 
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-2.5 focus:border-amber-500 focus:outline-none mono-font transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Nama Mata Kuliah</label>
                        <input type="text" name="nama_matkul" value="{{ old('nama_matkul') }}" placeholder="Contoh: Pemrograman Web" 
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-2.5 focus:border-amber-500 focus:outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Nama Dosen (Opsional)</label>
                        <input type="text" name="dosen" value="{{ old('dosen') }}" placeholder="Contoh: Budi S.Kom, M.T" 
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-2.5 focus:border-amber-500 focus:outline-none transition">
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black px-6 py-2.5 text-xs font-bold uppercase tracking-wider transition shadow-[0_0_15px_rgba(245,158,11,0.2)] mono-font">
                            [EXECUTE] SIMPAN
                        </button>
                        <a href="/" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 hover:text-white px-6 py-2.5 text-xs font-bold uppercase tracking-wider transition mono-font text-center flex items-center">
                            BATAL
                        </a>
                    </div>
                </form>
            </div>

            <!-- Daftar Matkul Cepat (Kanan) -->
            <div class="md:col-span-1 h-fit">
                <x-daftar-matkul :mata_kuliah="$mata_kuliah" />
            </div>

        </div>
    </main>
    <x-footer />

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - REGISTRATION_COURSE</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Montserrat', sans-serif; 
        }
        .mono-font { 
            font-family: 'Share Tech Mono', monospace; 
        }
        .scanlines {
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.35) 50%, rgba(0, 0, 0, 0.35));
            background-size: 100% 4px;
        }
        .cyber-grid {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(245, 158, 11, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(245, 158, 11, 0.04) 1px, transparent 1px);
        }
        .border-glow-amber {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .border-glow-amber:hover, .border-glow-amber:focus-within {
            border-color: rgba(245, 158, 11, 0.6);
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.15);
        }
    </style>
</head>

<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-between relative selection:bg-amber-500 selection:text-black"
      x-data="{ deleteModalOpen: false, deleteUrl: '', deleteMatkulName: '' }">
    
    <!-- Background Grid & Scanlines Overlay -->
    <div class="fixed inset-0 cyber-grid pointer-events-none z-0 opacity-40"></div>
    <div class="fixed inset-0 scanlines pointer-events-none z-10 opacity-30"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-20">
        <x-header />

        <main class="max-w-7xl mx-auto px-6 pt-32 pb-20 w-full">
            
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-xs mono-font text-zinc-500 mb-6 uppercase tracking-wider">
                <a href="{{ route('dashboard') }}" class="hover:text-amber-400 transition">[DASHBOARD]</a>
                <span>/</span>
                <span class="text-amber-400">[COURSE_MANAGEMENT]</span>
            </div>

            <!-- Notifications -->
            @if (session('success'))
                <div class="mb-6 bg-emerald-950/40 border border-emerald-500/60 text-emerald-400 px-5 py-3.5 rounded-lg text-xs mono-font uppercase flex items-center justify-between shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        [SUCCESS]: {{ session('success') }}
                    </span>
                    <span class="text-emerald-500/60">✓ EXECUTED</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-rose-950/40 border border-rose-500/60 text-rose-400 px-5 py-3.5 rounded-lg text-xs mono-font uppercase flex items-center justify-between shadow-[0_0_15px_rgba(244,63,94,0.15)]">
                    <span class="flex items-center gap-2">
                        <span>[!]</span>
                        [SYSTEM_ERROR]: {{ session('error') }}
                    </span>
                    <span class="text-rose-500/60">[FAILED]</span>
                </div>
            @endif

            <!-- 2-Column Responsive Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Column: Form Input Matkul (5 cols) -->
                <div class="lg:col-span-5 bg-zinc-900/90 border border-zinc-800 rounded-xl p-6 sm:p-8 shadow-[0_0_35px_rgba(0,0,0,0.5)] backdrop-blur-xl relative border-glow-amber">
                    
                    <!-- Corner HUD Accents -->
                    <div class="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-amber-500"></div>
                    <div class="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-amber-500"></div>
                    <div class="absolute -bottom-1 -left-1 w-3 h-3 border-b-2 border-l-2 border-amber-500"></div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-amber-500"></div>

                    <div class="border-b border-zinc-800 pb-4 mb-6">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="text-[10px] text-amber-500 mono-font uppercase tracking-widest">// MATRIX_REGISTRATION</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-bold tracking-widest text-white uppercase mono-font">
                            TAMBAH MATA KULIAH
                        </h2>
                        <p class="text-xs text-zinc-400 mt-1">Daftarkan modul akademik baru ke database sistem.</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-rose-950/40 border border-rose-600/60 rounded p-4 text-xs mono-font text-rose-300">
                            <p class="font-bold text-rose-400 mb-1">// VALIDATION_FAILED:</p>
                            <ul class="list-disc list-inside space-y-1 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/matkul" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                                // 01_KODE_MATA_KULIAH <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kode_matkul" value="{{ old('kode_matkul') }}" placeholder="Contoh: IF201" 
                                   class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none mono-font transition uppercase placeholder-zinc-600" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                                // 02_NAMA_MATA_KULIAH <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_matkul" value="{{ old('nama_matkul') }}" placeholder="Contoh: Algoritma & Struktur Data" 
                                   class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition placeholder-zinc-600" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                                // 03_DOSEN_PENGAMPU (OPTIONAL)
                            </label>
                            <input type="text" name="dosen" value="{{ old('dosen') }}" placeholder="Contoh: Dr. Ir. Gunawan, M.Kom" 
                                   class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition placeholder-zinc-600">
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-zinc-800">
                            <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black px-5 py-3 text-xs font-bold uppercase tracking-wider transition rounded-lg shadow-[0_0_15px_rgba(245,158,11,0.2)] mono-font flex items-center gap-2 cursor-pointer">
                                <span>></span>
                                <span>[EXECUTE] SIMPAN</span>
                            </button>
                            <a href="{{ route('dashboard') }}" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 hover:text-white px-4 py-3 text-xs font-bold uppercase tracking-wider transition mono-font rounded-lg text-center flex items-center">
                                [✕] KEMBALI
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Registered Courses Directory Table (7 cols) -->
                <div class="lg:col-span-7 bg-zinc-900/90 border border-zinc-800 rounded-xl overflow-hidden shadow-[0_0_35px_rgba(0,0,0,0.5)] backdrop-blur-xl relative">
                    
                    <div class="bg-zinc-950 px-6 py-4 border-b border-zinc-800 flex justify-between items-center gap-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                            <h3 class="text-xs font-bold tracking-widest text-zinc-300 uppercase mono-font">
                                DIREKTORI MATA KULIAH <span class="text-zinc-600">// REGISTERED</span>
                            </h3>
                        </div>
                        <span class="text-[10px] mono-font text-amber-400 bg-amber-950/60 border border-amber-500/40 px-2.5 py-1 rounded">
                            {{ $mata_kuliah->count() }} ENTRIES
                        </span>
                    </div>

                    @if($mata_kuliah->isEmpty())
                        <div class="p-12 text-center text-zinc-500 mono-font text-xs">
                            <p class="text-amber-500/60 mb-2">// DIRECTORY_EMPTY</p>
                            <span>Belum ada data mata kuliah terdaftar di sistem. Silakan isi form di samping untuk mendaftar.</span>
                        </div>
                    @else
                        <div class="overflow-x-auto max-h-[520px] overflow-y-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="sticky top-0 bg-zinc-950 z-10">
                                    <tr class="border-b border-zinc-800 text-[10px] mono-font text-zinc-500 uppercase">
                                        <th class="p-4 tracking-wider">KODE</th>
                                        <th class="p-4 tracking-wider">NAMA MATA KULIAH</th>
                                        <th class="p-4 tracking-wider">DOSEN</th>
                                        <th class="p-4 text-right tracking-wider">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800/60 text-xs">
                                    @foreach($mata_kuliah as $mk)
                                        <tr class="hover:bg-zinc-800/40 transition">
                                            <td class="p-4 mono-font font-bold text-amber-400 whitespace-nowrap">
                                                {{ $mk->kode_matkul }}
                                            </td>
                                            <td class="p-4 text-zinc-200 font-semibold">
                                                {{ $mk->nama_matkul }}
                                            </td>
                                            <td class="p-4 text-zinc-400 text-xs">
                                                {{ $mk->dosen ?: '-' }}
                                            </td>
                                            <td class="p-4 text-right">
                                                <button type="button" 
                                                        @click="deleteModalOpen = true; deleteUrl = '{{ route('matkul.destroy', $mk->id) }}'; deleteMatkulName = '{{ $mk->kode_matkul }} - {{ $mk->nama_matkul }}'" 
                                                        class="border border-rose-900/60 hover:border-rose-500 text-rose-400 bg-rose-950/30 hover:bg-rose-900/50 px-3 py-1.5 text-[10px] mono-font uppercase transition rounded">
                                                    HAPUS
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <x-footer />

    <!-- Termination Confirmation Modal -->
    <div x-show="deleteModalOpen" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         x-transition.opacity
         style="display: none;">
        <div class="bg-zinc-950 border border-rose-600/60 max-w-md w-full p-6 shadow-[0_0_30px_rgba(225,29,72,0.2)] relative rounded-lg">
            <div class="flex justify-between items-center border-b border-zinc-800 pb-3 mb-4">
                <span class="text-xs font-bold text-rose-500 mono-font uppercase tracking-widest flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    // SYSTEM_WARNING: CONFIRM_TERMINATION
                </span>
                <button @click="deleteModalOpen = false" class="text-zinc-500 hover:text-white mono-font text-xs">[X]</button>
            </div>

            <p class="text-xs text-zinc-300 mono-font mb-6 leading-relaxed">
                Anda akan menghapus entri mata kuliah: <span class="text-amber-400 font-bold" x-text="deleteMatkulName"></span> dari direktori sistem. Tindakan ini bersifat permanen.
            </p>

            <div class="flex justify-end gap-3">
                <button @click="deleteModalOpen = false" 
                        class="border border-zinc-700 text-zinc-400 hover:bg-zinc-900 px-4 py-2 text-xs uppercase mono-font transition rounded">
                    [CANCEL]
                </button>

                <form :action="deleteUrl" method="POST" class="inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="border border-rose-600 bg-rose-950/60 hover:bg-rose-900 text-rose-400 px-4 py-2 text-xs uppercase mono-font font-bold transition shadow-[0_0_10px_rgba(225,29,72,0.3)] rounded">
                        [EXECUTE] TERMINATE
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
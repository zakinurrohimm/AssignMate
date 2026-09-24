<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - RECONFIGURE_ASSIGNMENT</title>
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

<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-between relative selection:bg-amber-500 selection:text-black">
    
    <!-- Background Grid & Scanlines Overlay -->
    <div class="fixed inset-0 cyber-grid pointer-events-none z-0 opacity-40"></div>
    <div class="fixed inset-0 scanlines pointer-events-none z-10 opacity-30"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-amber-500/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <div class="relative z-20">
        <x-header />

        <main class="max-w-3xl mx-auto px-6 pt-32 pb-20 w-full">
            
            <!-- Breadcrumb Navigation -->
            <div class="flex items-center gap-2 text-xs mono-font text-zinc-500 mb-6 uppercase tracking-wider">
                <a href="{{ route('dashboard') }}" class="hover:text-amber-400 transition">[DASHBOARD]</a>
                <span>/</span>
                <span class="text-amber-400">[RECONFIGURE_TASK_{{ $tugas->id }}]</span>
            </div>

            <!-- Cyber HUD Form Card -->
            <div class="bg-zinc-900/90 border border-zinc-800 rounded-xl p-6 sm:p-10 shadow-[0_0_40px_rgba(0,0,0,0.6)] backdrop-blur-xl relative border-glow-amber">
                
                <!-- Corner HUD Accents -->
                <div class="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-amber-500"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-amber-500"></div>
                <div class="absolute -bottom-1 -left-1 w-3 h-3 border-b-2 border-l-2 border-amber-500"></div>
                <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-amber-500"></div>

                <!-- Form Header -->
                <div class="border-b border-zinc-800 pb-5 mb-8 flex justify-between items-start sm:items-center gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span class="text-[10px] text-amber-500 mono-font uppercase tracking-widest">// PARAMETER_RECONFIGURATION</span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold tracking-widest text-white uppercase mono-font">
                            EDIT PARAMETER TUGAS
                        </h2>
                        <p class="text-xs text-zinc-400 mt-1">Perbarui parameter tugas dan sesuaikan tenggat waktu operasional.</p>
                    </div>

                    <span class="text-[10px] mono-font text-zinc-400 bg-zinc-950 px-3 py-1.5 rounded border border-zinc-800">
                        TASK_ID #{{ $tugas->id }}
                    </span>
                </div>

                <!-- Validation Alerts -->
                @if ($errors->any())
                    <div class="mb-8 bg-rose-950/40 border border-rose-600/60 rounded p-4 text-xs mono-font text-rose-300 shadow-[0_0_15px_rgba(225,29,72,0.15)]">
                        <p class="font-bold text-rose-400 mb-2 flex items-center gap-1.5">
                            <span>[!]</span> [VALIDATION_FAILED]: Harap periksa input berikut
                        </p>
                        <ul class="list-disc list-inside space-y-1 text-rose-300/90 text-[11px]">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/tugas/{{ $tugas->id }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Field 1: Mata Kuliah -->
                    <div>
                        <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                            // 01_MATA_KULIAH <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="mata_kuliah_id" class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition cursor-pointer appearance-none" required>
                                @foreach($mataKuliah as $mk)
                                    <option value="{{ $mk->id }}" {{ (old('mata_kuliah_id', $tugas->mata_kuliah_id) == $mk->id) ? 'selected' : '' }}>
                                        [{{ $mk->kode_matkul }}] {{ $mk->nama_matkul }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="absolute right-4 top-4 text-zinc-500 pointer-events-none text-xs">▼</span>
                        </div>
                    </div>

                    <!-- Field 2: Nama Tugas -->
                    <div>
                        <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                            // 02_NAMA_TUGAS <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_tugas" value="{{ old('nama_tugas', $tugas->nama_tugas) }}" 
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition" required>
                    </div>

                    <!-- Grid: Deadline & Prioritas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Field 3: Deadline -->
                        <div>
                            <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                                // 03_BATAS_WAKTU (DEADLINE) <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" name="deadline" 
                                   value="{{ old('deadline', date('Y-m-d\TH:i', strtotime($tugas->deadline))) }}" 
                                   onclick="this.showPicker()"
                                   class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none mono-font transition [color-scheme:dark] cursor-pointer" required>
                        </div>

                        <!-- Field 4: Prioritas -->
                        <div>
                            <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                                // 04_PRIORITAS_TUGAS
                            </label>
                            <div class="relative">
                                <select name="prioritas" class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition cursor-pointer appearance-none">
                                    <option value="Sedang" {{ old('prioritas', $tugas->prioritas ?? 'Sedang') == 'Sedang' ? 'selected' : '' }}>🟡 Sedang (Standard Priority)</option>
                                    <option value="Tinggi" {{ old('prioritas', $tugas->prioritas ?? 'Sedang') == 'Tinggi' ? 'selected' : '' }}>🔴 Tinggi (High Priority)</option>
                                    <option value="Rendah" {{ old('prioritas', $tugas->prioritas ?? 'Sedang') == 'Rendah' ? 'selected' : '' }}>🟢 Rendah (Low Priority)</option>
                                </select>
                                <span class="absolute right-4 top-4 text-zinc-500 pointer-events-none text-xs">▼</span>
                            </div>
                        </div>
                    </div>

                    <!-- Field 5: Deskripsi / Catatan Tambahan -->
                    <div>
                        <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                            // 05_DESKRIPSI_CATATAN (OPTIONAL)
                        </label>
                        <textarea name="deskripsi" rows="3" placeholder="Instruksi tugas, format submission, atau link repositori..." 
                                  class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 focus:border-amber-500 focus:outline-none transition placeholder-zinc-600 resize-none">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-4 pt-6 border-t border-zinc-800">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black px-6 py-3.5 text-xs font-bold uppercase tracking-wider transition rounded-lg shadow-[0_0_20px_rgba(245,158,11,0.25)] mono-font flex items-center gap-2 cursor-pointer">
                            <span>></span>
                            <span>[UPDATE] RECONFIGURE PARAMETERS</span>
                        </button>
                        <a href="{{ route('dashboard') }}" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 hover:text-white px-6 py-3.5 text-xs font-bold uppercase tracking-wider transition mono-font rounded-lg text-center flex items-center">
                            [✕] BATAL
                        </a>
                    </div>
                </form>
            </div>

        </main>
    </div>

    <x-footer />

</body>
</html>
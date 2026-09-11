<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ENDFIELD // NEW_ASSIGNMENT</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Rajdhani', sans-serif; }
        .mono-font { font-family: 'Share Tech Mono', monospace; }
        .scanlines {
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.3));
            background-size: 100% 4px;
        }
    </style>
</head>

<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-between relative selection:bg-amber-500 selection:text-black">
    
    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <div>
        <x-header :showReturn="true" returnUrl="/" />

        <main class="max-w-3xl mx-auto px-6 pt-32 pb-16 relative z-20 w-full">
            
            <div class="bg-zinc-900/90 border border-zinc-800 p-8">
                <div class="border-b border-zinc-800 pb-4 mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold tracking-widest text-white uppercase mono-font">// DISPATCH_NEW_ASSIGNMENT</h2>
                        <p class="text-xs text-zinc-500">Input task parameters and set operational deadlines.</p>
                    </div>
                    <a href="/matkul/create" class="border border-zinc-700 hover:border-amber-500 hover:text-amber-400 bg-zinc-950 px-3 py-1 text-[10px] font-bold uppercase tracking-wider transition mono-font">
                        [+] REG. MATKUL
                    </a>
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

                <form action="/tugas" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Mata Kuliah</label>
                        <select name="mata_kuliah_id" class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-3 focus:border-amber-500 focus:outline-none transition" required>
                            <option value="" disabled selected>// PILIH_MATA_KULIAH</option>
                            @foreach($mataKuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode_matkul }} - {{ $mk->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Nama Tugas / Operasi</label>
                        <input type="text" name="nama_tugas" value="{{ old('nama_tugas') }}" placeholder="Contoh: Laporan Praktikum Modul 3" 
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-3 focus:border-amber-500 focus:outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Batas Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" 
                               onclick="this.showPicker()"
                               class="w-full bg-zinc-950 border border-zinc-800 text-zinc-200 text-sm rounded-none p-3 focus:border-amber-500 focus:outline-none mono-font transition [color-scheme:dark] cursor-pointer" required>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-zinc-800">
                        <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black px-6 py-3 text-xs font-bold uppercase tracking-wider transition shadow-[0_0_15px_rgba(245,158,11,0.2)] mono-font">
                            [DISPATCH] SIMPAN TUGAS
                        </button>
                        <a href="/" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 hover:text-white px-6 py-3 text-xs font-bold uppercase tracking-wider transition mono-font text-center flex items-center">
                            BATAL
                        </a>
                    </div>
                </form>
            </div>

        </main>
    </div>

    <x-footer />

</body>
</html>
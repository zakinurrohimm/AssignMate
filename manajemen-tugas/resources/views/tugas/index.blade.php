<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - TERMINAL</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/css/app.css')
    <!-- Google Fonts untuk kesan Sci-Fi / Monospace -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
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

    <x-loading-screen />

    <!-- Efek Garis Scanlines Tipis di Background -->
    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <!-- Header / Navigasi Taktis -->
    <x-header />

    <!-- Main Terminal Container -->
    <main class="max-w-7xl mx-auto px-6 pt-32 pb-16 relative z-20 flex-grow w-full">

        <!-- Notifikasi Flash Sukses/Error -->
        @if (session('success'))
            <div class="mb-6 bg-zinc-900 border-l-4 border-emerald-500 text-emerald-400 px-4 py-3 text-xs mono-font uppercase flex justify-between items-center">
                <span>[SUCCESS]: {{ session('success') }}</span>
                <span class="text-[10px] text-zinc-600">SYS_LOG_OK</span>
            </div>
        @endif

        <!-- Panel Status / Metrik Taktis (Grid Atas) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-zinc-900/80 border border-zinc-800 p-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-2 bg-zinc-700"></div>
                <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase">TOTAL_ASSIGNMENTS</p>
                <p class="text-3xl font-bold text-white mono-font mt-1">{{ $totalTugas ?? 0 }}</p>
                <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-amber-500 h-0.5 w-full"></div></div>
            </div>
            
            <div class="bg-zinc-900/80 border border-zinc-800 p-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-2 bg-emerald-700"></div>
                <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase">STATUS_COMPLETED</p>
                <p class="text-3xl font-bold text-emerald-400 mono-font mt-1">{{ $tugasSelesai ?? 0 }}</p>
                <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-emerald-500 h-0.5 w-full"></div></div>
            </div>

            <div class="bg-zinc-900/80 border border-zinc-800 p-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-2 h-2 bg-amber-700"></div>
                <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase">PENDING_ACTION</p>
                <p class="text-3xl font-bold text-amber-400 mono-font mt-1">{{ $tugasBelum ?? 0 }}</p>
                <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-amber-500 h-0.5 w-full"></div></div>
            </div>
        </div>

        <!-- Komponen Daftar Mata Kuliah (Accordion / Dropdown Taktis) -->
        <div class="mb-8">
            <x-daftar-matkul :mata_kuliah="$mata_kuliah" />
        </div>

        <!-- Tabel Data Tugas Aktif (Terminal Log Style) -->
        <div class="bg-zinc-900/90 border border-zinc-800 overflow-hidden">
            <div class="bg-zinc-950 px-6 py-3 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xs font-bold tracking-widest text-zinc-400 uppercase mono-font">// ACTIVE_ASSIGNMENTS_DIRECTORY</h3>
                <span class="text-[10px] mono-font text-amber-500">SECURE_CHANNEL</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-800 text-[11px] mono-font text-zinc-500 uppercase bg-zinc-950/50">
                            <th class="p-4">Mata Kuliah</th>
                            <th class="p-4">Nama Tugas</th>
                            <th class="p-4">Deadline</th>
                            <th class="p-4">Status Waktu</th>
                            <th class="p-4">Status Tugas</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/60 text-sm">
                        @forelse($tugas as $t)
                            <tr class="hover:bg-zinc-800/40 transition">
                                <td class="p-4 font-bold text-zinc-300">{{ $t->mataKuliah->nama_matkul ?? '-' }}</td>
                                <td class="p-4 text-white font-semibold">{{ $t->nama_tugas }}</td>
                                <td class="p-4 mono-font text-xs text-zinc-400">{{ $t->deadline }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 text-[10px] mono-font uppercase border {{ $t->badge_color ?? 'border-zinc-700 text-zinc-400' }}">
                                        {{ $t->sisa_waktu ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($t->status == 'Selesai')
                                        <span class="text-emerald-400 text-xs mono-font font-bold">[COMPLETED]</span>
                                    @else
                                        <span class="text-amber-400 text-xs mono-font font-bold">[IN_PROGRESS]</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    @if($t->status != 'Selesai')
                                        <form action="/tugas/{{ $t->id }}/status" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="border border-emerald-600/50 text-emerald-400 hover:bg-emerald-950/50 px-2.5 py-1 text-xs uppercase mono-font transition">
                                                SELESAI
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="/tugas/{{ $t->id }}" method="POST" class="inline" onsubmit="return confirm('Hapus data tugas ini dari sistem?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border border-rose-600/50 text-rose-400 hover:bg-rose-950/50 px-2.5 py-1 text-xs uppercase mono-font transition">
                                            HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-zinc-600 mono-font text-xs">
                                    // NO_ACTIVE_ASSIGNMENTS_FOUND_IN_SYSTEM
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <x-footer />

</body>
</html>
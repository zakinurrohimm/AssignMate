@php
    function caesarCipher($string, $key = 3) {
        $result = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];
            if (ctype_alpha($char)) {
                $asciiOffset = ctype_upper($char) ? ord('A') : ord('a');
                $result .= chr(($asciiOffset + (ord($char) - $asciiOffset + $key) % 26));
            } else {
                $result .= $char;
            }
        }
        return $result;
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - TERMINAL</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <!-- Alpine.js Collapse Plugin (Wajib agar animasi expand/collapse halus) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
    <style>
    body { 
        font-family: 'Montserrat', sans-serif; 
    }
    .mono-font { 
        font-family: 'Share Tech Mono', monospace; 
    }
    .scanlines {
        background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.3));
        background-size: 100% 4px;
    }
    /* Cyberpunk Grid Background */
    .cyber-grid {
        background-image: 
            linear-gradient(to right, rgba(245, 158, 11, 0.06) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(245, 158, 11, 0.06) 1px, transparent 1px);
        background-size: 28px 28px;
    }
    /* Keyframe Aurora Glowing Animation */
    @keyframes aurora-float-1 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(50px, -30px) scale(1.15); }
        66% { transform: translate(-40px, 20px) scale(0.9); }
    }
    @keyframes aurora-float-2 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(-50px, 40px) scale(1.2); }
        66% { transform: translate(30px, -40px) scale(0.85); }
    }
    @keyframes aurora-float-3 {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        50% { transform: translate(25px, 25px) scale(1.2); }
    }
    .animate-aurora-1 { animation: aurora-float-1 14s ease-in-out infinite alternate; }
    .animate-aurora-2 { animation: aurora-float-2 18s ease-in-out infinite alternate-reverse; }
    .animate-aurora-3 { animation: aurora-float-3 12s ease-in-out infinite alternate; }

    /* React Bits: Spotlight Card Effect */
    .spotlight-card {
        position: relative;
        overflow: hidden;
    }
    .spotlight-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(
            280px circle at var(--mouse-x, -500px) var(--mouse-y, -500px),
            rgba(245, 158, 11, 0.16),
            transparent 70%
        );
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }
    .spotlight-card:hover::before {
        opacity: 1;
    }

    .spotlight-card-emerald::before {
        background: radial-gradient(
            280px circle at var(--mouse-x, -500px) var(--mouse-y, -500px),
            rgba(16, 185, 129, 0.18),
            transparent 70%
        );
    }


    /* Cyber Card Border Glow */
    .border-glow-cyber {
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .border-glow-cyber:hover {
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.15);
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #090a0f;
        border-left: 1px solid #27272a;
    }
    ::-webkit-scrollbar-thumb {
        background: #3f3f46;
        border: 1px solid #52525b;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #f59e0b;
        border-color: #d97706;
    }
    </style>
</head>
<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col relative selection:bg-amber-500 selection:text-black" x-data="{ showDeleteModal: false, deleteUrl: '' }">

    <x-loading-screen />

    <!-- ================= LAPISAN AURORA GRADIENT GLOBAL ================= -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0 opacity-80">
        <!-- Aurora Blob 1: Amber Gold (#f59e0b) -->
        <div id="blob-1" class="absolute top-0 -left-1/4 w-[800px] h-[800px] bg-amber-500/10 rounded-full blur-[120px] mix-blend-screen animate-aurora-1"></div>

        <!-- Aurora Blob 2: Deep Orange (#ea580c) -->
        <div id="blob-2" class="absolute bottom-0 -right-1/4 w-[900px] h-[900px] bg-orange-600/5 rounded-full blur-[150px] mix-blend-screen animate-aurora-2"></div>

        <!-- Aurora Blob 3: Goldenrod Warm Center Accent (#fbbf24) -->
        <div id="blob-3" class="absolute top-1/3 left-1/4 w-[600px] h-[600px] bg-amber-400/5 rounded-full blur-[100px] mix-blend-screen animate-aurora-3"></div>
    </div>

    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <x-header />

    <main class="max-w-7xl mx-auto px-6 pt-28 pb-16 relative z-20 w-full">

        <!-- Panel Identitas Operator & Aurora Cyber Banner -->
        <div id="cyber-aurora-card" class="relative w-full bg-[#0b0c10]/95 border border-zinc-800 overflow-hidden mb-8 shadow-[0_0_35px_rgba(0,0,0,0.6)] backdrop-blur-xl">

            <!-- ================= LAPISAN CYBER GRID & SCANLINES ================= -->
            <div class="absolute inset-0 cyber-grid opacity-50 pointer-events-none z-[1]"></div>
            <div class="absolute inset-0 scanlines opacity-25 pointer-events-none z-[2]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_30%,#0b0c10_90%)] pointer-events-none z-[3]"></div>

            <!-- Corner HUD Accents -->
            <div class="absolute top-2 left-2 w-2.5 h-2.5 border-t-2 border-l-2 border-amber-500/60 z-10 pointer-events-none"></div>
            <div class="absolute top-2 right-2 w-2.5 h-2.5 border-t-2 border-r-2 border-amber-500/60 z-10 pointer-events-none"></div>
            <div class="absolute bottom-2 left-2 w-2.5 h-2.5 border-b-2 border-l-2 border-amber-500/60 z-10 pointer-events-none"></div>
            <div class="absolute bottom-2 right-2 w-2.5 h-2.5 border-b-2 border-r-2 border-amber-500/60 z-10 pointer-events-none"></div>

            <!-- ================= KONTEN DI ATAS AURORA ================= -->
            <div class="relative z-10 p-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <!-- Status Badges & Realtime Clock -->
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-zinc-900/80 border border-emerald-500/40 backdrop-blur-sm shadow-[0_0_12px_rgba(16,185,129,0.15)]">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span class="mono-font text-[11px] font-semibold text-emerald-400 tracking-wider">SYSTEM_ACTIVE</span>
                            <span class="text-zinc-600 mono-font text-[10px]">|</span>
                            <span class="mono-font text-[10px] text-amber-500/90 tracking-widest uppercase">NODE::ONLINE</span>
                        </div>

                        <div class="mono-font text-[11px] text-zinc-400 flex items-center gap-1.5">
                            <span class="text-zinc-600">[SYS_TIME]:</span>
                            <span id="terminal-clock" class="text-amber-400 font-bold tracking-wider">--:--:--</span>
                        </div>
                    </div>

                    <!-- Headline: WELCOME, OPERATOR (React Bits: Decrypted Text) -->
                    <h2 class="text-xl md:text-2xl font-bold text-white mono-font tracking-wide flex items-center">
                        WELCOME, <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-amber-500 to-orange-500 ml-2 drop-shadow-[0_0_20px_rgba(245,158,11,0.3)] cursor-pointer select-none" data-scramble="{{ strtoupper(Auth::user()->name) }}">{{ strtoupper(Auth::user()->name) }}</span>
                        <span class="inline-block w-2 h-5 bg-amber-400 ml-2 animate-pulse"></span>
                    </h2>

                    <!-- Subtitle: ID Operator & Status -->
                    <div class="text-xs text-zinc-400 mono-font mt-2 flex flex-wrap items-center gap-2">
                        <span>Operator ID:</span>
                        <span class="text-amber-300 bg-zinc-800/80 px-2 py-0.5 border border-zinc-700/60 font-medium">{{ caesarCipher(Auth::user()->email, 3) }}</span>
                        <span class="text-zinc-600">•</span>
                        <span>Status:</span>
                        <span class="text-emerald-400 font-bold tracking-wider">AUTHORIZED</span>
                        <span class="text-zinc-600 hidden sm:inline">•</span>
                        <span class="text-zinc-500 hidden sm:inline">[256-BIT_ENCRYPTED]</span>
                    </div>
                </div>

                <!-- Tombol Aksi Cepat -->
                <div class="flex gap-3 text-center w-full lg:w-auto relative z-10 flex-wrap sm:flex-nowrap shrink-0">
                    <a href="{{ route('matkul.create') }}" class="flex-1 lg:flex-none border border-zinc-800 hover:border-amber-500 bg-zinc-950/80 hover:bg-amber-500/10 px-4 py-2.5 transition text-left group">
                        <p class="text-[9px] text-zinc-500 mono-font uppercase tracking-wider">// ACTION_01</p>
                        <p class="text-xs font-bold text-amber-500 mono-font uppercase mt-0.5 group-hover:text-amber-400">[+] ADD_MATKUL</p>
                    </a>
                    <a href="{{ route('tugas.create') }}" class="flex-1 lg:flex-none border border-amber-500/60 hover:border-amber-400 bg-amber-500 hover:bg-amber-400 px-4 py-2.5 transition text-left group shadow-[0_0_15px_rgba(245,158,11,0.25)] relative overflow-hidden">
                        <p class="text-[9px] text-black/70 mono-font uppercase tracking-wider font-bold">// ACTION_02</p>
                        <p class="text-xs font-bold text-black mono-font uppercase mt-0.5">[+] ADD_TUGAS</p>
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-zinc-900 border-l-4 border-emerald-500 text-emerald-400 px-4 py-3 text-xs mono-font uppercase flex justify-between items-center">
                <span>[SUCCESS]: {{ session('success') }}</span>
                <span class="text-[10px] text-zinc-600">SYS_LOG_OK</span>
            </div>
        @endif

        <!-- Panel Status / Metrik Taktis (React Bits: Spotlight Cards + CountUp + Decrypted Text) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="spotlight-card border-glow-cyber bg-zinc-900/80 border border-zinc-800 p-4 relative group">
                <div class="absolute top-0 right-0 w-2 h-2 bg-zinc-700 group-hover:bg-amber-500 transition-colors"></div>
                <div class="relative z-10">
                    <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase cursor-pointer select-none" data-scramble="TOTAL_ASSIGNMENTS">TOTAL_ASSIGNMENTS</p>
                    <p class="text-3xl font-bold text-white mono-font mt-1 tracking-tight" data-countup="{{ $totalTugas ?? 0 }}">{{ $totalTugas ?? 0 }}</p>
                    <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-amber-500 h-0.5 w-full shadow-[0_0_8px_rgba(245,158,11,0.6)]"></div></div>
                </div>
            </div>
            
            <div class="spotlight-card spotlight-card-emerald border-glow-cyber bg-zinc-900/80 border border-zinc-800 p-4 relative group">
                <div class="absolute top-0 right-0 w-2 h-2 bg-emerald-700 group-hover:bg-emerald-400 transition-colors"></div>
                <div class="relative z-10">
                    <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase cursor-pointer select-none" data-scramble="STATUS_COMPLETED">STATUS_COMPLETED</p>
                    <p class="text-3xl font-bold text-emerald-400 mono-font mt-1 tracking-tight" data-countup="{{ $tugasSelesai ?? 0 }}">{{ $tugasSelesai ?? 0 }}</p>
                    <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-emerald-500 h-0.5 w-full shadow-[0_0_8px_rgba(16,185,129,0.6)]"></div></div>
                </div>
            </div>

            <div class="spotlight-card border-glow-cyber bg-zinc-900/80 border border-zinc-800 p-4 relative group">
                <div class="absolute top-0 right-0 w-2 h-2 bg-amber-700 group-hover:bg-orange-500 transition-colors"></div>
                <div class="relative z-10">
                    <p class="text-[10px] mono-font text-zinc-500 tracking-widest uppercase cursor-pointer select-none" data-scramble="PENDING_ACTION">PENDING_ACTION</p>
                    <p class="text-3xl font-bold text-amber-400 mono-font mt-1 tracking-tight" data-countup="{{ $tugasBelum ?? 0 }}">{{ $tugasBelum ?? 0 }}</p>
                    <div class="mt-2 w-full bg-zinc-800 h-0.5"><div class="bg-amber-500 h-0.5 w-full shadow-[0_0_8px_rgba(245,158,11,0.6)]"></div></div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <x-daftar-matkul :mata_kuliah="$mata_kuliah" />
        </div>

        @php
            $hasActiveFilter = request()->filled('search') || request()->filled('matkul_id') || request()->filled('status') || request()->filled('prioritas') || request()->filled('urgency') || (request()->filled('sort') && request('sort') !== 'asc');
        @endphp

        <!-- TABEL DATA TUGAS DENGAN INTEGRATED EXPANDABLE SEARCH & FILTER -->
        <div class="bg-zinc-900/90 border border-zinc-800 overflow-hidden mt-6 shadow-[0_0_30px_rgba(0,0,0,0.5)] border-glow-cyber"
             x-data="{ filterOpen: {{ $hasActiveFilter ? 'true' : 'false' }} }">
                     <!-- Header Table & Trigger Button Search & Filter -->
            <div class="bg-zinc-950 px-6 py-4 border-b border-zinc-800 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                    <h3 class="text-xs font-bold tracking-widest text-zinc-300 uppercase mono-font">
                        DAFTAR TUGAS <span class="text-zinc-600">// ACTIVE_ASSIGNMENTS_DIRECTORY</span>
                    </h3>
                    <span id="total-tasks-badge" class="text-[10px] mono-font text-zinc-400 bg-zinc-900 px-2.5 py-0.5 border border-zinc-800">
                        {{ $tugas->total() }} TASKS
                    </span>
                    <span id="filter-active-indicator" class="text-[10px] mono-font text-amber-400 bg-amber-950/60 border border-amber-500/40 px-2 py-0.5 flex items-center gap-1.5 animate-pulse {{ $hasActiveFilter ? '' : 'hidden' }}">
                        <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                        FILTER_ACTIVE
                    </span>
                </div>

                <!-- Tombol Kecil Expand Search & Filter -->
                <div class="flex items-center gap-2">
                    <button type="button" id="reset-filter-btn" 
                            class="text-[10px] mono-font text-rose-400 hover:text-rose-300 bg-rose-950/40 hover:bg-rose-900/50 border border-rose-800/60 px-2.5 py-1.5 rounded transition flex items-center gap-1 {{ $hasActiveFilter ? '' : 'hidden' }}">
                        <span>[X]</span>
                        <span>RESET</span>
                    </button>

                    <button type="button" 
                            @click="filterOpen = !filterOpen" 
                            class="border border-zinc-700 hover:border-amber-500/80 bg-zinc-900 hover:bg-zinc-800 text-zinc-300 hover:text-amber-400 px-3.5 py-1.5 rounded text-xs mono-font transition flex items-center gap-2 select-none shadow-sm cursor-pointer"
                            :class="filterOpen ? 'border-amber-500 text-amber-400 bg-amber-500/10 shadow-[0_0_15px_rgba(245,158,11,0.15)]' : ''">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="font-bold tracking-wider">SEARCH & FILTER</span>
                        <span class="transition-transform duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] text-[10px] ml-1" :class="filterOpen ? 'rotate-180 text-amber-400' : 'text-zinc-500'">▼</span>
                    </button>
                </div>
            </div>

            <!-- Panel Search & Filter: Ultra Smooth CSS Grid Accordion Transition -->
            <div class="grid transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)] border-b"
                 :class="filterOpen ? 'grid-rows-[1fr] opacity-100 border-zinc-800' : 'grid-rows-[0fr] opacity-0 border-transparent'">
                <div class="overflow-hidden">
                    <div class="bg-zinc-950/80 p-5 backdrop-blur-md relative">
                        <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-amber-500/60 via-amber-500/20 to-transparent"></div>

                        <form id="tasks-filter-form" method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                
                                <!-- 1. Search Query Input -->
                                <div>
                                    <label class="block text-[10px] text-amber-500 mb-1.5 mono-font uppercase tracking-widest">// 01_SEARCH_TASK (Live)</label>
                                    <div class="relative">
                                        <input type="text" id="search-input" name="search" value="{{ request('search') }}" placeholder="Cari nama tugas..." autocomplete="off"
                                               class="w-full bg-zinc-900 border border-zinc-700 text-amber-400 pl-8 pr-3 py-2 text-xs focus:outline-none focus:border-amber-500 mono-font placeholder-zinc-600 transition">
                                        <span class="absolute left-2.5 top-2.5 text-zinc-500 text-xs mono-font font-bold">></span>
                                    </div>
                                </div>

                                <!-- 2. Filter Mata Kuliah -->
                                <div>
                                    <label class="block text-[10px] text-amber-500 mb-1.5 mono-font uppercase tracking-widest">// 02_MATA_KULIAH</label>
                                    <select id="matkul-select" name="matkul_id" 
                                            class="w-full bg-zinc-900 border border-zinc-700 text-amber-400 px-3 py-2 text-xs focus:outline-none focus:border-amber-500 mono-font transition cursor-pointer">
                                        <option value="">[ ALL_MATA_KULIAH ]</option>
                                        @foreach($mata_kuliah as $mk)
                                            <option value="{{ $mk->id }}" {{ request('matkul_id') == $mk->id ? 'selected' : '' }}>
                                                {{ strtoupper($mk->nama_matkul) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 3. Filter Status Penyelesaian -->
                                <div>
                                    <label class="block text-[10px] text-amber-500 mb-1.5 mono-font uppercase tracking-widest">// 03_STATUS_PENYELESAIAN</label>
                                    <select id="status-select" name="status" 
                                            class="w-full bg-zinc-900 border border-zinc-700 text-amber-400 px-3 py-2 text-xs focus:outline-none focus:border-amber-500 mono-font transition cursor-pointer">
                                        <option value="">[ ALL_STATUS ]</option>
                                        <option value="Belum Selesai" {{ request('status') == 'Belum Selesai' ? 'selected' : '' }}>⏳ Belum Selesai (In Progress)</option>
                                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>✓ Selesai (Completed)</option>
                                    </select>
                                </div>

                                <!-- 4. Filter Tingkat Urgensi / Waktu Deadline -->
                                <div>
                                    <label class="block text-[10px] text-amber-500 mb-1.5 mono-font uppercase tracking-widest">// 04_DEADLINE_URGENCY</label>
                                    <select id="urgency-select" name="urgency" 
                                            class="w-full bg-zinc-900 border border-zinc-700 text-amber-400 px-3 py-2 text-xs focus:outline-none focus:border-amber-500 mono-font transition cursor-pointer">
                                        <option value="">[ ALL_TIMELINES ]</option>
                                        <option value="critical" {{ request('urgency') == 'critical' ? 'selected' : '' }}>🚨 HARI INI (&lt; 24 Jam)</option>
                                        <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>⚠️ MENDEKATI DEADLINE (H-3)</option>
                                        <option value="overdue" {{ request('urgency') == 'overdue' ? 'selected' : '' }}>⛔ TERLAMBAT (Overdue)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Baris Bawah: Prioritas, Sorting & Reset -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-3 border-t border-zinc-900">
                                <div class="flex flex-wrap items-center gap-4">
                                    <!-- Filter Prioritas (Tinggi / Sedang / Rendah) -->
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] mono-font text-zinc-500 uppercase tracking-widest">// PRIORITAS:</span>
                                        <select id="prioritas-select" name="prioritas" class="bg-zinc-900 border border-zinc-700 text-amber-400 px-2 py-1 text-xs mono-font cursor-pointer">
                                            <option value="">[ SEMUA ]</option>
                                            <option value="Tinggi" {{ request('prioritas') == 'Tinggi' ? 'selected' : '' }}>🔴 Tinggi</option>
                                            <option value="Sedang" {{ request('prioritas') == 'Sedang' ? 'selected' : '' }}>🟡 Sedang</option>
                                            <option value="Rendah" {{ request('prioritas') == 'Rendah' ? 'selected' : '' }}>🟢 Rendah</option>
                                        </select>
                                    </div>

                                    <!-- Sorting Radio -->
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] mono-font text-zinc-500 uppercase tracking-widest">// URUTAN:</span>
                                        <label class="inline-flex items-center gap-1.5 text-xs mono-font text-zinc-300 cursor-pointer">
                                            <input type="radio" name="sort" value="asc" {{ request('sort', 'asc') == 'asc' ? 'checked' : '' }} class="accent-amber-500">
                                            <span>Deadline Terdekat</span>
                                        </label>
                                        <label class="inline-flex items-center gap-1.5 text-xs mono-font text-zinc-300 cursor-pointer">
                                            <input type="radio" name="sort" value="desc" {{ request('sort', 'desc') == 'desc' ? 'checked' : '' }} class="accent-amber-500">
                                            <span>Deadline Terlama</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dynamic AJAX Table Container -->
            <div id="tasks-table-container">
                @include('tugas.partials.table_content')
            </div>
        </div>

    </main>
    <x-footer />

    <!-- Terminal Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         style="display: none;"
         x-transition.opacity>
        
        <div class="bg-zinc-950 border border-rose-600/60 max-w-md w-full p-6 shadow-[0_0_30px_rgba(225,29,72,0.2)] relative">
            <div class="flex justify-between items-center border-b border-zinc-800 pb-3 mb-4">
                <span class="text-xs font-bold text-rose-500 mono-font uppercase tracking-widest">// WARNING: SYSTEM_DELETE_PROTOCOL</span>
                <button @click="showDeleteModal = false" class="text-zinc-500 hover:text-white mono-font text-xs">[X]</button>
            </div>

            <p class="text-xs text-zinc-300 mono-font mb-6 leading-relaxed">
                [ALERT]: Apakah Anda yakin ingin menghapus data tugas ini dari direktori sistem? Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
            </p>

            <div class="flex justify-end gap-3">
                <button @click="showDeleteModal = false" 
                        class="border border-zinc-700 text-zinc-400 hover:bg-zinc-900 px-4 py-2 text-xs uppercase mono-font transition">
                    [CANCEL]
                </button>

                <form :action="deleteUrl" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="border border-rose-600 text-rose-400 hover:bg-rose-950 px-4 py-2 text-xs uppercase mono-font font-bold transition shadow-[0_0_10px_rgba(225,29,72,0.3)]">
                        [CONFIRM_DELETE]
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Vanilla JS: Realtime Terminal Clock & Aurora Interactive Parallax -->
    <script>
        function updateTerminalClock() {
            const clockEl = document.getElementById('terminal-clock');
            if (!clockEl) return;
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            clockEl.textContent = `${h}:${m}:${s} WIB`;
        }
        setInterval(updateTerminalClock, 1000);
        updateTerminalClock();

        const auroraCard = document.getElementById('cyber-aurora-card');
        const b1 = document.getElementById('blob-1');
        const b2 = document.getElementById('blob-2');
        const b3 = document.getElementById('blob-3');

        if (auroraCard && b1 && b2 && b3) {
            auroraCard.addEventListener('mousemove', (e) => {
                const rect = auroraCard.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                b1.style.transform = `translate(${x * 0.07}px, ${y * 0.07}px)`;
                b2.style.transform = `translate(${-x * 0.05}px, ${-y * 0.05}px)`;
                b3.style.transform = `translate(${x * 0.03}px, ${y * 0.03}px)`;
            });
            auroraCard.addEventListener('mouseleave', () => {
                b1.style.transform = '';
                b2.style.transform = '';
                b3.style.transform = '';
            });
        }

        // 2. React Bits: Spotlight Card Effect (Mouse Tracking Glow)
        function initSpotlight() {
            const cards = document.querySelectorAll('.spotlight-card');
            cards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    card.style.setProperty('--mouse-x', `${x}px`);
                    card.style.setProperty('--mouse-y', `${y}px`);
                });
                card.addEventListener('mouseleave', () => {
                    card.style.setProperty('--mouse-x', `-500px`);
                    card.style.setProperty('--mouse-y', `-500px`);
                });
            });
        }
        initSpotlight();

        // 3. React Bits: CountUp Counter Animation
        function initCountUp() {
            document.querySelectorAll('[data-countup]').forEach(el => {
                const target = parseInt(el.getAttribute('data-countup'), 10) || 0;
                if (target === 0) {
                    el.textContent = '0';
                    return;
                }
                const duration = 1200;
                const start = performance.now();
                function frame(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const ease = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(ease * target);
                    el.textContent = current;
                    if (progress < 1) {
                        requestAnimationFrame(frame);
                    } else {
                        el.textContent = target;
                    }
                }
                requestAnimationFrame(frame);
            });
        }
        initCountUp();

        // 4. React Bits: Decrypted Text (Hacker Terminal Scramble)
        function initDecryptedText() {
            const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!<>#$*&";
            document.querySelectorAll('[data-scramble]').forEach(el => {
                const originalText = el.getAttribute('data-scramble') || el.innerText.trim();
                let isScrambling = false;

                function runScramble() {
                    if (isScrambling) return;
                    isScrambling = true;
                    let iteration = 0;
                    const interval = setInterval(() => {
                        el.innerText = originalText
                            .split("")
                            .map((char, index) => {
                                if (char === " " || index < iteration) {
                                    return originalText[index];
                                }
                                return chars[Math.floor(Math.random() * chars.length)];
                            })
                            .join("");

                        if (iteration >= originalText.length) {
                            clearInterval(interval);
                            el.innerText = originalText;
                            isScrambling = false;
                        }
                        iteration += 1 / 2;
                    }, 30);
                }

                el.addEventListener('mouseenter', runScramble);
                setTimeout(runScramble, 400);
            });
        }
        initDecryptedText();

        // 5. AJAX Live Search & Filter Engine (Smooth Table Update without Page Reload)
        (function() {
            const form = document.getElementById('tasks-filter-form');
            const tableContainer = document.getElementById('tasks-table-container');
            const searchInput = document.getElementById('search-input');
            const resetBtn = document.getElementById('reset-filter-btn');
            const totalBadge = document.getElementById('total-tasks-badge');
            const filterActiveIndicator = document.getElementById('filter-active-indicator');

            let searchTimeout = null;
            let currentAbortController = null;

            function updateUIFilterActiveState() {
                if (!form) return;
                const formData = new FormData(form);
                const search = formData.get('search')?.trim() || '';
                const matkul = formData.get('matkul_id') || '';
                const status = formData.get('status') || '';
                const urgency = formData.get('urgency') || '';
                const prioritas = formData.get('prioritas') || '';
                const sort = formData.get('sort') || 'asc';

                const hasActive = !!(search || matkul || status || urgency || prioritas || (sort && sort !== 'asc'));

                if (filterActiveIndicator) {
                    filterActiveIndicator.classList.toggle('hidden', !hasActive);
                }
                if (resetBtn) {
                    resetBtn.classList.toggle('hidden', !hasActive);
                }
            }

            function fetchTasks(url = null) {
                if (currentAbortController) {
                    currentAbortController.abort();
                }
                currentAbortController = new AbortController();

                // Show loading radar indicator
                const loader = document.getElementById('table-loading-indicator');
                if (loader) {
                    loader.classList.remove('hidden');
                }

                let fetchUrl = url;
                if (!fetchUrl) {
                    const formData = new FormData(form);
                    const params = new URLSearchParams();
                    for (const [key, value] of formData.entries()) {
                        if (value !== null && value !== '') {
                            params.append(key, value);
                        }
                    }
                    const baseUrl = form.getAttribute('action') || window.location.pathname;
                    fetchUrl = `${baseUrl}?${params.toString()}`;
                }

                // Update browser URL query string without reloading page
                window.history.pushState({ path: fetchUrl }, '', fetchUrl);
                updateUIFilterActiveState();

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    signal: currentAbortController.signal
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network error: ' + res.status);
                    return res.text();
                })
                .then(html => {
                    if (tableContainer) {
                        tableContainer.innerHTML = html;
                        // Re-initialize Alpine.js directives on newly injected DOM elements
                        if (window.Alpine) {
                            window.Alpine.initTree(tableContainer);
                        }
                        // Sync total task count badge in header
                        const totalEl = tableContainer.querySelector('#ajax-total-count');
                        if (totalEl && totalBadge) {
                            totalBadge.textContent = `${totalEl.textContent.trim()} TASKS`;
                        }
                    }
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        console.error('Failed to load table content:', err);
                    }
                })
                .finally(() => {
                    const activeLoader = document.getElementById('table-loading-indicator');
                    if (activeLoader) {
                        activeLoader.classList.add('hidden');
                    }
                });
            }

            // Realtime debounced input on search bar (300ms)
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        fetchTasks();
                    }, 300);
                });
            }

            // Realtime trigger on select filters & sorting radio change
            if (form) {
                form.addEventListener('change', (e) => {
                    if (e.target !== searchInput) {
                        fetchTasks();
                    }
                });

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    fetchTasks();
                });
            }

            // Intercept pagination clicks for AJAX page transitions
            if (tableContainer) {
                tableContainer.addEventListener('click', (e) => {
                    const link = e.target.closest('.ajax-pagination a');
                    if (link && link.href) {
                        e.preventDefault();
                        fetchTasks(link.href);
                    }
                });
            }

            // Clean reset handler
            function resetAllFilters() {
                if (!form) return;
                form.reset();
                if (searchInput) searchInput.value = '';
                const selects = form.querySelectorAll('select');
                selects.forEach(s => s.value = '');
                const defaultSort = form.querySelector('input[name="sort"][value="asc"]');
                if (defaultSort) defaultSort.checked = true;
                fetchTasks();
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    resetAllFilters();
                });
            }

            // Synchronize with browser Back and Forward navigation
            window.addEventListener('popstate', () => {
                fetchTasks(window.location.href);
            });
        })();
    </script>
</body>
</html>
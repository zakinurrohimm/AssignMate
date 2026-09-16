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

    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <x-header />

    <main class="max-w-7xl mx-auto px-6 pt-28 pb-16 relative z-20 w-full">

        <!-- Panel Identitas Operator & Tombol Aksi Cepat -->
        <div class="bg-zinc-900/90 border border-zinc-800 p-5 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden shadow-[0_0_20px_rgba(0,0,0,0.3)]">
            <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-zinc-800/20 to-transparent pointer-events-none"></div>

            <div class="relative z-10">
                <h2 class="text-lg font-bold text-amber-500 uppercase mono-font tracking-widest flex items-center gap-2">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                    WELCOME, {{ Auth::user()->name }}
                </h2>
                <p class="text-xs text-zinc-500 mono-font mt-1">
                    Operator ID: <span class="text-zinc-300">{{ caesarCipher(Auth::user()->email, 3) }}</span> | Status: <span class="text-emerald-500">AUTHORIZED</span>
                </p>
            </div>
            
            <div class="flex gap-3 text-center relative z-10 w-full md:w-auto">
                <a href="{{ route('matkul.create') }}" class="flex-1 md:flex-none border border-zinc-800 hover:border-amber-500 bg-zinc-950 hover:bg-amber-500/10 px-4 py-2.5 transition text-left group">
                    <p class="text-[9px] text-zinc-500 mono-font uppercase tracking-wider">// ACTION_01</p>
                    <p class="text-xs font-bold text-amber-500 mono-font uppercase mt-0.5 group-hover:text-amber-400">[+] ADD_MATKUL</p>
                </a>
                <a href="{{ route('tugas.create') }}" class="flex-1 md:flex-none border border-amber-500/60 hover:border-amber-400 bg-amber-500 hover:bg-amber-400 px-4 py-2.5 transition text-left group shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                    <p class="text-[9px] text-black/70 mono-font uppercase tracking-wider font-bold">// ACTION_02</p>
                    <p class="text-xs font-bold text-black mono-font uppercase mt-0.5">[+] ADD_TUGAS</p>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-zinc-900 border-l-4 border-emerald-500 text-emerald-400 px-4 py-3 text-xs mono-font uppercase flex justify-between items-center">
                <span>[SUCCESS]: {{ session('success') }}</span>
                <span class="text-[10px] text-zinc-600">SYS_LOG_OK</span>
            </div>
        @endif

        <!-- Panel Status / Metrik Taktis -->
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

        <div class="mb-8">
            <x-daftar-matkul :mata_kuliah="$mata_kuliah" />
        </div>

        <!-- SEARCH & FILTER RADAR TERMINAL (AUTO-SUBMIT) -->
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6 flex flex-wrap gap-4 items-end border border-amber-500/30 p-4 bg-zinc-900/40 relative">
            <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-amber-500/50 to-transparent"></div>
            
            <!-- Input Search (Tekan Enter untuk mencari) -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] text-amber-500 mb-1 mono-font uppercase tracking-widest">// SEARCH_QUERY (Press Enter)</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tugas..." 
                       class="w-full bg-zinc-950 border border-zinc-700 text-amber-400 px-3 py-2 text-sm focus:outline-none focus:border-amber-500 mono-font placeholder-zinc-700 transition">
            </div>

            <!-- Dropdown Matkul (Auto-Submit) -->
            <div class="w-full md:w-48">
                <label class="block text-[10px] text-amber-500 mb-1 mono-font uppercase tracking-widest">// FILTER_MATKUL</label>
                <select name="matkul_id" onchange="this.form.submit()" class="w-full bg-zinc-950 border border-zinc-700 text-amber-400 px-3 py-2 text-sm focus:outline-none focus:border-amber-500 mono-font transition appearance-none cursor-pointer">
                    <option value="">[ ALL_MATKUL ]</option>
                    @foreach($mata_kuliah as $mk)
                        <option value="{{ $mk->id }}" {{ request('matkul_id') == $mk->id ? 'selected' : '' }}>
                            {{ strtoupper($mk->nama_matkul) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Dropdown Sort (Auto-Submit) -->
            <div class="w-full md:w-48">
                <label class="block text-[10px] text-amber-500 mb-1 mono-font uppercase tracking-widest">// SORT_DEADLINE</label>
                <select name="sort" onchange="this.form.submit()" class="w-full bg-zinc-950 border border-zinc-700 text-amber-400 px-3 py-2 text-sm focus:outline-none focus:border-amber-500 mono-font transition appearance-none cursor-pointer">
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>[ ASC ] Terdekat</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>[ DESC ] Terlama</option>
                </select>
            </div>
        </form>

        <!-- TABEL DATA TUGAS AKTIF (TERMINAL LOG STYLE) -->
        <div class="bg-zinc-900/90 border border-zinc-800 overflow-hidden mt-6">
            <div class="bg-zinc-950 px-6 py-3 border-b border-zinc-800 flex justify-between items-center">
                <h3 class="text-xs font-bold tracking-widest text-zinc-400 uppercase mono-font">DAFTAR TUGAS // ACTIVE_ASSIGNMENTS_DIRECTORY</h3>
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
                                    <span class="{{ $t->badge_color }} px-2 py-1 rounded whitespace-nowrap text-xs">
                                        {{ $t->sisa_waktu }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($t->status == 'Selesai')
                                        <span class="text-emerald-400 text-xs mono-font font-bold">[COMPLETED]</span>
                                    @else
                                        <span class="text-amber-400 text-xs mono-font font-bold">[IN_PROGRESS]</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($t->status != 'Selesai')
                                            <form action="/tugas/{{ $t->id }}/selesai" method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="border border-emerald-600/50 text-emerald-400 hover:bg-emerald-950/50 px-2.5 py-1.5 text-[10px] uppercase mono-font transition">
                                                    SELESAI
                                                </button>
                                            </form>
                                        @endif
                                        <a href="/tugas/{{ $t->id }}/edit" class="border border-blue-900/50 text-blue-400 bg-blue-950/30 hover:bg-blue-900/40 px-3 py-1.5 text-[10px] mono-font uppercase transition flex items-center">
                                            EDIT
                                        </a>
                                        <button type="button" 
                                                @click="showDeleteModal = true; deleteUrl = '/tugas/{{ $t->id }}'" 
                                                class="border border-rose-600/50 text-rose-400 hover:bg-rose-950/50 px-2.5 py-1.5 text-[10px] uppercase mono-font transition flex items-center">
                                            HAPUS
                                        </button>
                                    </div>
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

        <!-- PAGINATION LINKS -->
        <div class="mt-4 text-amber-500 mono-font terminal-pagination">
            {{ $tugas->links() }}
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

</body>
</html>
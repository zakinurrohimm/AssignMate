<style>
    .shiny-text {
        background: linear-gradient(
            120deg,
            #a1a1aa 20%,
            #fbbf24 45%,
            #ffffff 50%,
            #fbbf24 55%,
            #a1a1aa 80%
        );
        background-size: 250% 100%;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shiny-sweep 4s linear infinite;
    }
    @keyframes shiny-sweep {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
<header class="fixed top-0 w-full z-50 bg-[#0b0c10]/90 backdrop-blur-sm border-b border-zinc-800">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Logo & Title (Sekarang bisa diklik untuk kembali ke Home/Dashboard) -->
        <a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none">
            <!-- Logo Gambar -->
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">

            <div>
                <h1 class="text-white font-bold tracking-widest flex items-center gap-2 mono-font">
                    <span class="shiny-text">ASSIGNMATE</span>
                    <span class="text-amber-500 text-xs font-normal hidden sm:inline-block">// ASSIGNMENT MANAGEMENT</span>
                </h1>
                <p class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase mono-font mt-0.5">// ACADEMIC PROTOCOL</p>
            </div>
        </a>

        <!-- Right Nav -->
        <div class="flex items-center gap-4">
            <!-- Blok Identitas & Logout -->
            @auth
                <div class="h-4 w-px bg-zinc-800 hidden md:block"></div>
                
                <!-- Indikator User Aktif -->
                <span class="text-[10px] text-zinc-500 mono-font hidden md:inline-block uppercase tracking-widest">
                    OPR: <span class="text-amber-500">{{ Auth::user()->name }}</span>
                </span>

                <!-- Tombol Logout -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="border border-zinc-800 hover:border-rose-900/80 text-zinc-500 hover:text-rose-400 hover:bg-rose-950/30 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition mono-font flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                        <span class="hidden sm:inline">[DISCONNECT]</span>
                        <span class="sm:hidden">DC</span>
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>
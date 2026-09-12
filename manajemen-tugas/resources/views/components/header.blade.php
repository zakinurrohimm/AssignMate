@props(['showReturn' => false, 'returnUrl' => '/'])

<header class="fixed top-0 w-full z-50 bg-[#0b0c10]/90 backdrop-blur-sm border-b border-zinc-800">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <!-- Logo & Title -->
        <div class="flex items-center gap-3">
            <!-- Logo Gambar -->
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">

            <div>
                <h1 class="text-white font-bold tracking-widest flex items-center gap-2 mono-font">
                    ASSIGNMATE 
                    <span class="text-amber-500 text-xs font-normal hidden sm:inline-block">// ASSIGNMENT MANAGEMENT</span>
                </h1>
                <p class="text-[9px] text-zinc-500 tracking-[0.2em] uppercase mono-font mt-0.5">// ACADEMIC PROTOCOL</p>
            </div>
        </div>

        <!-- Right Nav -->
        <div class="flex items-center gap-4">
            <!-- Tombol Return -->
            @if($showReturn)
                <a href="{{ $returnUrl }}" class="border border-zinc-700 hover:border-amber-500 text-zinc-400 hover:text-amber-400 px-3 py-1.5 text-[10px] font-bold uppercase tracking-widest transition mono-font">
                    [<-] RETURN_COMMAND
                </a>
            @endif

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
                        [DISCONNECT]
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>
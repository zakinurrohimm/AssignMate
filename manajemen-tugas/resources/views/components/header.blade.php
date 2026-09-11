@props(['showReturn' => false, 'returnUrl' => '/'])

<header class="fixed top-0 left-0 right-0 bg-[#0b0c10]/90 backdrop-blur-md border-b border-zinc-800 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <!-- Logo & System ID -->
        <div class="flex items-center gap-4"> 
            <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8">
            <div class="w-3 h-3 bg-amber-500 animate-pulse"></div>
            <div>
                <a href="/" class="flex items-center gap-2 hover:opacity-90 transition">
                    <h1 class="text-xl font-bold tracking-widest text-white uppercase">ASIGNMATE <span class="text-amber-500 font-normal text-xs">// ASSIGNMENT MANAGEMENT</span></h1>
                </a>
                <p class="text-[10px] mono-font text-zinc-500 uppercase tracking-wider">// ACADEMIC PROTOCOL</p>
            </div>
        </div>

        <!-- Bagian Kanan: Bisa Tombol Aksi atau Tombol Return -->
        <div>
            @if($showReturn)
                <a href="{{ $returnUrl }}" class="border border-zinc-700 hover:border-amber-500 hover:text-amber-400 bg-zinc-900/50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider transition mono-font">
                    [←] RETURN_COMMAND
                </a>
            @else
                <div class="flex gap-3">
                    <a href="/matkul/create" class="border border-zinc-700 hover:border-amber-500 hover:text-amber-400 bg-zinc-900/50 px-4 py-1.5 text-xs font-bold uppercase tracking-wider transition mono-font">
                        [+] REG. MATKUL
                    </a>
                    <a href="/tugas/create" class="bg-amber-500 hover:bg-amber-400 text-black px-4 py-1.5 text-xs font-bold uppercase tracking-wider transition shadow-[0_0_15px_rgba(245,158,11,0.3)] mono-font">
                        [+] NEW ASSIGNMENT
                    </a>
                </div>
            @endif
        </div>
    </div>
</header>
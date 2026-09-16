<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ASSIGNMATE - SYSTEM</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body { font-family: 'Montserrat', sans-serif; }
            .mono-font { font-family: 'Share Tech Mono', monospace; }
            .scanlines {
                background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.3));
                background-size: 100% 4px;
            }
            
            /* CSS Tambahan untuk memaksa teks bawaan Breeze jadi gaya terminal */
            .breeze-content p { color: #a1a1aa !important; font-family: 'Share Tech Mono', monospace; font-size: 0.875rem; }
        </style>
    </head>
    <body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-center items-center relative selection:bg-amber-500 selection:text-black p-6">
        
        <!-- Garis Scanlines -->
        <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

        <!-- Kotak Utama Terminal -->
        <div class="w-full max-w-md bg-zinc-900/90 border border-zinc-800 p-8 relative z-20 shadow-[0_0_30px_rgba(0,0,0,0.5)]">
            
            <!-- Header Logo (Menggantikan logo bawaan Laravel) -->
            <div class="border-b border-zinc-800 pb-4 mb-6 text-center">
                <a href="/">
                    <!-- Hapus tanda komentar di bawah ini jika ingin memunculkan logo gambar -->
                    <img src="{{ asset('logo.png') }}" alt="ASSIGNMATE Logo" class="h-16 w-auto mx-auto mb-4">
                    <h1 class="text-3xl font-bold tracking-widest text-amber-500 uppercase mono-font">ASSIGNMATE</h1>
                    <p class="text-xs text-zinc-500 mono-font mt-2">// SYSTEM_AUTHORIZATION_REQUIRED</p>
                </a>
            </div>

            <!-- Konten (Ini tempat masuknya halaman Verifikasi Email) -->
            <div class="breeze-content w-full">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>
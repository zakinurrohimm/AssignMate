<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - OPERATOR_REGISTRATION</title>
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

<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-center items-center relative selection:bg-amber-500 selection:text-black p-4 sm:p-6 overflow-x-hidden">
    
    <!-- Background Grid, Radial Glow & Scanlines -->
    <div class="fixed inset-0 cyber-grid pointer-events-none z-0 opacity-40"></div>
    <div class="fixed inset-0 scanlines pointer-events-none z-10 opacity-30"></div>
    <div class="fixed top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[350px] bg-amber-500/10 rounded-full blur-[110px] pointer-events-none z-0"></div>

    <div class="w-full max-w-md bg-zinc-900/90 border border-zinc-800 rounded-xl p-8 relative z-20 shadow-[0_0_40px_rgba(0,0,0,0.7)] backdrop-blur-xl border-glow-amber my-8">
        
        <!-- Corner HUD Accents -->
        <div class="absolute -top-1 -left-1 w-3 h-3 border-t-2 border-l-2 border-amber-500"></div>
        <div class="absolute -top-1 -right-1 w-3 h-3 border-t-2 border-r-2 border-amber-500"></div>
        <div class="absolute -bottom-1 -left-1 w-3 h-3 border-b-2 border-l-2 border-amber-500"></div>
        <div class="absolute -bottom-1 -right-1 w-3 h-3 border-b-2 border-r-2 border-amber-500"></div>

        <!-- Header -->
        <div class="border-b border-zinc-800 pb-5 mb-6 text-center">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('logo.png') }}" alt="ASSIGNMATE Logo" class="h-14 w-auto drop-shadow-[0_0_12px_rgba(245,158,11,0.3)]">
            </div>
            <h1 class="text-2xl font-bold tracking-widest text-amber-500 uppercase mono-font">ASSIGNMATE</h1>
            <p class="text-[11px] text-zinc-400 mono-font mt-1.5 tracking-wider flex items-center justify-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                // INITIALIZE_NEW_OPERATOR
            </p>
        </div>

        <!-- Validation Error Message -->
        @if ($errors->any())
            <div class="mb-6 bg-rose-950/40 border border-rose-600/60 rounded-lg text-rose-300 p-4 text-xs mono-font shadow-[0_0_15px_rgba(225,29,72,0.15)]">
                <p class="font-bold text-rose-400 mb-1 flex items-center gap-1.5">
                    <span>[!]</span> [REGISTRATION_FAILED]: Validasi Gagal
                </p>
                <ul class="list-disc list-inside space-y-1 text-[11px] text-rose-300/90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <!-- Name -->
            <div>
                <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                    // 01_OPERATOR_NAME
                </label>
                <div class="relative">
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 pl-10 focus:border-amber-500 focus:outline-none transition placeholder:text-zinc-600" 
                           placeholder="Contoh: Alex Mercer">
                    <span class="absolute left-3.5 top-3.5 text-zinc-500 text-sm">👤</span>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                    // 02_OPERATOR_ID (EMAIL)
                </label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 pl-10 focus:border-amber-500 focus:outline-none mono-font transition placeholder:text-zinc-600" 
                           placeholder="operator@assignmate.sys">
                    <span class="absolute left-3.5 top-3.5 text-zinc-500 text-sm">✉</span>
                </div>
            </div>

            <!-- Password with Peek -->
            <div x-data="{ showPass: false }">
                <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                    // 03_PASSCODE (PASSWORD)
                </label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" required
                           class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 pl-10 pr-20 focus:border-amber-500 focus:outline-none mono-font transition placeholder:text-zinc-600" 
                           placeholder="Minimal 8 karakter">
                    <span class="absolute left-3.5 top-3.5 text-zinc-500 text-sm">🔒</span>
                    
                    <button type="button" @click="showPass = !showPass" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold mono-font text-zinc-500 hover:text-amber-400 transition tracking-wider px-2 py-1 bg-zinc-900 border border-zinc-800 rounded">
                        <span x-text="showPass ? '[HIDE]' : '[SHOW]'"></span>
                    </button>
                </div>
            </div>

            <!-- Confirm Password with Peek -->
            <div x-data="{ showConfirm: false }">
                <label class="block text-xs font-bold mono-font text-amber-400 uppercase mb-2 tracking-wider">
                    // 04_CONFIRM_PASSCODE
                </label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                           class="w-full bg-zinc-950 border border-zinc-800 text-zinc-100 text-sm rounded-lg p-3.5 pl-10 pr-20 focus:border-amber-500 focus:outline-none mono-font transition placeholder:text-zinc-600" 
                           placeholder="Ulangi passcode">
                    <span class="absolute left-3.5 top-3.5 text-zinc-500 text-sm">🔒</span>
                    
                    <button type="button" @click="showConfirm = !showConfirm" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold mono-font text-zinc-500 hover:text-amber-400 transition tracking-wider px-2 py-1 bg-zinc-900 border border-zinc-800 rounded">
                        <span x-text="showConfirm ? '[HIDE]' : '[SHOW]'"></span>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-3">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black px-6 py-3.5 text-xs font-bold uppercase tracking-wider transition rounded-lg shadow-[0_0_20px_rgba(245,158,11,0.25)] mono-font flex items-center justify-center gap-2 cursor-pointer">
                    <span>></span>
                    <span>[EXECUTE] REGISTER OPERATOR</span>
                </button>
            </div>

            <!-- Link to Login -->
            <div class="text-center pt-3 border-t border-zinc-800/80">
                <a href="{{ route('login') }}" class="text-xs text-zinc-400 hover:text-amber-400 mono-font transition uppercase tracking-wider inline-flex items-center gap-1.5">
                    <span>// ESTABLISH_CONNECTION (LOGIN)</span>
                    <span class="text-amber-500">→</span>
                </a>
            </div>
        </form>
    </div>

</body>
</html>
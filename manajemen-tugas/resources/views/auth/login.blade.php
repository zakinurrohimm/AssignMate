<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSIGNMATE - LOGIN</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Alpine.js untuk fitur Show/Hide Password -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .mono-font { font-family: 'Share Tech Mono', monospace; }
        .scanlines {
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0.3));
            background-size: 100% 4px;
        }
    </style>
</head>
<body class="bg-[#0b0c10] text-slate-200 min-h-screen flex flex-col justify-center items-center relative selection:bg-amber-500 selection:text-black p-6">
    
    <!-- Garis Scanlines -->
    <div class="absolute inset-0 scanlines pointer-events-none z-10 opacity-40"></div>

    <div class="w-full max-w-md bg-zinc-900/90 border border-zinc-800 p-8 relative z-20 shadow-[0_0_30px_rgba(0,0,0,0.5)]">
        <div class="border-b border-zinc-800 pb-4 mb-6 text-center">
            <h1 class="text-3xl font-bold tracking-widest text-amber-500 uppercase mono-font">ASSIGNMATE</h1>
            <p class="text-xs text-zinc-500 mono-font mt-2">// SYSTEM_AUTHENTICATION_REQUIRED</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-zinc-950 border border-rose-900/60 text-rose-400 p-4 text-xs mono-font">
                <p class="font-bold mb-1">// ACCESS_DENIED:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Operator ID (Email)</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-zinc-950 border border-zinc-800 text-amber-500 text-sm rounded-none p-3 focus:border-amber-500 focus:outline-none mono-font transition placeholder:text-zinc-700" placeholder="operator@assignmate.sys">
            </div>

            <!-- Input Password dengan Fitur Intip -->
            <div x-data="{ showPass: false }">
                <label class="block text-xs font-bold mono-font text-zinc-400 uppercase mb-1">Passcode (Password)</label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" required
                           class="w-full bg-zinc-950 border border-zinc-800 text-amber-500 text-sm rounded-none p-3 pr-20 focus:border-amber-500 focus:outline-none mono-font transition placeholder:text-zinc-700" placeholder="••••••••">
                    
                    <button type="button" @click="showPass = !showPass" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold mono-font text-zinc-500 hover:text-amber-500 transition tracking-wider">
                        <span x-text="showPass ? '[HIDE]' : '[SHOW]'"></span>
                    </button>
                </div>
            </div>

            <!-- Ingat Saya (Remember Me) -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 bg-zinc-950 border-zinc-800 text-amber-500 focus:ring-amber-500 focus:ring-offset-zinc-900 rounded-sm">
                <label for="remember_me" class="ml-2 block text-xs text-zinc-500 mono-font uppercase">
                    [x] MAINTAIN_CONNECTION
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-black px-6 py-3 text-sm font-bold uppercase tracking-wider transition shadow-[0_0_15px_rgba(245,158,11,0.2)] mono-font">
                    [EXECUTE] LOGIN
                </button>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('register') }}" class="text-xs text-amber-500 hover:text-blue-500 mono-font transition uppercase border-b border-transparent hover:border-amber-500 pb-1">
                    // INITIALIZE_NEW_OPERATOR (Register)
                </a>
            </div>
        </form>
    </div>
</body>
</html>
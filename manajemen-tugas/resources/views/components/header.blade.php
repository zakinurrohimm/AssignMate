<!-- resources/views/components/header.blade.php -->
<header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-md border-b border-slate-200 z-50 shadow-sm">
    <div class="max-w-6xl mx-auto px-8 py-3 flex justify-between items-center">
        
        <!-- Logo & Judul Berdampingan -->
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-3 hover:opacity-90 transition">
                <img src="{{ asset('logo.png') }}" alt="Logo AssignMate" class="w-10 h-10 object-contain">
                <div>
                    <h1 class="text-3xl font-bold text-indigo-600 tracking-tight">AssignMate</h1>
                    <p class="text-xs text-slate-500 font-medium">Ayo Nugas Bolo !</p>
                </div>
            </a>
        </div>

        <div class="flex gap-3">
            <a href="/matkul/create" class="bg-slate-800 text-white px-3 py-1.5 rounded-lg hover:bg-slate-900 transition text-sm font-medium shadow-sm">
                + Tambah Matkul
            </a>
            <a href="/tugas/create" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition text-sm font-medium shadow-sm">
                + Tambah Tugas
            </a>
        </div>
    </div>
</header>
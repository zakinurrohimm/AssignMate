<x-guest-layout>
    <div class="text-center py-4">
        <!-- Indikator Status Sukses -->
        <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-950/50 border border-emerald-500/50 rounded-full mb-4 text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-xl font-bold tracking-widest text-emerald-400 uppercase mono-font mb-2">
            // AUTHORIZATION_SUCCESSFUL
        </h2>
        
        <p class="text-xs text-zinc-400 mono-font leading-relaxed mb-6">
            Identitas operator telah diverifikasi secara permanen oleh sistem. Akses penuh ke protokol ASSIGNMATE sekarang telah dibuka.
        </p>

        <div class="border-t border-zinc-800 pt-6">
            <a href="{{ route('dashboard') }}" class="inline-block w-full bg-emerald-500 hover:bg-emerald-400 text-black font-bold py-3 px-4 text-xs uppercase tracking-widest transition shadow-[0_0_15px_rgba(16,185,129,0.3)] mono-font text-center">
                [ACCESS] Masuk ke Terminal Utama
            </a>
        </div>
    </div>
</x-guest-layout>
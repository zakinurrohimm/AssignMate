<x-guest-layout>
    <div class="mb-4 text-sm text-zinc-400 mono-font leading-relaxed">
        // ACCESS_RESTRICTED. <br><br>
        Terima kasih telah mendaftar di sistem. Sebelum memulai eksekusi tugas, harap verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika email tidak masuk, Anda dapat meminta ulang.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-xs font-bold text-emerald-500 mono-font border border-emerald-900/50 bg-emerald-950/30 p-3">
            [SUCCESS] Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat registrasi.
        </div>
    @endif

    <div class="mt-6 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div>
                <button type="submit" class="bg-amber-500 hover:bg-amber-400 text-black px-4 py-2 text-xs font-bold uppercase tracking-wider transition shadow-[0_0_10px_rgba(245,158,11,0.2)] mono-font">
                    [EXECUTE] Kirim Ulang Tautan
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-xs text-rose-500 hover:text-rose-400 mono-font transition uppercase border-b border-transparent hover:border-rose-500 pb-1">
                // DISCONNECT (Logout)
            </button>
        </form>
    </div>
</x-guest-layout>
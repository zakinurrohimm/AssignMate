@props(['mata_kuliah'])

<div class="bg-zinc-900/90 border border-zinc-800 relative rounded-lg border-glow-cyber transition-all duration-300" 
     x-data="{ 
         isOpen: false, 
         deleteModalOpen: false, 
         deleteFormId: '', 
         deleteMatkulName: '' 
     }"
     @mouseenter="isOpen = true"
     @mouseleave="if(!deleteModalOpen) isOpen = false">
    
    <!-- Header Dropdown: Terbuka otomatis saat di-hover (atau diklik) -->
    <div @click="isOpen = !isOpen" 
         class="flex justify-between items-center cursor-pointer p-4 text-zinc-300 hover:bg-zinc-800/40 transition-colors select-none">
        
        <div class="flex items-center gap-2.5">
            <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
            <span class="text-xs font-bold tracking-widest text-amber-500 uppercase mono-font">
                // SYSTEM_DIRECTORY: DAFTAR MATA KULIAH
            </span>
            <span class="text-[10px] mono-font text-zinc-400 bg-zinc-800/80 px-2 py-0.5 rounded border border-zinc-700/50">
                {{ $mata_kuliah->count() }} REGISTERED
            </span>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-[10px] mono-font text-zinc-500 hidden sm:inline tracking-wider">[HOVER_TO_VIEW]</span>
            <span class="transition-transform duration-300 text-amber-500 text-xs inline-block" 
                  :class="isOpen ? 'rotate-180 text-amber-400' : 'text-zinc-500'">
                ▼
            </span>
        </div>
    </div>
    
    <!-- Konten Dropdown (Animasi expand halus saat hover) -->
    <div class="overflow-hidden transition-all duration-300 ease-in-out border-t"
         :class="isOpen ? 'max-h-[500px] opacity-100 border-zinc-800' : 'max-h-0 opacity-0 border-transparent pointer-events-none'">
        
        <div class="p-4 bg-zinc-950/70 max-h-[350px] overflow-y-auto">
            @if($mata_kuliah->isEmpty())
                <div class="py-6 text-center">
                    <p class="text-xs mono-font text-zinc-600">// NO_COURSE_DATA_REGISTERED_IN_DATABASE</p>
                    <a href="{{ route('matkul.create') }}" class="inline-block mt-3 text-xs mono-font text-amber-500 hover:text-amber-400 underline">
                        [+] REGISTER_FIRST_COURSE
                    </a>
                </div>
            @else
                <ul class="space-y-2">
                    @foreach($mata_kuliah as $mk)
                        <li class="flex justify-between items-center bg-zinc-900/90 border border-zinc-800/80 p-3 hover:border-amber-500/40 hover:bg-zinc-900 transition rounded">
                            <div class="flex flex-col">
                                <span class="mono-font font-bold text-amber-400 text-xs tracking-wider">{{ $mk->kode_matkul }}</span>
                                <span class="text-xs text-zinc-300 mt-0.5">{{ $mk->nama_matkul }}</span>
                            </div>
                            
                            <form id="form-delete-{{ $mk->id }}" action="{{ route('matkul.destroy', $mk->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        @click.stop="deleteModalOpen = true; deleteFormId = 'form-delete-{{ $mk->id }}'; deleteMatkulName = '{{ $mk->kode_matkul }} - {{ $mk->nama_matkul }}'" 
                                        class="border border-rose-900/60 text-rose-400 bg-rose-950/30 hover:bg-rose-900/40 px-3 py-1 text-[10px] mono-font uppercase transition rounded">
                                    TERMINATE
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Custom Sci-Fi Modal Konfirmasi Hapus Matkul -->
    <div x-show="deleteModalOpen" 
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         x-transition.opacity
         style="display: none;">
        <div class="bg-zinc-950 border border-rose-900/80 p-6 max-w-md w-full shadow-[0_0_30px_rgba(225,29,72,0.2)]" 
             @click.away="deleteModalOpen = false; isOpen = false">
            <div class="flex items-center gap-2 border-b border-rose-900/50 pb-3 mb-4">
                <span class="w-2 h-2 bg-rose-500 animate-ping"></span>
                <h3 class="text-xs font-bold mono-font text-rose-500 uppercase tracking-widest">// SYSTEM_WARNING: CONFIRM_TERMINATION</h3>
            </div>
            <p class="text-xs text-zinc-300 mb-6 mono-font leading-relaxed">
                Anda akan menghapus entri mata kuliah: <span class="text-amber-400 font-bold" x-text="deleteMatkulName"></span> dari database sistem. Tindakan ini bersifat permanen.
            </p>
            <div class="flex justify-end gap-3 pt-3 border-t border-zinc-900">
                <button type="button" @click="deleteModalOpen = false; isOpen = false" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 px-4 py-2 text-[10px] mono-font uppercase transition">
                    [CANCEL]
                </button>
                <button type="button" @click="document.getElementById(deleteFormId).submit()" class="border border-rose-600 bg-rose-950/50 hover:bg-rose-600 hover:text-white text-rose-400 px-4 py-2 text-[10px] font-bold mono-font uppercase transition">
                    [EXECUTE] TERMINATE
                </button>
            </div>
        </div>
    </div>
</div>
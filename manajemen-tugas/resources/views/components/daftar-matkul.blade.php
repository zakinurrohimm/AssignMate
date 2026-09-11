@props(['mata_kuliah'])
@php $uid = uniqid('toggle-'); @endphp

<div class="bg-zinc-900/90 border border-zinc-800" x-data="{ deleteModalOpen: false, deleteFormId: '', deleteMatkulName: '' }">
    <input type="checkbox" id="{{ $uid }}" class="peer hidden" checked>
    
    <label for="{{ $uid }}" class="flex justify-between items-center cursor-pointer p-4 text-zinc-300 hover:bg-zinc-800/50 transition-colors">
        <span class="text-xs font-bold tracking-widest text-amber-500 uppercase mono-font">[+] SYSTEM_DIRECTORY // DAFTAR MATA KULIAH</span>
        <span class="transition-transform duration-300 peer-checked:rotate-180 text-amber-500">
            ▼
        </span>
    </label>
    
    <div class="max-h-0 opacity-0 overflow-hidden transition-all duration-500 ease-in-out peer-checked:max-h-[500px] peer-checked:opacity-100 border-t border-zinc-800">
        <div class="p-4 bg-zinc-950/60 max-h-[350px] overflow-y-auto">
            @if($mata_kuliah->isEmpty())
                <p class="text-xs mono-font text-zinc-600 text-center py-2">// NO_COURSE_DATA_REGISTERED</p>
            @else
                <ul class="space-y-2">
                    @foreach($mata_kuliah as $mk)
                        <li class="flex justify-between items-center bg-zinc-900 border border-zinc-800 p-3 hover:border-zinc-700 transition">
                            <div class="flex flex-col">
                                <span class="mono-font font-bold text-amber-400 text-xs">{{ $mk->kode_matkul }}</span>
                                <span class="text-xs text-zinc-300">{{ $mk->nama_matkul }}</span>
                            </div>
                            
                            <form id="form-delete-{{ $mk->id }}" action="{{ route('matkul.destroy', $mk->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" @click="deleteModalOpen = true; deleteFormId = 'form-delete-{{ $mk->id }}'; deleteMatkulName = '{{ $mk->kode_matkul }} - {{ $mk->nama_matkul }}'" class="border border-rose-900/50 text-rose-400 bg-rose-950/30 hover:bg-rose-900/40 px-3 py-1 text-[10px] mono-font uppercase transition">
                                    TERMINATE
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Custom Sci-Fi Modal Konfirmasi -->
    <div x-show="deleteModalOpen" 
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4"
         x-transition.opacity
         style="display: none;">
        <div class="bg-zinc-950 border border-rose-900/80 p-6 max-w-md w-full shadow-[0_0_30px_rgba(225,29,72,0.2)]" @click.away="deleteModalOpen = false">
            <div class="flex items-center gap-2 border-b border-rose-900/50 pb-3 mb-4">
                <span class="w-2 h-2 bg-rose-500 animate-ping"></span>
                <h3 class="text-xs font-bold mono-font text-rose-500 uppercase tracking-widest">// SYSTEM_WARNING: CONFIRM_TERMINATION</h3>
            </div>
            <p class="text-xs text-zinc-300 mb-6 mono-font leading-relaxed">
                Anda akan menghapus entri mata kuliah: <span class="text-amber-400 font-bold" x-text="deleteMatkulName"></span> dari database sistem. Operasi ini bersifat permanen.
            </p>
            <div class="flex justify-end gap-3 pt-3 border-t border-zinc-900">
                <button type="button" @click="deleteModalOpen = false" class="border border-zinc-700 hover:border-zinc-500 text-zinc-400 px-4 py-2 text-[10px] mono-font uppercase transition">
                    [CANCEL]
                </button>
                <button type="button" @click="document.getElementById(deleteFormId).submit()" class="border border-rose-600 bg-rose-950/50 hover:bg-rose-600 hover:text-white text-rose-400 px-4 py-2 text-[10px] font-bold mono-font uppercase transition">
                    [EXECUTE] TERMINATE
                </button>
            </div>
        </div>
    </div>
</div>
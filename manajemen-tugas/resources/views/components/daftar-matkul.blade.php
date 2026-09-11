@props(['mata_kuliah'])
@php $uid = uniqid('toggle-'); @endphp

<div class="bg-zinc-900/90 border border-zinc-800">
    <input type="checkbox" id="{{ $uid }}" class="peer hidden">
    
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
                            
                            <form id="form-delete-{{ $mk->id }}" action="/matkul/{{ $mk->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="openModal('form-delete-{{ $mk->id }}')" class="border border-rose-900/50 text-rose-400 bg-rose-950/30 hover:bg-rose-900/40 px-3 py-1 text-[10px] mono-font uppercase transition">
                                    PURGE
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
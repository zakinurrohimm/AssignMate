<div id="tasks-table-content" class="relative transition-opacity duration-200">
    
    <!-- Indikator Loading Scanning Radar -->
    <div id="table-loading-indicator" class="hidden absolute inset-0 bg-black/60 backdrop-blur-sm z-30 flex flex-col items-center justify-center gap-3">
        <div class="relative flex h-8 w-8">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-8 w-8 bg-amber-500/80 items-center justify-center text-black font-bold text-xs">+</span>
        </div>
        <p class="text-xs font-bold text-amber-400 mono-font uppercase tracking-widest animate-pulse">
            // SCANNING_SYSTEM_DIRECTORY...
        </p>
    </div>

    <!-- Table Responsive Wrapper -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-zinc-800 text-[11px] mono-font text-zinc-500 uppercase bg-zinc-950/70">
                    <th class="p-4 tracking-wider">Mata Kuliah</th>
                    <th class="p-4 tracking-wider">Nama Tugas</th>
                    <th class="p-4 tracking-wider">Deadline</th>
                    <th class="p-4 tracking-wider">Status Waktu</th>
                    <th class="p-4 tracking-wider">Status Tugas</th>
                    <th class="p-4 text-right tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/60 text-sm">
                @forelse($tugas as $t)
                    <tr class="hover:bg-zinc-800/40 transition duration-150">
                        <td class="p-4 font-bold text-zinc-300">{{ $t->mataKuliah->nama_matkul ?? '-' }}</td>
                        <td class="p-4 text-white font-semibold">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span>{{ $t->nama_tugas }}</span>
                                @if(isset($t->prioritas))
                                    @if($t->prioritas == 'Tinggi')
                                        <span class="text-[9px] px-1.5 py-0.5 rounded border border-rose-500/50 text-rose-400 bg-rose-950/50 mono-font font-normal tracking-wider">HIGH</span>
                                    @elseif($t->prioritas == 'Sedang')
                                        <span class="text-[9px] px-1.5 py-0.5 rounded border border-amber-500/50 text-amber-400 bg-amber-950/50 mono-font font-normal tracking-wider">MED</span>
                                    @elseif($t->prioritas == 'Rendah')
                                        <span class="text-[9px] px-1.5 py-0.5 rounded border border-zinc-700 text-zinc-400 bg-zinc-900 mono-font font-normal tracking-wider">LOW</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td class="p-4 mono-font text-xs text-zinc-400">{{ $t->deadline }}</td>
                        <td class="p-4">
                            <span class="{{ $t->badge_color }} px-2.5 py-1 rounded whitespace-nowrap text-xs inline-block">
                                {{ $t->sisa_waktu }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($t->status == 'Selesai')
                                <span class="text-emerald-400 text-xs mono-font font-bold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    [COMPLETED]
                                </span>
                            @else
                                <span class="text-amber-400 text-xs mono-font font-bold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    [IN_PROGRESS]
                                </span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-end gap-2">
                                @if($t->status != 'Selesai')
                                    <form action="/tugas/{{ $t->id }}/selesai" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="border border-emerald-600/50 hover:border-emerald-500 text-emerald-400 hover:bg-emerald-950/60 px-2.5 py-1.5 text-[10px] uppercase mono-font transition rounded">
                                            SELESAI
                                        </button>
                                    </form>
                                @endif
                                <a href="/tugas/{{ $t->id }}/edit" class="border border-blue-900/50 hover:border-blue-500 text-blue-400 bg-blue-950/30 hover:bg-blue-900/40 px-3 py-1.5 text-[10px] mono-font uppercase transition flex items-center rounded">
                                    EDIT
                                </a>
                                <button type="button" 
                                        @click="showDeleteModal = true; deleteUrl = '/tugas/{{ $t->id }}'" 
                                        class="border border-rose-600/50 hover:border-rose-500 text-rose-400 hover:bg-rose-950/60 px-2.5 py-1.5 text-[10px] uppercase mono-font transition flex items-center rounded">
                                    HAPUS
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-zinc-600 mono-font text-xs">
                            <p class="text-amber-500/60 mb-1">// NULL_RESULT</p>
                            <span>NO_ACTIVE_ASSIGNMENTS_FOUND_IN_SYSTEM_MATCHING_FILTER</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links Terintegrasi -->
    <div class="px-6 py-4 border-t border-zinc-800 text-amber-500 mono-font terminal-pagination flex justify-between items-center flex-wrap gap-3">
        <span class="text-xs text-zinc-500">
            Total Target: <span id="ajax-total-count" class="text-amber-400 font-bold">{{ $tugas->total() }}</span> Tugas
        </span>
        <div class="ajax-pagination">
            {{ $tugas->links() }}
        </div>
    </div>
</div>

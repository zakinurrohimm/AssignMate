@props(['mata_kuliah'])
@php $uid = uniqid('toggle-'); @endphp

<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8">
    
    <!-- Checkbox Accordion -->
    <input type="checkbox" id="{{ $uid }}" class="peer hidden">
    
    <label for="{{ $uid }}" class="flex justify-between items-center font-medium cursor-pointer p-4 text-slate-800 hover:bg-slate-50 transition-colors rounded-xl peer-checked:rounded-b-none">
        <span class="text-base font-semibold text-indigo-600">📚 Lihat Daftar Mata Kuliah</span>
        <span class="transition-transform duration-300 peer-checked:rotate-180">
            <svg fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
        </span>
    </label>
    
    <div class="max-h-0 opacity-0 overflow-hidden transition-all duration-500 ease-in-out peer-checked:max-h-[500px] peer-checked:opacity-100">
        <div class="px-4 pb-4 text-slate-600 border-t border-slate-100 bg-slate-50/50 pt-4 overflow-y-auto max-h-[400px]">
            @if($mata_kuliah->isEmpty())
                <p class="text-sm text-slate-500 italic text-center">Belum ada mata kuliah yang terdaftar.</p>
            @else
                <ul class="space-y-2">
                    @foreach($mata_kuliah as $mk)
                        <li class="flex justify-between items-center bg-white p-3 rounded-lg border border-slate-200 shadow-sm hover:border-indigo-200 transition">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 text-sm">{{ $mk->kode_matkul }}</span>
                                <span class="text-xs text-slate-500">{{ $mk->nama_matkul }}</span>
                            </div>
                            
                            <!-- Form diubah: onsubmit dihapus, dan ID form ditambahkan -->
                            <form id="form-delete-{{ $mk->id }}" action="/matkul/{{ $mk->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <!-- Tombol diganti menjadi type="button" dan memanggil fungsi JS openModal() -->
                                <button type="button" onclick="openModal('form-delete-{{ $mk->id }}')" class="text-rose-600 hover:bg-rose-100 bg-rose-50 px-3 py-1.5 rounded-md text-xs font-bold transition">
                                    Hapus
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<!-- ================= MODAL KONFIRMASI UI ================= -->
<div id="deleteModal" class="fixed inset-0 z-[999] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <!-- Kotak Modal dengan animasi membesar (scale) -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        
        <!-- Ikon Peringatan -->
        <div class="flex items-center justify-center w-14 h-14 mx-auto bg-rose-100 rounded-full mb-4 shadow-inner">
            <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        
        <h3 class="text-xl font-bold text-center text-slate-800 mb-2">Hapus Mata Kuliah?</h3>
        
        <p class="text-sm text-center text-slate-500 mb-6 leading-relaxed">
            Apakah Anda yakin? Jika ada tugas yang menggunakan matkul ini, <span class="font-bold text-rose-600">aplikasi bisa error atau tugas ikut terhapus</span>. Tindakan ini tidak dapat dibatalkan!
        </p>
        
        <!-- Tombol Aksi -->
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition w-full">
                Batal
            </button>
            <button type="button" onclick="submitDelete()" class="px-5 py-2.5 bg-rose-600 text-white rounded-xl font-semibold hover:bg-rose-700 transition w-full shadow-sm">
                Ya, Hapus!
            </button>
        </div>
    </div>
</div>

<!-- ================= SCRIPT JAVASCRIPT ================= -->
<script>
    let formToSubmit = null; // Menyimpan form mana yang akan dihapus

    function openModal(formId) {
        formToSubmit = document.getElementById(formId);
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        
        // Tampilkan background gelap
        modal.classList.remove('hidden');
        
        // Beri sedikit jeda agar transisi CSS berjalan mulus
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        
        // Animasi mengecil dan memudar
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        // Sembunyikan sepenuhnya setelah animasi selesai (300ms)
        setTimeout(() => {
            modal.classList.add('hidden');
            formToSubmit = null;
        }, 300);
    }

    function submitDelete() {
        if(formToSubmit) {
            formToSubmit.submit(); // Jalankan proses hapus (submit form)
        }
    }
</script>
<div x-data="{ 
        progress: 0, 
        isLoading: true,
        init() {
            let interval = setInterval(() => {
                this.progress += 25;
                if (this.progress >= 100) {
                    this.progress = 100;
                    clearInterval(interval);
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 300);
                }
            }, 100);
        }
     }" 
     x-show="isLoading"
     x-transition:leave="transition ease-out duration-500"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-105"
     class="fixed inset-0 z-[9999] bg-[#0b0c10] text-slate-200 select-none overflow-hidden flex flex-col justify-between p-8 md:p-16">
    
    <!-- Bagian Atas: Info Sistem -->
    <div class="flex justify-between items-center text-[10px] mono-font text-zinc-500 tracking-widest uppercase">
        <span>INITIALIZING_SYS_KERNEL</span>
        <span>SECURE_GATEWAY_v0.3</span>
    </div>

    <!-- Bagian Tengah: Branding -->
    <div class="flex flex-col items-end justify-end text-right space-y-2">
        <div class="flex items-center gap-3">
            <span class="w-2 h-2 bg-amber-500 animate-ping"></span>
            <h2 class="text-2xl md:text-3xl font-bold tracking-widest text-white uppercase font-sans">PROTOCOL <span class="text-amber-500">//</span> ASSIGNMATE</h2>
        </div>
        <p class="text-xs mono-font text-zinc-500 tracking-wider">ACADEMIC / PROTOCOL</p>
    </div>

    <!-- Bagian Bawah: Bar & Persentase -->
    <div class="flex flex-col gap-3 max-w-xl">
        <div class="flex justify-between items-center text-xs mono-font text-zinc-400">
            <span class="uppercase tracking-widest">Loading Protocol...</span>
            <span class="text-amber-500 font-bold" x-text="progress + '%'">0%</span>
        </div>

        <div class="w-full bg-zinc-900 h-2 relative overflow-hidden border border-zinc-800">
            <div class="absolute top-0 left-0 bottom-0 bg-amber-500 transition-all duration-100 shadow-[0_0_15px_rgba(245,158,11,0.5)] flex items-center justify-end" 
                 :style="'width: ' + progress + '%'">
                <div class="w-1 h-full bg-white animate-pulse"></div>
            </div>
        </div>
    </div>
</div>
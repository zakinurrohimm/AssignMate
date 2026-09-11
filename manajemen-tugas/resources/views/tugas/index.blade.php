<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssignMate - Manajemen Tugas Kuliah</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Memanggil Header Modular -->
    <x-header />
    
    <!-- Main Content (Diberi pt-24 agar konten tidak tertutup header yang melayang) -->
    <main class="flex-grow max-w-6xl w-full mx-auto p-8 pt-24">
        @if (session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center justify-between">
                <span class="text-sm font-medium">✨ {{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm flex items-center justify-between">
                <span class="text-sm font-medium">⚠️ {{ session('error') }}</span>
            </div>
        @endif
        <!-- Kotak Statistik Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-500">Total Tugas</p>
                <h3 class="text-3xl font-bold text-slate-800 mt-2">{{ $totalTugas ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-500">Tugas Selesai</p>
                <h3 class="text-3xl font-bold text-emerald-600 mt-2">{{ $tugasSelesai ?? 0 }}</h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-500">Belum Dikerjakan</p>
                <h3 class="text-3xl font-bold text-amber-600 mt-2">{{ $tugasBelum ?? 0 }}</h3>
            </div>
        </div>
        
        <!-- Memanggil Komponen Daftar Mata Kuliah -->
        <x-daftar-matkul :mata_kuliah="$mata_kuliah" />

        <!-- Tabel Data Tugas -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 overflow-x-auto">
            <h2 class="text-xl font-semibold mb-6">Daftar Tugas Aktif</h2>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-sm uppercase tracking-wide text-slate-500">
                        <th class="p-4 border-b">Mata Kuliah</th>
                        <th class="p-4 border-b">Nama Tugas</th>
                        <th class="p-4 border-b">Deadline</th>
                        <th class="p-4 border-b">Sisa Waktu</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugas as $item)
                        <tr class="hover:bg-slate-50 transition border-b border-slate-100">
                            <td class="p-4">{{ $item->mataKuliah->nama_matkul }}</td>
                            <td class="p-4 font-medium">{{ $item->nama_tugas }}</td>
                            <td class="p-4 text-slate-500 text-sm">
                                {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y, H:i') }}
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->badge_color }}">
                                    {{ $item->sisa_waktu }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <form action="/tugas/{{ $item->id }}/selesai" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium bg-emerald-50 px-3 py-1 rounded-md transition" title="Tandai Selesai">
                                            ✓ Selesai
                                        </button>
                                    </form>

                                    <form action="/tugas/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 text-sm font-medium bg-rose-50 px-3 py-1 rounded-md transition" title="Hapus Tugas">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-4 border-b text-center text-slate-400" colspan="6">
                                Belum ada tugas yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer di Paling Bawah -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-6">
        <div class="max-w-6xl mx-auto px-8 text-center text-sm text-slate-500">
            &copy; 2026 Manajemen Tugas Kuliah. <a href="https://github.com/zakinurrohimm" class="text-indigo-600 hover:text-indigo-800" target="_blank">github.com/zakinurrohimm</a>
        </div>
    </footer>

</body>
</html>
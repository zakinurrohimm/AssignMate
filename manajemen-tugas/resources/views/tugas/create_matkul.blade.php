<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssignMate - Tambah Mata Kuliah Baru</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 text-slate-800 p-8 pt-28 min-h-screen">
    
    <!-- Memanggil Header Modular -->
    <x-header />

    <main class="max-w-5xl mx-auto">
        
        <!-- Notifikasi Sukses & Error dari Controller -->
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

        <!-- Grid Layout: Form di Kiri (lebih lebar), Daftar Matkul di Kanan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Bagian Form (Kiri) -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-fit">
                <h2 class="text-2xl font-bold mb-6 text-indigo-600">Tambah Mata Kuliah Baru</h2>
                
                <!-- Peringatan Error Validasi (Form kosong / kode kembar) -->
                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm">
                        <p class="font-semibold mb-1">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/matkul" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Kode Mata Kuliah</label>
                        <input type="text" name="kode_matkul" value="{{ old('kode_matkul') }}" placeholder="Contoh: IF101" class="w-full border @error('kode_matkul') border-rose-500 bg-rose-50 @else border-slate-300 @enderror rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                        @error('kode_matkul')
                            <span class="text-xs text-rose-600 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Mata Kuliah</label>
                        <input type="text" name="nama_matkul" value="{{ old('nama_matkul') }}" placeholder="Contoh: Pemrograman Web" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Dosen (Opsional)</label>
                        <input type="text" name="dosen" value="{{ old('dosen') }}" placeholder="Contoh: Budi S.Kom, M.T" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200">
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">Simpan Matkul</button>
                        <a href="/" class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition text-center">Batal</a>
                    </div>
                </form>
            </div>

            <!-- Bagian Daftar Matkul (Kanan) -->
            <div class="md:col-span-1 h-fit">
                <x-daftar-matkul :mata_kuliah="$mata_kuliah" />
            </div>

        </div>
    </main>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssignMate - Tambah Tugas Baru</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-800 p-8 pt-28 min-h-screen">
    
    <!-- Memanggil Header Modular -->
    <x-header />

    <!-- Grid Layout: Form di Kiri, Daftar Matkul di Kanan -->
    <main class="max-w-5xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Bagian Form (Kiri) -->
            <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-fit">
                <h2 class="text-2xl font-bold mb-6 text-indigo-600">Tambah Tugas Baru</h2>
                
                @if ($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/tugas" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium">Mata Kuliah</label>
                            <a href="/matkul/create" class="text-xs text-indigo-600 hover:underline font-medium">+ Tambah Matkul Baru</a>
                        </div>
                        <select name="mata_kuliah_id" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                            <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                            @foreach($mata_kuliah as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode_matkul }} - {{ $mk->nama_matkul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Tugas</label>
                        <input type="text" name="nama_tugas" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Tenggat Waktu (Deadline)</label>
                        <input type="datetime-local" name="deadline" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan Tugas</button>
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
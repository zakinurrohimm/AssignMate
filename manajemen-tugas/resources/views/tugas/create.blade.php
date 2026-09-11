<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas Baru</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 text-slate-800 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-2xl font-bold mb-6 text-indigo-600">Tambah Tugas Baru</h2>
        
        <form action="/tugas" method="POST" class="space-y-4">
            @csrf <!-- Wajib ada di Laravel untuk keamanan form -->
            
            <div>
                <label class="block text-sm font-medium mb-1">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="w-full border border-slate-300 rounded-lg p-2 focus:ring focus:ring-indigo-200" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach($mata_kuliah as $mk)
                        <option value="{{ $mk->id }}">{{ $mk->nama_matkul }}</option>
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

            <div class="flex gap-4">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Simpan Tugas</button>
                <a href="/" class="px-6 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 30px 40px;
        }

        .form-container {
            max-width: 600px;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        label {
            display: block;
            margin: 18px 0 8px;
        }

        input, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
        }

        button, .kembali {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 18px;
            background: #4f46e5;
            color: white;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Tambah Kategori</h1>

    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf

        <label for="nama_kategori">Nama Kategori</label>
        <input
            type="text"
            id="nama_kategori"
            name="nama_kategori"
            value="{{ old('nama_kategori') }}"
            required
        >

        @error('nama_kategori')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="deskripsi">Deskripsi</label>
        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="4"
        >{{ old('deskripsi') }}</textarea>

        @error('deskripsi')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Simpan Kategori</button>
        <a href="{{ route('kategori.index') }}" class="kembali">
            Kembali
        </a>
    </form>
</div>

</body>
</html>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>

    <style>
        body {
            margin: 0;
            padding: 30px 40px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .form-container {
            max-width: 600px;
            padding: 25px;
            background: white;
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
            border: none;
            border-radius: 7px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .kembali {
            background: #64748b;
        }

        .error {
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h1>Edit Kategori</h1>

    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="nama_kategori">Nama Kategori</label>
        <input
            type="text"
            id="nama_kategori"
            name="nama_kategori"
            value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
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
        >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>

        @error('deskripsi')
            <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Simpan Perubahan</button>

        <a href="{{ route('kategori.index') }}" class="kembali">
            Kembali
        </a>
    </form>
</div>

</body>
</html>

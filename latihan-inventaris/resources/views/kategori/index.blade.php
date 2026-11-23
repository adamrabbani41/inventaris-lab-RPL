
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        nav {
            background: #4f46e5;
            padding: 25px 40px;
            display: flex;
            gap: 30px;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 22px;
            font-weight: bold;
        }

        nav a:hover {
            color: #c7d2fe;
        }

        .container {
            padding: 30px 40px;
        }

        .tambah {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 14px 22px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .tambah:hover {
            background: #4338ca;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #e2e8f0;
        }

        .success {
            color: green;
            margin-bottom: 15px;
        }

        .error {
            color: #dc2626;
            margin-bottom: 15px;
        }

        .aksi {
            white-space: nowrap;
        }

        .edit {
            display: inline-block;
            background: #f59e0b;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit:hover {
            background: #d97706;
        }

        .hapus {
            background: #dc2626;
            color: white;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .hapus:hover {
            background: #b91c1c;
        }

        form {
            display: inline-block;
            margin: 0;
        }
    </style>
</head>

<body>

<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('barang.index') }}">Barang</a>
    <a href="{{ route('kategori.index') }}">Kategori</a>
</nav>

<div class="container">

    <h1>Data Kategori</h1>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <a href="{{ route('kategori.create') }}" class="tambah">
        + Tambah Kategori
    </a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($kategoris as $kategori)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $kategori->nama_kategori }}
                    </td>

                    <td>
                        {{ $kategori->deskripsi ?? '-' }}
                    </td>

                    <td class="aksi">
                        <a
                            href="{{ route('kategori.edit', $kategori->id) }}"
                            class="edit"
                        >
                            Edit
                        </a>

                        <form
                            action="{{ route('kategori.destroy', $kategori->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="hapus">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        Belum ada kategori.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>

</body>
</html>

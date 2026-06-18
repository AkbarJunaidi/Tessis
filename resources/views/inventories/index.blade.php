<!DOCTYPE html>
<html>
<head>
    <title>Data Inventory</title>
</head>
<body>

<h1>Data Inventory</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<a href="{{ route('inventories.create') }}">
    Tambah Barang
</a>

<br><br>

<table border="1" cellpadding="10">

    <thead>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Barang</th>
            <th>Serial Number</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    @forelse($inventories as $inventory)

        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>
                @if(!empty($inventory->image))

                    <img
                        src="{{ asset('storage/'.$inventory->image) }}"
                        alt="Gambar Barang"
                        width="100"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">

                    <span style="display:none;color:red;">
                        Gambar tidak ditemukan
                    </span>

                @else

                    Tidak ada gambar

                @endif
            </td>

            <td>{{ $inventory->name }}</td>

            <td>{{ $inventory->serial_number }}</td>

            <td>

                <a href="{{ route('inventories.edit', $inventory->id) }}">
                    Edit
                </a>

                <form action="{{ route('inventories.destroy', $inventory->id) }}"
                      method="POST"
                      style="display:inline;">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                        Hapus
                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>
            <td colspan="5">
                Data belum tersedia.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>

</body>
</html>
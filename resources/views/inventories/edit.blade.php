<!DOCTYPE html>
<html>
<head>
    <title>Edit Inventory</title>
</head>
<body>

<h1>Edit Inventory</h1>

<a href="{{ route('inventories.index') }}">
    Kembali
</a>

<br><br>

@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('inventories.update', $inventory->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <label>Nama Barang</label>
    <br>
    <input
        type="text"
        name="name"
        value="{{ old('name', $inventory->name) }}">
    <br><br>

    <label>Gambar Saat Ini</label>
    <br>

    @if($inventory->image)

        <img
            src="{{ asset('storage/' . $inventory->image) }}"
            alt="Inventory Image"
            width="150">

    @else

        <p>Tidak ada gambar.</p>

    @endif

    <br><br>

    <label>Ganti Gambar</label>
    <br>
    <input type="file" name="image">
    <br><br>

    <label>Deskripsi</label>
    <br>
    <textarea name="description">{{ old('description', $inventory->description) }}</textarea>
    <br><br>

    <label>Serial Number</label>
    <br>
    <input
        type="text"
        name="serial_number"
        value="{{ old('serial_number', $inventory->serial_number) }}">
    <br><br>

    <button type="submit">
        Update
    </button>

</form>

</body>
</html>
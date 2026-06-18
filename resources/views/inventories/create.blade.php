<!DOCTYPE html>
<html>
<head>
    <title>Add Inventory</title>
</head>
<body>

<h1>Add Inventory</h1>

<a href="{{ route('inventories.index') }}">
    Kembali ke Data Inventory
</a>

<br><br>

<form action="{{ route('inventories.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <label>Nama Barang</label>
    <br>
    <input type="text" name="name">
    <br><br>

    <label>Gambar Barang</label>
    <br>
    <input type="file" name="image">
    <br><br>

    <label>Deskripsi</label>
    <br>
    <textarea name="description"></textarea>
    <br><br>

    <label>Serial Number</label>
    <br>
    <input type="text" name="serial_number">
    <br><br>

    <button type="submit">
        Save
    </button>

</form>

</body>
</html>
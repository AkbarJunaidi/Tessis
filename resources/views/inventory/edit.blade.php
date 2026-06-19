@extends('layouts.app')

@section('title', 'Edit Aset - ' . $inventory->name)

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Edit Inventory Data</h3>
            <p class="text-muted small m-0">Perbarui informasi nama, nomor seri, atau ganti lampiran berkas gambar fisik aset.</p>
        </div>
        <a href="{{ route('inventory.show', $inventory->id) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 fw-medium">
            <i class="bi bi-arrow-left"></i> Batal / Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-4">

            <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold small text-secondary">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $inventory->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="serial_number" class="form-label fw-semibold small text-secondary">Serial Number (Nomor Seri Unik) <span class="text-danger">*</span></label>
                        <input type="text"
                               name="serial_number"
                               id="serial_number"
                               class="form-control @error('serial_number') is-invalid @enderror"
                               value="{{ old('serial_number', $inventory->serial_number) }}"
                               required>
                        @error('serial_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 mt-2">
                    <label class="form-label fw-semibold small text-secondary d-block">Berkas Gambar Fisik Saat Ini</label>
                    @if($inventory->image)
                        <div class="mb-3 p-2 bg-light rounded border border-dashed text-start" style="max-width: 200px;">
                            <img src="{{ asset('storage/' . $inventory->image) }}" alt="Current Image" class="img-thumbnail img-fluid">
                            <small class="text-muted d-block mt-1 text-center"><i class="bi bi-info-circle me-1"></i>Gambar Terpasang</small>
                        </div>
                    @endif

                    <label for="image" class="form-label fw-semibold small text-secondary">Ganti / Unggah Gambar Baru</label>
                    <input type="file"
                        name="image"
                        id="image"
                        class="form-control @error('image') is-invalid @enderror"
                        accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="form-text text-muted small mt-1">
                        <i class="bi bi-info-circle me-1"></i> *Kosongkan jika tidak ingin mengubah foto. Format: <strong>jpeg, png, jpg, webp</strong>. Maksimal: <strong>5 MB</strong>.
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="border-light my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('inventory.index') }}" class="btn btn-light px-4 fw-medium">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-medium shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

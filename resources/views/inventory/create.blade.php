@extends('layouts.app')

@section('title', 'Tambah Inventory Baru')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark m-0">Add New Inventory</h3>
            <p class="text-muted small m-0">Daftarkan aset barang fisik baru ke dalam sistem digital manajemen.</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2 fw-medium">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3 bg-white">
        <div class="card-body p-4">

            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold small text-secondary">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Contoh: Laptop ASUS ROG Strix"
                               value="{{ old('name') }}"
                               required
                               autofocus>
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
                               placeholder="Contoh: SN-ROG-2026XYZ"
                               value="{{ old('serial_number') }}"
                               required>
                        @error('serial_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label fw-semibold small text-secondary">Upload Gambar Barang</label>
                    <input type="file"
                           name="image"
                           id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/jpg,image/webp">
                    <div class="form-text text-muted small mt-1">
                        <i class="bi bi-info-circle me-1"></i> *Opsional (Boleh dikosongkan). Format berkas: <strong>jpeg, png, jpg, webp</strong>. Maksimal ukuran: <strong>5 MB</strong>.
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <hr class="border-light my-4">

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light px-4 fw-medium">Reset</button>
                    <button type="submit" class="btn btn-primary px-4 fw-medium shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Save Inventory
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection

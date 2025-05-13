<form id="schoolForm" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="nama">Nama</label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
            value="{{ old('nama', $sekolah->nama) }}" required>
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="npsn">NPSN</label>
        <input type="number" class="form-control @error('npsn') is-invalid @enderror" id="npsn" name="npsn"
            value="{{ old('npsn', $sekolah->npsn) }}" required>
        @error('npsn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="nsm">NSM</label>
        <input type="number" class="form-control @error('nsm') is-invalid @enderror" id="nsm" name="nsm"
            value="{{ old('nsm', $sekolah->nsm) }}" required>
        @error('nsm')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" required>{{ old('alamat', $sekolah->alamat) }}</textarea>
        @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="opening">Jam Buka Sekolah</label>
        <textarea class="form-control @error('opening') is-invalid @enderror" id="opening" name="opening" required>{{ old('opening', $sekolah->opening) }}</textarea>
        @error('opening')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="notelp">Nomor Telepon</label>
        <input type="number" class="form-control @error('notelp') is-invalid @enderror" id="notelp" name="notelp"
            value="{{ old('notelp', $sekolah->notelp) }}" required>
        @error('notelp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            value="{{ old('email', $sekolah->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="website">Website</label>
        <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" name="website"
            value="{{ old('website', $sekolah->website) }}" required>
        @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="kepala_sekolah_id">Kepala Sekolah</label>
        <select name="kepala_sekolah_id" id="kepala_sekolah_id"
            class="form-control @error('kepala_sekolah_id') is-invalid @enderror">
            <option disabled selected>Pilih salah satu</option>
            @foreach ($guru as $item)
                <option value="{{ $item->id }}" {{ $item->id == $sekolah->kepala_sekolah_id ? 'selected' : '' }}>
                    {{ $item->nama_lengkap }} {{ $item->gelar_belakang }}
                </option>
            @endforeach
        </select>
        @error('kepala_sekolah_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="bendahara_id">Bendahara</label>
        <select name="bendahara_id" id="bendahara_id" class="form-control @error('bendahara_id') is-invalid @enderror">
            <option disabled selected>Pilih salah satu</option>
            @foreach ($guru as $item)
                <option value="{{ $item->id }}" {{ $item->id == $sekolah->bendahara_id ? 'selected' : '' }}>
                    {{ $item->nama_lengkap }} {{ $item->gelar_belakang }}
                </option>
            @endforeach
        </select>
        @error('bendahara_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Other fields remain the same -->
    <div class="form-group">
        <label for="logo">Logo</label>
        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
        @if ($sekolah->logo)
            <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo" width="100" class="mt-2">
        @endif
        @error('logo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#schoolForm').on('submit', function(e) {
                e.preventDefault();

                // Show SweetAlert loading
                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                // AJAX request
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('sekolah.update', $sekolah->id) }}", // Update the route as needed
                    method: "POST", // Use POST instead of PUT because Laravel uses POST for method spoofing
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close(); // Close the loading
                        if (response.success) {
                            Swal.fire(
                                'Success!',
                                'Data has been successfully updated.',
                                'success'
                            ).then(() => {
                                window.location
                                    .reload(); // Optional: reload the page after success
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'There was an issue with your submission.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Close the loading
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again later.',
                            'error'
                        );
                    }
                });
            });
        });
    </script>
@endpush

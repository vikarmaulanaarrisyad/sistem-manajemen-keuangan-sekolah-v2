<form id="sambutanForm" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="sambutan">Sambutan Kepala Madrasah</label>
        <textarea name="sambutan" id="sambutan" cols="50" rows="50" class="summernote">{{ $sekolah->sambutan }}</textarea>
        @error('sambutan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

@include('includes.summernote')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#sambutanForm').on('submit', function(e) {
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

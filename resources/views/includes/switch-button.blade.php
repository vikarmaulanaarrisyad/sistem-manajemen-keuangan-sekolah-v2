@push('scripts_vendor')
    <!-- Bootstrap Switch CSS -->
    <link href="{{ asset('AdminLTE') }}/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet">
    <!-- Bootstrap Switch JS -->
    <script src="{{ asset('AdminLTE') }}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
@endpush

@push('scripts')
    <script>
        // Inisialisasi bootstrap switch untuk semua elemen dengan atribut data-bootstrap-switch
        $(document).ready(function() {
            // Inisialisasi bootstrap switch
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });
        });
    </script>
@endpush

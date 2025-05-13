@push('css_vendor')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('/AdminLTE') }}/plugins/daterangepicker/daterangepicker.css">
@endpush

@push('scripts_vendor')
    <!-- date-range-picker -->
    <script src="{{ asset('/AdminLTE') }}/plugins/daterangepicker/daterangepicker.js"></script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tanggal').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            $('#tanggal').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });

            $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    </script>
@endpush

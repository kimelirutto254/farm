
<!--begin::Footer-->
<script src="{{ asset('landing/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{ asset('landing/js/scripts.bundle.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Resources -->
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

       <script>
    $(document).on(
        "click",
        'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
        function () {
            var title = $(this).data("title");
            var size = $(this).data("size") == "" ? "md" : $(this).data("size");
            var url = $(this).data("url");
            $("#commonModal .modal-title").html(title);
            $("#commonModal .modal-dialog").addClass("modal-" + size);
            $.ajax({
                url: url,
                success: function (data) {
                    setTimeout(function () {
                        $("#commonModal .body").html(data);
                        $("#commonModal").modal("show");

                        // Initialize Select2 inside the modal
                        $('#commonModal select[data-control="select2"]').select2({
                            placeholder: function(){
                                return $(this).data('placeholder');
                            },
                            minimumResultsForSearch: Infinity
                        });

                    }, 600);
                },
                error: function (data) {
                    data = data.responseJSON;
                },
            });
        }
    );
</script>

@if (Session::has('success'))
    <script>
        toastr.success('{{ __('Success') }}', '{!! session('success') !!}');
    </script>
    {{ Session::forget('success') }}
@endif

@if (Session::has('error'))
    <script>
        toastr.error('{{ __('Error') }}', '{!! session('error') !!}');
    </script>
    {{ Session::forget('error') }}
@endif

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
</script>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script>
    $(document).ready(function() {
        $('#example2').DataTable();
    });
</script>



@stack('script-page')


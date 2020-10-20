        <!-- JAVASCRIPT -->
        <script src="{{ URL::asset('backend/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/metismenu/metismenu.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/node-waves/node-waves.min.js')}}"></script>
        <script type="text/javascript">$(function() { $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}}); });</script>

        <!-- Plugins DataTables js -->
        <script src="{{ URL::asset('backend/libs/datatables/datatables.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/pdfmake/pdfmake.min.js')}}"></script>

        <!-- Magnific Popup -->
        <script src="{{ URL::asset('backend/libs/toastr/toastr.min.js')}}"></script>

        @if (session('alert'))
        <span style="display:none" id="toastr">{!! session('alert.msg') !!}</span>
        <script type="text/javascript">$(function() { toastr["{{ strtolower(session('alert.status')) }}"]($('#toastr').html()); });</script>
        @endif

        <!-- App js -->
        <script src="{{ URL::asset('backend/js/app.min.js')}}"></script>

        <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>

        @yield('script')
        @yield('script-bottom')

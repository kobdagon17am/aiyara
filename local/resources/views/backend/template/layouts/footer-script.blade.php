        <!-- JAVASCRIPT -->
        <script src="{{ URL::asset('backend/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/metismenu/metismenu.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ URL::asset('backend/libs/node-waves/node-waves.min.js')}}"></script>

        @yield('script')

        <!-- App js -->
        <script src="{{ URL::asset('backend/js/app.min.js')}}"></script>
        
        @yield('script-bottom')
        <!-- JAVASCRIPT -->
        <!-- <script src="{{ URL::asset('backend/libs/jquery/jquery.min.js')}}"></script> -->
        <script
              src="https://code.jquery.com/jquery-3.5.1.js"
              integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
              crossorigin="anonymous"></script>


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

        <script type="text/javascript">
                $("html").dblclick(function(event) {
                   // alert('xxxx');
                   $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
              });
        </script>


        <script src="{{ URL::asset('backend/libs/select2/select2.min.js')}}"></script>
        <script>
              $('.select2-templating').select2();
        </script>  

        <script type="text/javascript">
            $('.NumberOnly').on('keyup',function(){
                  if(isNaN(this.value)){ alert('!!! กรุณากรอกเป็น ตัวเลข'); this.value='0';$(this).select();}
            });
        </script>



        <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>



        <script type="text/javascript">

            $(document).on('click', '.click_link', function(event) {

                // event.preventDefault();

                // alert("xxxxxx");
                var role_group_id = "{{!empty(\Auth::user()->role_group_id_fk)?\Auth::user()->role_group_id_fk:0}}";

                sessionStorage.setItem("role_group_id", role_group_id);

                var menu_id = $(this).data('id');
                sessionStorage.setItem("menu_id", menu_id);

                $.ajax({

                       type:'POST',
                       url: " {{ url('backend/ajaxSetSession') }} ", 
                       data:{ _token: '{{csrf_token()}}',session_menu_id:menu_id },
                        success:function(data){
                             console.log(data); 
                             // return false;
                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                  });                    


                // alert(role_group_id+":"+menu_id);

                localStorage.clear();

            });

            // console.log("jQuery version "+jQuery().jquery);

        </script>

        
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('backend/libs/sweetalert2/sweetalert2.min.js')}}"></script>

        <!-- Sweet alert init js -->
        <script src="{{ URL::asset('backend/js/pages/sweet-alerts.init.js')}}"></script> 
        <script src="{{ URL::asset('backend/js/pages/jquery.idle.js')}}"></script> 


        @yield('script')
        @yield('script-bottom')

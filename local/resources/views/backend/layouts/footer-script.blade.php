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
              // $(".select2-templating").select2({
              //    minimumInputLength: 2,
              //    allowClear: true,
              // });
        </script>  

        <script type="text/javascript">
            $('.NumberOnly').on('keyup',function(){
                  if(isNaN(this.value)){ alert('!!! กรุณากรอกเป็น ตัวเลข'); this.value='0';$(this).select();}
            });
        </script>



        <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js" type="text/javascript"></script>

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
                             // console.log(data); 
                             // return false;
                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                  });                    
   
                $.ajax({

                       type:'POST',
                       url: " {{ url('backend/ajaxMenuPermissionControl') }} ", 
                       data:{ _token: '{{csrf_token()}}',menu_id:menu_id },
                        success:function(data){
                             var d1 = JSON.stringify(data);
                             var d2 = JSON.parse(d1);  
                             sessionStorage.setItem("menu_id", d2.menu_id);
                             sessionStorage.setItem("sPermission", d2.sPermission);
                             sessionStorage.setItem("sC", d2.sC);
                             sessionStorage.setItem("sU", d2.sU);
                             sessionStorage.setItem("sD", d2.sD);
                             sessionStorage.setItem("can_cancel_bill", d2.can_cancel_bill);
                             sessionStorage.setItem("can_cancel_bill_across_day", d2.can_cancel_bill_across_day);
                             sessionStorage.setItem("can_approve", d2.can_approve);

                          },
                        error: function(jqXHR, textStatus, errorThrown) { 
                            console.log(JSON.stringify(jqXHR));
                            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                        }
                  });       
                localStorage.clear();
            });

           $(document).ready(function(){   

              var menu_id = sessionStorage.getItem("menu_id");
              var sPermission = "<?=@\Auth::user()->permission?>";
              var sC = sessionStorage.getItem("sC");

              if(sPermission==1){
                var can_approve = 1;
                var sC = 1;
                var sU = 1;
                var sD = 1;
              }else{
                var can_approve = sessionStorage.getItem("can_approve");
              }

              
              // console.log('menu_id : '+menu_id);
              // console.log('sPermission : '+sPermission);
              // console.log('can_approve : '+can_approve);
              // console.log('sPermission : '+sPermission);
              // console.log('sC : '+sC);
              // console.log('sU : '+sU);
              // console.log('sD : '+sD);
              
              // console.log('can_cancel_bill : '+can_cancel_bill);
              // console.log('can_cancel_bill_across_day : '+can_cancel_bill_across_day);
              // $(".get_menu_id").val(menu_id);

              if(sPermission!=1){

                (sC==1)?($(".class_btn_add").show()):($(".class_btn_add").remove());
                // เคสนี้ ต้องรับสินค้าครบทั้งหมดก่อนถึงจะมีสิทธิ์อนุมัติใบโอนได้ และค่อยดึงเข้าคลังต่อไปได้
                (can_approve==1)?($(".div_approve_transfer_branch_get").show()):($(".div_approve_transfer_branch_get").remove());

              }
        

           });


        </script>

        
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('backend/libs/sweetalert2/sweetalert2.min.js')}}"></script>

        <!-- Sweet alert init js -->
        <script src="{{ URL::asset('backend/js/pages/sweet-alerts.init.js')}}"></script> 
        <script src="{{ URL::asset('backend/js/pages/jquery.idle.js')}}"></script> 


   
          <script>    
              $(document).ready(function(){
                var fp = "{{@$page}}";
                // console.log(fp);
                  if(!fp){
                    $('body').toggleClass('vertical-collpsed');
                    $(".btnHome").show();
                    $(".btn_mega").show();
                  }else{
                    $(".btnHome").hide();
                    $(".btn_mega").hide();
                  }
              });
          </script>

        @yield('script')
        @yield('script-bottom')

@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/bower_components/jstree/css/style.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/assets/pages/treeview/treeview.css')}}">
@endsection

<div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="page-header card">
    <div class="card-block">
          <h5 class="m-b-10">ลำดับสายงาน</h5> 
    <div class="card-header" style="padding: 0px">
      <a class="btn hor-grd btn-primary btn-outline-primary waves-effect md-trigger btn-sm btn-round m-t-5" href="{{route('home')}}" style="color: black;font-size: 16px;"><i class="fa fa-user" ></i> <b class="font-primary">You</b></a>
      @if($data['lv1']->id == Auth::guard('c_user')->user()->id)
      <button class="btn btn-success btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px">
        <i class="fa fa-sort-up"></i> <b>Up one step</b></button>
        @else

        <a href="#"  onclick="event.preventDefault();
        document.getElementById('upline_id').submit();"
        class="btn hor-grd btn-success btn-sm m-t-5" style=" color: #FFF;font-size: 16px"><i class="fa fa-sort-up"></i> <b>Up one step</b></a>
        <form id="upline_id" action="{{ route('home') }}" method="POST" style="display: none;">
          <input type="hidden" name="id" value="{{$data['lv1']->upline_id}}">
          @csrf
        </form>
        @endif



        @if(empty($data['lv2_a']) || empty($data['lv3_a_a']))
        <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px" ><i class="fa fa-sort-down"></i> ดิ่งขา A</button>

        @else
        <a href="#" onclick="event.preventDefault();
        document.getElementById('under_a').submit();" class="btn btn-primary btn-sm m-t-5" style="color: #FFF;font-size:16px"><i class="fa fa-sort-down"></i> ดิ่งขา A</a>

        <form id="under_a" action="{{ route('under_a') }}" method="POST" style="display: none;">
          <input type="hidden" name="id" value="{{$data['lv3_a_a']->id}}">
          @csrf
        </form>

        @endif


        @if(empty($data['lv2_b']) || empty($data['lv3_b_b']))
        <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size: 16px" ><i class="fa fa-sort-down"></i> ดิ่งขา B</button>

        @else
        <a href="#" onclick="event.preventDefault();
        document.getElementById('under_b').submit();" class="btn btn-sm btn-primary m-t-5" style="color: #FFF;font-size: 16px"><i class="fa fa-sort-down"></i> ดิ่งขา B</a>

        <form id="under_b" action="{{ route('under_b') }}" method="POST" style="display: none;">
          <input type="hidden" name="id" value="{{$data['lv3_b_b']->id}}">
          @csrf
        </form>
        @endif 

        @if(empty($data['lv2_c']) || empty($data['lv3_c_c']))
        <button class="btn btn-primary btn-sm btn-disabled disabled m-t-5" style="color: #FFF;font-size:16px" disabled=""><i class="fa fa-sort-down"></i> ดิ่งขา C</button>

        @else 
        <a href="#" onclick="event.preventDefault();
        document.getElementById('under_c').submit();" class="btn btn-sm btn-primary m-t-5" style="color: #FFF;font-size:16px"><i class="fa fa-sort-down"></i> ดิ่งขา C</a>

        <form id="under_c" action="{{route('under_c')}}" method="POST" style="display: none;">
          <input type="hidden" name="id" value="{{$data['lv3_c_c']->id}}">
          @csrf
        </form>

        @endif

      </div>
      
    
    </div>
  </div>
  </div>
</div>
 
<div class="row">
  <div class="col-sm-12 col-md-3 col-lg-3">
    <!-- Basic Tree card start -->
    <div class="card"> 
      <div class="card-header">
        <h5>Tree View</h5>
      </div>
      <div class="card-block">
        <div class="card-block tree-view">
          <div id="basicTree">
            <ul>
              <li data-jstree='{"opened":true}'>Admin
                <ul>
                  <li data-jstree='{"opened":true}'><b>สาย A : 00016</b>
                    <ul>
                      <li data-jstree='{"type":"file"}'>สาย A : 0020

                      </li>
                      <li data-jstree='{"type":"file"}'>สาย B : 0021</li>
                      <li data-jstree='{"type":"file"}'>สาย C : 0022</li>
                    </ul>
                  </li>
                  <li data-jstree='{"opened":true}'><b>สาย B : 00017</b>
                    <ul>
                      <li data-jstree='{"type":"file"}'>สาย A : 0023</li>
                      <li data-jstree='{"type":"file"}'>สาย B : 0024</li>
                      <li data-jstree='{"type":"file"}'>สาย C : 0025</li>
                    </ul>
                  </li>

                  <li data-jstree='{"opened":true}'><b>สาย C : 00019</b>
                   <ul>
                    <li data-jstree='{"type":"file"}'>สาย A : 0026</li>
                    <li data-jstree='{"type":"file"}'>สาย B : 0027</li>
                    <li data-jstree='{"type":"file"}'>สาย C : 0028</li>
                  </ul>
                </li>

              </ul>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
  <!-- Basic Tree card end -->
</div>

<div class="col-sm-12 col-md-9 col-lg-9">
  <div class="page-header card">
    <div class="card-block">
      <h5 class="m-b-10">โครงสร้างสายงาน</h5> 

      <div class="row" align="center">
        <div class="col-lg-4"> 
        </div>
        @if($data['lv1'])

        <div class="col-lg-4"> 
          <div class="card widget-statstic-card borderless-card">
            <div class="card-block">
              @if($data['lv1']->id == Auth::guard('c_user')->user()->id)
              <i class="fa fa-group st-icon bg-success"></i>
              @else
              <i class="st-icon bg-success" style="padding: 34px 43px 12px 28px"><b>{{$data['lv1']->line_type}}</b></i>
              @endif
              <div class="usre-image">
                @if($data['lv1']->profile_img)
                <a data-toggle="modal" data-target="#lv1"><img class="img-radius zoom" width="100" src="{{asset('local/public/profile_customer/'.$data['lv1']->profile_img)}}" alt="User-Profile-Image">
                </a>
                @else
                <a data-toggle="modal" data-target="#lv1"><img class="img-radius zoom" width="100" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
                </a>
                @endif

              </div>
              <h6 class="f-w-600 m-t-15 m-b-10">รหัสสมาชิก : {{$data['lv1']->user_name}} </h6>
              <p class="text-muted">{{$data['lv1']->prefix_name.' '.$data['lv1']->first_name.' '.$data['lv1']->last_name }}</p>
            </div>
          </div>
        </div>

        <div class="modal fade" id="lv1" tabindex="-1" role="dialog">
         <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
           <div class="modal-header bg-c-green">
            <h4 class="modal-title" style="color: #FFFF">รหัสสมาชิก : {{$data['lv1']->user_name}}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>

         <div class="modal-body text-left">
          <div class="table-responsive">
           <table class="table">
            <tbody>
             <tr class="table-success">
              <td><strong>วันที่สมัคร </strong></td>
              <td>{{ date('d-m-Y',strtotime($data['lv1']->created_at)) }}</td>
              <td></td>
            </tr>
            <tr>
              <td><strong>สั่งซื้อครั้งแรก </strong></td>
              <td> [ยังไม่มีข้อมูล] </td>
              <td></td>
            </tr>
            <tr class="table-success">
              <td><strong>คะแนนส่วนตัว:</strong></td> 
              <td>{{ $data['lv1']->pv_point }} PV</td>
              <td>[Exclusive ????]</td>

            </tr>
            <tr>
              <td><strong>Active ถึง</strong></td>
              <td>{{ date('d-m-Y',strtotime($data['lv1']->created_at)) }}</td>
              <td>[เหลือ 0 pv ???]</td>
            </tr>
            <tr class="table-success">
              <td><strong>คุณวุฒิสูงสุด</strong></td>
              <td>BRONZE STAR AWARD (BSA)</td>
              <td></td>
            </tr>
            <tr>
              <td><strong>สิทธิ Reward Bonus</strong></td>
              <td></td>
              <td></td>
            </tr>
            <tr class="table-success">
              <td><strong>นับคุณวุฒิจาก</strong></td>
              <td>2020-05-01 ถึง 2020-05-31</td>
              <td></td>
            </tr>
            <tr>
              <td><strong>ทีมกลางคือทีม</strong></td>
              <td><b>C</b> มีคะแนนสะสม 260,204 PV</td>
              <td></td> 
            </tr>

          </tbody>
        </table>
      </div>
      <div class="b-t-default transection-footer row">
       <div class="col-6  b-r-default">
        <strong>คะแนนคงเหลือยกมา</strong><br>
        [ A ]<font class="font-red"> 208,898,210</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 0</font>
      </div>
      <div class="col-6">
        <strong>คะแนนวันนี้</strong><br>
        [ A ]<font class="font-red"> 9,230</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 7,400</font>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">ปิด</button>
              {{-- <button type="button"  onclick="event.preventDefault();
              document.getElementById('line_id_v1').submit();" class="btn btn-primary waves-effect waves-light ">ดูสายงาน</button>
              <form id="line_id_v1" action="{{route('home')}}" method="POST" style="display: none;">
                <input type="hidden" name="id" value="{{$data['lv1']->id}}">
                @csrf
              </form> --}}
            </div>
          </div>
        </div>
      </div>


      @else
      <!-- กรณีไม่มีข้อมูล -->
      <div class="col-lg-4"> 
        <div class="card widget-statstic-card borderless-card">
          <div class="card-block">
            <i class="fa fa-line-chart st-icon btn-warning"></i>
            <div class="usre-image">
              <img src="frontend/assets/icon/add_user.png" class="img-radius zoom" alt="User-Profile-Image">
            </div>
                            <!-- <h6 class="f-w-600 m-t-15 m-b-10">Alessa Robert</h6>
                              <p class="text-muted">Active | Male | Born 23.05.1992</p> -->
                            </div>
                          </div>

                        </div>
                        @endif
                        <div class="col-lg-4"> 
                        </div>
                      </div>
                      <br>
                      <div class="row text-center">
                       @for($i=1;$i<=3;$i++)
                       <?php
                       if($i==1){
                        $data_lv2 =$data['lv2_a'];
                        $model_lv2 = 'lv2_a';
                        $type = 'a';
                        $line_lv2 = 'A';
                      }elseif($i==2){
                        $data_lv2 =$data['lv2_b'];
                        $model_lv2 = 'lv2_b';
                        $type = 'b';
                        $line_lv2 = 'B';
                      }elseif($i==3){
                        $data_lv2 =$data['lv2_c'];
                        $model_lv2 = 'lv2_c';
                        $type = 'c';
                        $line_lv2 = 'C';
                      }else{
                        $data_lv2 = null;
                        $model_lv2 = null;
                        $line_lv2 = null;
                      }

                      ?>


                      @if($data_lv2)
                      <div class="col-lg-4">
                        <div class="card widget-statstic-card borderless-card">
                         <div class="card-block">
                          <i class="st-icon bg-primary" style="padding: 34px 43px 12px 28px"><b>{{$line_lv2}}</b></i>
                          <div class="usre-image">

                            @if($data_lv2->profile_img)
                            <a data-toggle="modal" data-target="#{{$model_lv2}}"><img class="img-radius zoom" width="80" src="{{asset('local/public/profile_customer/'.$data_lv2->profile_img)}}" alt="User-Profile-Image">
                            </a>
                            @else
                            <a data-toggle="modal" data-target="#{{$model_lv2}}"><img class="img-radius zoom" width="80" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
                            </a>
                            @endif
                          </div>

                          <div class="modal fade" id="{{$model_lv2}}" tabindex="-1" role="dialog">
                           <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content">
                             <div class="modal-header bg-c-green">
                              <h4 class="modal-title" style="color: #FFFF">สาย : {{$line_lv2}} รหัสสมาชิก : {{$data_lv2->user_name}}</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                               <span aria-hidden="true">&times;</span>
                             </button>
                           </div>

                           <div class="modal-body text-left">
                            <div class="table-responsive">
                             <table class="table">
                              <tbody>
                               <tr class="table-success">
                                <td><strong>Upline </strong></td>
                                <td>{{$data['lv1']->user_name}} Sponsor : ???</td>
                                <td>Sponsor : ???</td>
                              </tr>

                              <tr>
                                <td><strong>วันที่สมัคร </strong></td>
                                <td>{{ date('d-m-Y',strtotime($data_lv2->created_at)) }}</td>
                                <td></td>
                              </tr>
                              <tr class="table-success">
                                <td><strong>สั่งซื้อครั้งแรก </strong></td>
                                <td> [ยังไม่มีข้อมูล] </td>
                                <td></td>
                              </tr>
                              <tr>
                                <td><strong>คะแนนส่วนตัว:</strong></td> 
                                <td>{{ $data['lv1']->pv_point }} PV</td>
                                <td>[Exclusive ????]</td>

                              </tr>
                              <tr class="table-success">
                                <td><strong>Active ถึง</strong></td>
                                <td>{{ date('d-m-Y',strtotime($data_lv2->created_at)) }}</td>
                                <td>[เหลือ 0 pv ???]</td>
                              </tr>
                              <tr>
                                <td><strong>คุณวุฒิสูงสุด</strong></td>
                                <td>BRONZE STAR AWARD (BSA)</td>
                                <td></td>
                              </tr>
                              <tr class="table-success">
                                <td><strong>สิทธิ Reward Bonus</strong></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td><strong>นับคุณวุฒิจาก</strong></td>
                                <td>2020-05-01 ถึง 2020-05-31</td>
                                <td></td>
                              </tr>
                              <tr class="table-success">
                                <td><strong>ทีมกลางคือทีม</strong></td>
                                <td><b>C</b> มีคะแนนสะสม 260,204 PV</td>
                                <td></td> 
                              </tr>

                            </tbody>
                          </table>
                        </div>
                        <div class="b-t-default transection-footer row">
                         <div class="col-6  b-r-default">
                          <strong>คะแนนคงเหลือยกมา</strong><br>
                          [ A ]<font class="font-red"> 208,898,210</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 0</font>
                        </div>
                        <div class="col-6">
                          <strong>คะแนนวันนี้</strong><br>
                          [ A ]<font class="font-red"> 9,230</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 7,400</font>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                      <button type="button"  onclick="event.preventDefault();
                      document.getElementById('{{$data_lv2->id}}').submit();" class="btn btn-primary waves-effect waves-light ">ดูสายงาน</button>
                      <form id="{{$data_lv2->id}}" action="{{route('home')}}" method="POST" style="display: none;">
                       <input type="hidden" name="id" value="{{$data_lv2->id}}">
                       @csrf
                     </form>
                   </div>
                 </div>
               </div>
             </div>

             <h6 class="f-w-600 m-t-15 m-b-10">สาย {{$line_lv2}} : {{$data_lv2->user_name}} </h6>
             <p class="text-muted m-t-15">{{$data_lv2->prefix_name.' '.$data_lv2->first_name.' '.$data_lv2->last_name }}</p>


             <hr>
             <div class="row ml-auto">
               @for($j=1;$j<=3;$j++)
               <?php
               if($j==1){
                $data_lv3 =$data['lv3_'.$type.'_a'];
                $model_lv3 = 'lv3_'.$type.'_a';
                $line_lv3 = 'A';
              }elseif($j==2){
                $data_lv3 =$data['lv3_'.$type.'_b'];
                $model_lv3 = 'lv3_'.$type.'_b';
                $line_lv3 = 'B';
              }elseif($j==3){
                $data_lv3 =$data['lv3_'.$type.'_c'];
                $model_lv3 = 'lv3_'.$type.'_c';
                $line_lv3 = 'C';
              }else{
                $data_lv3 = null;
                $model_lv3 = null;
                $line_lv3 = null; 
              }

              ?>
              @if($data_lv3)


              <div class="col-auto col-sm-4 text-center">
                @if($data_lv2->profile_img)
                <a data-toggle="modal" data-target="#{{$model_lv3}}">
                  <img class="img-radius zoom" width="60" src="{{asset('local/public/profile_customer/'.$data_lv3->profile_img)}}" alt="User-Profile-Image">
                </a>
                @else
                <a data-toggle="modal" data-target="#{{$model_lv3}}">
                  <img class="img-radius zoom" width="60" src="{{asset('local/public/images/ex.png')}}" alt="User-Profile-Image">
                </a>
                @endif


                <h6 class="m-t-15 m-b-0">สาย {{$line_lv3}}<br>{{$data_lv3->user_name}}</h6>
                <!-- <p class="text-muted m-b-0"><small>{{ $data['lv1']->pv_point }} PV</small></p> -->
              </div>



              <div class="modal fade" id="{{$model_lv3}}" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-md" role="document">
                 <div class="modal-content">
                  <div class="modal-header bg-c-green">
                   <h4 class="modal-title" style="color: #FFFF">สาย : {{$line_lv3}} รหัสสมาชิก : {{$data_lv3->user_name}}</h4>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body text-left">
                 <div class="table-responsive">
                  <table class="table">
                   <tbody>
                    <tr class="table-success">
                     <td><strong>Upline </strong></td>
                     <td>{{$data_lv2->user_name}}</td>
                     <td>Sponsor : ???</td>
                   </tr>

                   <tr>
                     <td><strong>วันที่สมัคร </strong></td>
                     <td>{{ date('d-m-Y',strtotime($data_lv3->created_at)) }}</td>
                     <td></td>
                   </tr>
                   <tr class="table-success">
                     <td><strong>สั่งซื้อครั้งแรก </strong></td>
                     <td> [ยังไม่มีข้อมูล] </td>
                     <td></td>
                   </tr>
                   <tr>
                     <td><strong>คะแนนส่วนตัว:</strong></td> 
                     <td>{{ $data_lv3->pv_point }} PV</td>
                     <td>[Exclusive ????]</td>

                   </tr>
                   <tr class="table-success">
                     <td><strong>Active ถึง</strong></td>
                     <td>{{ date('d-m-Y',strtotime($data_lv3->created_at)) }}</td>
                     <td>[เหลือ 0 pv ???]</td>
                   </tr>
                   <tr>
                     <td><strong>คุณวุฒิสูงสุด</strong></td>
                     <td>BRONZE STAR AWARD (BSA)</td>
                     <td></td>
                   </tr>
                   <tr class="table-success">
                     <td><strong>สิทธิ Reward Bonus</strong></td>
                     <td></td>
                     <td></td>
                   </tr>
                   <tr>
                     <td><strong>นับคุณวุฒิจาก</strong></td>
                     <td>2020-05-01 ถึง 2020-05-31</td>
                     <td></td>
                   </tr>
                   <tr class="table-success">
                     <td><strong>ทีมกลางคือทีม</strong></td>
                     <td><b>C</b> มีคะแนนสะสม 260,204 PV</td>
                     <td></td> 
                   </tr>

                 </tbody>
               </table>
             </div>
             <div class="b-t-default transection-footer row">
              <div class="col-6  b-r-default">
               <strong>คะแนนคงเหลือยกมา</strong><br>
               [ A ]<font class="font-red"> 208,898,210</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 0</font>
             </div>
             <div class="col-6">
               <strong>คะแนนวันนี้</strong><br>
               [ A ]<font class="font-red"> 9,230</font> -[ B ]<font class="font-red"> 0</font> -[ C ]<font class="font-red"> 7,400</font>
             </div>
           </div>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
           <button type="button"  onclick="event.preventDefault();
           document.getElementById('{{$data_lv3->id}}').submit();" class="btn btn-primary waves-effect waves-light ">ดูสายงาน</button>
           <form id="{{$data_lv3->id}}" action="{{route('home')}}" method="POST" style="display: none;">
            <input type="hidden" name="id" value="{{$data_lv3->id}}">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>
  @else
  @if($data_lv2)
  <div class="col-auto col-sm-4 text-center">
    <a href="{{route('register',['id'=>$data_lv2->id,'line_type'=>$line_lv3])}}"><img src="frontend/assets/icon/add_user.png" alt="img" class="img-radius img-60 zoom"></a>
    <h6 class="m-t-15 m-b-0 text-success">เพิ่ม {{$line_lv3}} </h6>
    <!-- <p class="text-muted m-b-0"><small>PNG-100KB</small></p> -->
  </div>
  @else
  <div class="col-auto text-center">
    <img src="frontend/assets/images/avatar-4.jpg" alt="img" class="img-radius img-60 zoom">
    <h6 class="m-t-15 m-b-0">สาย {{$line_lv3}}</h6>
    <!-- <p class="text-muted m-b-0"><small>PNG-100KB</small></p> -->
  </div>
  @endif
  @endif 
  @endfor
</div>
</div>
</div>
<br>
</div> 
@else
<div class="col-lg-4">
  <div class="card widget-statstic-card borderless-card">
   <div class="card-block">
    <i class="st-icon bg-success" style="padding: 34px 43px 12px 28px;background-color:#666 !important"><b>{{$line_lv2}}</b></i>
    <div class="text-center">
     <a href="{{route('register',['id'=>$data['lv1']->id,'line_type'=>$line_lv2])}}">
      <img src="frontend/assets/icon/add_user.png" alt="img" class="img-radius img-100 zoom">
      <a>

      </div>
      <h6 class="f-w-600 m-t-15 m-b-10 text-success">เพิ่ม {{$line_lv2}}</h6>
      <h6 class="f-w-600 m-t-15 m-b-10">Under line : {{$data['lv1']->user_name}} </h6>

      <hr> 
      <div class="row ml-auto">
       <div class="col-auto col-sm-4 text-center">
        <img src="frontend/assets/icon/add_user_not.png" alt="img" class="img-radius img-70">
        <h6 class="m-t-15 m-b-0">สาย A </h6>

      </div>
      <div class="col-auto col-sm-4 text-center">
        <img src="frontend/assets/icon/add_user_not.png" alt="img" class="img-radius img-70">
        <h6 class="m-t-15 m-b-0">สาย B </h6>

      </div>
      <div class="col-auto col-sm-4 text-center">
        <img src="frontend/assets/icon/add_user_not.png" alt="img" class="img-radius img-70">
        <h6 class="m-t-15 m-b-0">สาย C </h6>

      </div>
    </div>


  </div>
</div>
<br>
</div>
@endif
@endfor
</div>
</div>
</div>
</div>

</div>
@endsection
@section('js')
<script  src="{{asset('frontend/bower_components/jstree/js/jstree.min.js')}}"></script>
<script  src="{{asset('frontend/assets/pages/treeview/jquery.tree.js')}}"></script>
@endsection

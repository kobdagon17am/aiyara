@extends('frontend.layouts.customer.customer_app')
@section('conten')
@section('css')

@endsection
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="sub-title"><i class="fa fa-file-text"></i> เอกสารการลงทะเบียน</h4>
            </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="100">#</th>
                                <th>เอกสาร</th>
                                <th width="200">วันที่</th>
                                <th width="200">สถานะ</th>
                                <th>รายละเอียด</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; ?>
                            @foreach($data as $value)
                            <?php $i++;
                            if($value->type == 1){
                                $type = "บัตรประชาชน";
                            }elseif($value->type == 2){
                                $type = "หน้าบัญชีธนาคาร";
                            }elseif($value->type == 3) {
                                $type = "ลายเซ็น";
                            }else{
                                $type = "อื่นๆ";
                            }

                            if ($value->status=='W'){
                                $status = "<span class='pcoded-badge label label-warning'>รอการอนุมัติ</span>";
                            }elseif ($value->status=='S') {
                                $status = "<span class='pcoded-badge label label-success'>ผ่านการอนุมัติ</span>";
                            }elseif ($value->status=='F') {
                                $status = "<span class='pcoded-badge label label-danger'>ไม่ผ่านการอนุมัติ</span>";
                                                                # code...
                            }else{
                                $status = "อื่นๆ";
                            }

                            ?>
                            <tr>
                                <th scope="row">{{ $i }}</th>
                                <td>{{ $type }}</td>
                                <td>{{ date('d/m/Y',strtotime($value->created_at)) }}</td>
                                <td>{!! $status !!}</td>
                                <td>{!! $value->comment !!}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 

                <hr>
                <p class="text-danger">*กรณีเอกสารไม่ผ่านการอนุมัติ สามาถส่งเอกสารเพิ่มเติมได้โดยการแนบไฟล์เอกสารตามฟอร์มด้านล่าง ทางทีมงานจะรีบดำเนินการตรวจสอบให้ภานใน 1-2 วันทำการคะ </p>


                <form action="{{route('docs_upload')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-12">
                         <h4 class="sub-title"><i class="fa fa-upload"></i> ส่งเอกสารเพิ่มเติม</h4>

                         <div class="form-group row">
                            <div class="col-sm-6">
                                <label>บัตรประชาชน</label>
                                <input type="file" id="file_1" name="file_1" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label>หน้าบัญชีธนาคาร</label>
                                <input type="file" id="file_2" name="file_2" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6"> 
                                <label>ลายเซ็น</label>
                                <input type="file" id="file_3" name="file_3" class="form-control">
                            </div>
                        </div>

                    </div>

                    <div class="form-group row text-right">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary m-b-0">Upload</button>
                        </div>
                    </div>

                </form>


            </div>

        </div>
        <div class="card-footer text-right">
           {{--  <button type="submit" class="btn btn-primary" > Update </button> --}}
       </div>
   </div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $('#file_1').change( function () {
      var fileExtension = ['jpg','png','pdf'];
      if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
         alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
         this.value = '';
         return false;
     }
 });
    $('#file_2').change( function () {
      var fileExtension = ['jpg','png','pdf'];
      if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
         alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
         this.value = '';
         return false;
     }
 });

    $('#file_3').change( function () {
      var fileExtension = ['jpg','png','pdf'];
      if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
         alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
         this.value = '';
         return false;
     }
 });
</script>
@endsection



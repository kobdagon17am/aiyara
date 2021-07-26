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


        <h4 class="sub-title"><i class="fa fa-upload"></i> ส่งเอกสารเพิ่มเติม</h4>


        <form action="{{route('docs_upload')}}" method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-group row">

            <div class="col-sm-6">
             @if ($registeredDocs->regis_doc1_status !== 1)
              <div class="form-group row">
                <div class="col-sm-12">
                  <label>ภาพถ่ายบัตรประชาชน</label>
                  <input type="file" id="file_1" name="file_1" class="form-control">
                </div>
              </div>
             @endif

            @if ($registeredDocs->regis_doc4_status !== 1)
              <div class="form-group row">
                <div class="col-sm-12">
                  <label>ภาพถ่ายหน้าบัญชีธนาคาร</label>
                  <input type="file" id="file_4" name="file_4" class="form-control">
                </div>
              </div>
            @endif

            @if ($registeredDocs->regis_doc2_status !== 1)
              <div class="form-group row">
                <div class="col-sm-12">
                  <label>ภายถ่ายหน้าตรง</label>
                  <input type="file" id="file_2" name="file_2" class="form-control">
                </div>
              </div>
            @endif
          </div>

          <div class="col-sm-6">
           @if ($registeredDocs->regis_doc3_status !== 1)
            <div class="form-group row">
              <div class="col-sm-12">
                <label>ภาพถ่ายหน้าตรงถือบัตรประชาชน</label>
                <input type="file" id="file_3" name="file_3" class="form-control">
              </div>
            </div>
            <div class="m-t-2 col-sm-12 text-center">
              <img src="{{ asset('frontend/assets/images/user_card.jpg') }}" id="preview" class="img-thumbnail">
            </div>
           @endif
        </div>

        <div class="form-group row ml-auto">
          <label class="col-sm-2"></label>
          <div class="col-sm-10">
            @if($canAccess)
            <button type="submit" class="btn btn-primary m-b-0">Upload</button>
            @endif
          </div>
        </div>
      </div>

    </form>

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
                $type = "เอกสารการสมัคร";
              }elseif($value->type == 4) {
                $type = "ภาพใบหน้าพร้อมถือบัตรประชาชน";

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
        <p class="text-danger">*กรณีเอกสารไม่ผ่านการอนุมัติ สามาถส่งเอกสารเพิ่มเติมได้โดยการแนบไฟล์เอกสารตามฟอร์มด้านล่าง ทางทีมงานจะรีบดำเนินการตรวจสอบให้ภานใน 1-2 วันทำการค่ะ </p>


  </div>

</div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  $('#file_1').change( function () {
    var fileExtension = ['jpg','png','pdf', 'jpeg'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });
  $('#file_2').change( function () {
    var fileExtension = ['jpg','png','pdf', 'jpeg'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });

  $('#file_3').change( function () {
    var fileExtension = ['jpg','png','pdf', 'jpeg'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }
 });

  $('#file_4').change( function () {
    var fileExtension = ['jpg','png', 'jpeg'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
     alert("This is not an allowed file type. Only JPG, PNG and PDF files are allowed.");
     this.value = '';
     return false;
   }else {

     var reader = new FileReader();
     reader.onload = function(e) {
    // get loaded data and render thumbnail.
    document.getElementById("preview").src = e.target.result;
  };
  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);

}


});



</script>
@endsection



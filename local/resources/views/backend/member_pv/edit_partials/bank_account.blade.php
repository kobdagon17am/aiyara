<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">ข้อมูลธนาคาร</h5>
        <hr class="mt-1 mb-2">

        <form action="#" method="POST">
          @csrf
          @method('PATCH')

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="bank_account">ชื่อบัญชี</label>
                <input type="text" class="form-control" name="bank_account" value="{{ $customer->bank_account }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="bank_no">เลขที่บัญชี</label>
                <input type="text" class="form-control" name="bank_no" value="{{ $customer->bank_no }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="bank_name">ธนาคาร</label>
                <input type="text" class="form-control" value="{{ $customer->bank_name }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="bank_branch">สาขา</label>
                <input type="text" class="form-control" name="bank_branch" value="{{ $customer->bank_branch }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="bank_type">ประเภทบัญชี</label>
              <div class="form-group">
                <div class="custom-control custom-radio mb-2">
                    <input type="radio" id="bank_type1" name="bank_type" class="custom-control-input" value="ออมทรัพย์" @if($customer->bank_type == 'ออมทรัพย์')  checked @endif>
                    <label class="custom-control-label" for="bank_type1">ออมทรัพย์</label>
                </div>
                <div class="custom-control custom-radio">
                    <input type="radio" id="bank_type2" name="bank_type" class="custom-control-input" value="กระแสรายวัน" @if($customer->bank_type == 'กระแสรายวัน')  checked @endif>
                    <label class="custom-control-label" for="bank_type2">กระแสรายวัน</label>
                </div>
              </div>
            </div>
          </div>
  
          <div class="text-right">
            <button type="submit" class="btn btn-info">แก้ไขข้อมูล</button>
          </div>          
        </form>

      </div>
    </div>
  </div>
</div>
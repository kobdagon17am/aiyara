<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">ข้อมูลธนาคาร</h5>
        <hr class="mt-1 mb-2">

        <form action="{{ route('backend.member_pv.update', $customer->id) }}" method="POST">
          @csrf
          @method('PATCH')

          <input type="hidden" name="type" value="bank">

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
            {{-- <div class="col-md-3">
              <div class="form-group">
                <label for="bank_name">ธนาคาร</label>
                <input type="text" class="form-control" name="bank_name" value="{{ $customer->bank_name }}">
              </div>
            </div> --}}


            <div class="col-md-3">
              <div class="form-group">
              <label>ธนาคาร <font class="text-danger">*</font></label>
              <select class="form-control" name="bank_name" required>
                  <option value=""> เลือกธนาคาร </option>
                  <option value="ธนาคารกรุงเทพ" @if ('ธนาคารกรุงเทพ' ==$customer->bank_name) selected="" @endif> ธนาคารกรุงเทพ </option>
                  <option value="ธนาคารกรุงไทย" @if ('ธนาคารกรุงไทย' ==$customer->bank_name) selected="" @endif> ธนาคารกรุงไทย </option>
                  <option value="ธนาคารกรุงศรีอยุธยา" @if ('ธนาคารกรุงศรีอยุธยา' ==$customer->bank_name) selected="" @endif> ธนาคารกรุงศรีอยุธยา </option>
                  <option value="ธนาคารกสิกรไทย" @if ('ธนาคารกสิกรไทย' ==$customer->bank_name) selected="" @endif> ธนาคารกสิกรไทย </option>
                  <option value="ธนาคารทหารไทยธนชาต" @if ('ธนาคารทหารไทยธนชาต' ==$customer->bank_name) selected="" @endif> ธนาคารทหารไทยธนชาต </option>
                  <option value="ธนาคารไทยพาณิชย์" @if ('ธนาคารไทยพาณิชย์' ==$customer->bank_name) selected="" @endif> ธนาคารไทยพาณิชย์ </option>

                  <option value="ธนาคารยูโอบี" @if ('ธนาคารยูโอบี' ==$customer->bank_name) selected="" @endif> ธนาคารยูโอบี </option>
                  <option value="ธนาคารอิสลาม" @if ('ธนาคารอิสลาม' ==$customer->bank_name) selected="" @endif> ธนาคารอิสลาม </option>
                  <option value="ACLEDA fo cambodian" @if ('ACLEDA fo cambodian' ==$customer->bank_name) selected="" @endif> ACLEDA fo cambodian </option>
                  <option value="ABA fo cambodian" @if ('ABA fo cambodian' ==$customer->bank_name) selected="" @endif> ABA fo cambodian </option>
              </select>
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

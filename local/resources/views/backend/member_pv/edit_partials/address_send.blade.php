<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">ที่อยู่จัดส่ง</h5>
        <hr class="mt-1 mb-2">

        <form action="#" method="POST">
          @csrf

          <div class="text-left">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="house_no">บ้านเลขที่</label>
                  <input type="text" class="form-control" name="house_no" value="{{ $customer->house_no ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="village">หมู่บ้าน/อาคาร</label>
                  <input type="text" class="form-control" name="house_name" value="{{ $customer->house_name ?? '' }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="moo">หมู่ที่</label>
                  <input type="text" class="form-control" name="moo" value="{{ $customer->moo ?? '' }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="soi">ตรอก/ซอย</label>
                  <input type="text" class="form-control" name="soi" value="{{ $customer->soi ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="road">ถนน</label>
                  <input type="text" class="form-control" name="road" value="{{ $customer->road ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="province">จังหวัด</label>
                  <select name="province" id="province" class="form-control select2-templating select2-province" data-prefix="send" data-send-province>
                    <option value="">- เลือกจังหวัด -</option>
                    @foreach ($provinces as $provinceId => $provinceName)
                        <option value="{{ $provinceId }}" @if($provinceId == $customer->province_id_fk) selected @endif>{{ $provinceName }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="district">เขต/อำเภอ</label>
                  <select name="district" id="district" class="form-control select2-templating select2-district" data-prefix="send" data-send-district></select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="sub_district">แขวง/ตำบล</label>
                  <select name="sub_district" id="sub_district" class="form-control select2-templating select2-sub-district" data-prefix="send" data-send-sub-district></select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="zipcode">รหัสไปรษณีย์</label>
                  <input type="text" class="form-control" name="zipcode" data-send-zip-code>
                </div>
              </div>
            </div>

            <div class="text-right">
              <button type="submit" class="btn btn-info">แก้ไขข้อมูล</button>
            </div>
          </div> 
        </form>
        
      </div>
    </div>
  </div>
</div>
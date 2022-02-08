<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">ที่อยู่ตามบัตรประชาชน</h5>
        <hr class="mt-1 mb-2">

        <form action="#" method="POST">
          @csrf

          <div class="text-left">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="house_no">บ้านเลขที่</label>
                  <input type="text" class="form-control" name="card_house_no" value="{{ @$addressCard->card_house_no ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="village">หมู่บ้าน/อาคาร</label>
                  <input type="text" class="form-control" name="card_house_name" value="{{ @$addressCard->card_house_name ?? '' }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="moo">หมู่ที่</label>
                  <input type="text" class="form-control" name="card_moo" value="{{ @$addressCard->card_moo ?? '' }}">
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="soi">ตรอก/ซอย</label>
                  <input type="text" class="form-control" name="card_soi" value="{{ @$addressCard->card_soi ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="road">ถนน</label>
                  <input type="text" class="form-control" name="card_road" value="{{ @$addressCard->card_road ?? '' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>จังหวัด</label>
                  <select name="card_province" class="form-control select2-templating select2-province" data-prefix="card" data-card-province>
                    <option value="">- เลือกจังหวัด -</option>
                    @foreach ($provinces as $provinceId => $provinceName)
                        <option value="{{ $provinceId }}" @if($provinceId === @@$addressCard->card_province_id_fk) selected @endif>{{ $provinceName }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>เขต/อำเภอ</label>
                  <select name="card_district" class="form-control select2-templating select2-district" data-prefix="card" data-card-district></select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>แขวง/ตำบล</label>
                  <select name="card_sub_district" class="form-control select2-templating select2-sub-district" data-prefix="card" data-card-sub-district></select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="zipcode">รหัสไปรษณีย์</label>
                  <input type="text" class="form-control" name="card_zipcode" value="{{ @$addressCard->card_zipcode ?? '' }}" data-card-zip-code>
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

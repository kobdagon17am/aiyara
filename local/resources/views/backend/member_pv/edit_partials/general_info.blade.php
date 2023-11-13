<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">ข้อมูลทั่วไป</h5>
        <hr class="mt-1 mb-2">
        <form action="{{ route('backend.member_pv.update', $customer->id) }}" method="POST">
          @csrf
          @method('PATCH')
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="first_name">คำนำหน้าชื่อ</label>
                <input type="text" class="form-control" name="prefix_name" id="prefix_name" placeholder="คำนำหน้าชื่อ"
                  value="{{ $customer->prefix_name }}">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="first_name">ชื่อ</label>
                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="ชื่อ"
                  value="{{ $customer->first_name }}">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="last_name">นามสกุล</label>
                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="นามสกุล"
                  value="{{ $customer->last_name }}">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="business_name">ชื่อที่ใช้ในธุรกิจ</label>
                <input type="text" class="form-control" name="business_name" id="business_name" placeholder="ชื่อที่ใช้ในธุรกิจ"
                  value="{{ $customer->business_name }}">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="id_card">รหัสบัตรประชาชน</label>
                <input type="text" class="form-control" name="id_card" id="id_card" placeholder="รหัสบัตรประชาชน"
                  value="{{ $customer->id_card }}">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="id_card">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="email"
                  value="{{ $customer->email }}">
              </div>
            </div>


            <div class="col-md-3">
              <div class="form-group">
                <label for="upline_id">Upline</label>
                <input type="text" class="form-control" name="upline_id" id="upline_id" placeholder="Upline" value="{{ $customer->upline_id }}" disabled>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="qualification_id">คุณสมบัติ</label>
                <select name="qualification_id" id="qualification_id" class="form-control select2-templating">
                  <option value="">- เลือกคุณสมบัติ -</option>
                  @foreach ($qualifications as $key => $name)
                    <option value="{{ $key }}" @if($key == $customer->qualification_id) selected @endif>{{ $name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="package_id">ตำแหน่งเกียรติยศ</label>
                <select name="package_id" id="package_id" class="form-control">
                  <option value="">- เลือกตำแหน่งเกียรติยศ -</option>
                  @foreach ($packages as $key => $name)
                    <option value="{{ $key }}" @if($key == $customer->package_id) selected @endif>{{ $name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="introduce_id">ผู้แนะนำ</label>
                <input type="text" class="form-control" name="introduce_id" id="introduce_id" placeholder="ผู้แนะนำ"
                  value="{{ $customer->introduce_name }}" disabled>
              </div>
            </div>
          </div>

          <div class="text-right">
            <button type="submit" name="type" value="customer" class="btn btn-info">แก้ไขข้อมูล</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>


<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold">Business Location</h5>
        <hr class="mt-1 mb-2">
        <form action="{{ route('backend.member_regis.tranfer_file') }}" method="POST" >


          @csrf
          <input type="hidden" name="user_name" value="{{$customer->user_name}}">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label for="first_name">Business Location</label>
                <input type="text" class="form-control" placeholder="Business Location"
                  value="{{ $business_location_name }}" disabled>

              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="package_id">ปรับ Business Location</label>
                <select name="business_location"  class="form-control" required>
                  <option value="">- เลือก Business Location -</option>
                  @foreach ($dataset_business_location as $value)
                    <option value="{{ $value->id }}" @if($value->id == $customer->business_location_id) selected @endif>{{ $value->txt_desc }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>

          <div class="text-right">
            <button type="submit" name="type" onclick="return confirm('Confirm Business Locatio ?')" class="btn btn-info">แก้ไขข้อมูล</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_tree_show" tabindex="-1" role="dialog">
 <div class="modal-dialog modal-md" role="document">
  <div class="modal-content">
   <div class="modal-header bg-c-green">
    <h4 class="modal-title" style="color: #FFFF">รหัสสมาชิก : {{$data->user_name}}</h4>
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
      <td>{{ date('d-m-Y',strtotime($data->created_at)) }}</td>
      <td></td>
    </tr>
    <tr>
      <td><strong>สั่งซื้อครั้งแรก </strong></td>
      <td> [ยังไม่มีข้อมูล] </td>
      <td></td>
    </tr>
    <tr class="table-success">
      <td><strong>คะแนนส่วนตัว:</strong></td> 
      <td>{{ $data->pv }} PV</td>
      <td>[Exclusive ????]</td>

    </tr>
    <tr>
      <td><strong>Active ถึง</strong></td>
      <td>{{ date('d-m-Y',strtotime($data->created_at)) }}</td>
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
                <input type="hidden" name="id" value="{{$data->id}}">
                @csrf
              </form> --}}
            </div>
          </div>
        </div>
      </div>
  
@extends('frontend.layouts.customer.customer_app')
@section('css')
<style>
  .wrap{
    margin-left:20px;
  }
  .box{
    width:40%;
    height:200px;
    float:left;
    background-color:white; 
    margin:25px 15px;
    border-radius:5px;
  }
  .box h3{
    font-family: 'Didact Gothic', sans-serif;
    font-weight:normal;
    text-align:center;
    padding-top:60px;

  }
  .box1{
    background-color: #00FF7F;
  }
  .box2{
    background-color: #00FF7F;
  }
  .box3{
    background-color: #00FA9A;
  }
  .box4{
    background-color: #00FA9A;
  }
  .box5{
    background-color: #00FF7F;
  }
  .box6{
    background-color: #9EB3EB;
  }
  .box7{
    background-color: #DB9EEB;
  }
  .box8{
    background-color: #C49EEB;
  }
  .shadow1, .shadow2, .shadow3,.shadow4,.shadow5,.shadow6,.shadow7,.shadow8{
    position:relative;
  }
  .shadow1,.shadow2,.shadow3,.shadow4,.shadow5,.shadow6,.shadow7,.shadow8{
    box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 20px rgba(0, 0, 0, 0.1) inset;
  }
/*****************************************************************dashed border
****************************************************************/
.shadow1 h3, .shadow2 h3, .shadow3 h3, .shadow4 h3, .shadow5 h3, .shadow6 h3, .shadow7 h3, .shadow8 h3{
  width:87%;
  height:100px;
  margin-left:6%;
  
  border-radius:5px;
}
/****************************************************************
*styling shadows
****************************************************************/
.shadow1:before, .shadow1:after{
  position:absolute;
  content:"";
  bottom:12px;left:15px;top:80%;
  width:45%;
  background:#9B7468;
  z-index:-1;
  -webkit-box-shadow: 0 20px 15px #9B7468;
  -moz-box-shadow: 0 20px 15px #9B7468;
  box-shadow: 0 20px 15px #9B7468;
  -webkit-transform: rotate(-6deg);
  -moz-transform: rotate(-6deg);
  transform: rotate(-6deg);
}
.shadow1:after{
  -webkit-transform: rotate(6deg);
  -moz-transform: rotate(6deg);
  transform: rotate(6deg);
  right: 15px;left: auto;
}
.shadow2:before{
  position:absolute;
  content:"";
  width:80%;
  top:140px;bottom:15px;left:30px;
  background-color:#9F8641;
  z-index:-1;
  -webkit-box-shadow:0 23px 17px 0 #9F8641;
  -moz-box-shadow:0 23px 17px 0 #9F8641;
  box-shadow: 0 23px 17px 0 #9F8641;
  -webkit-transform:rotate(-4deg);
  -moz-transform:rotate(-4deg);
  transform:rotate(-4deg);
}
.shadow3:before, .shadow3:after{
  content:"";
  position:absolute;
  bottom:0;top:2px;left:15px;right:15px;
  z-index:-1;
  border-radius:100px/30px;
  -webkit-box-shadow:0 0 30px 2px #479F41;
  -moz-box-shadow:0 0 30px 2px #479F41;
  box-shadow: 0 0 30px 2px #479F41;
}
.shadow4:before, .shadow4:after{
  position:absolute;
  content:"";
  top:14px;bottom:14px;left:0;right:0;
  box-shadow:0 0 25px 3px #548E7F;
  border-radius:100px/10px;
  z-index:-1;
}
.shadow5:before, .shadow5:after{
  position:absolute;
  content:"";
  box-shadow:0 10px 25px 20px #518C96;
  top:40px;left:10px;bottom:50px;
  width:15%;
  z-index:-1;
  -webkit-transform: rotate(-8deg);
  -moz-transform: rotate(-8deg);
  transform: rotate(-8deg);
}
.shadow5:after{
  -webkit-transform: rotate(8deg);
  -moz-transform: rotate(8deg);
  transform: rotate(8deg);
  right: 10px;left: auto;
}
.shadow6:before, .shadow6:after{
  position:absolute;
  content:"";
  top:100px;bottom:5px;left:30px;right:30px;
  z-index:-1;
  box-shadow:0 0 40px 13px #486685;
  border-radius:100px/20px; 
}
.shadow7:before, .shadow7:after{
  position:absolute;
  content:"1";
  top:25px;left:20px;bottom:150px;
  width:80%;
  z-index:-1;
  -webkit-transform: rotate(-6deg);
  -moz-transform: rotate(-6deg);
  transform: rotate(-6deg);
}
.shadow7:before{
  box-shadow:10px -10px 30px 15px #984D8E;
}
.shadow7:after{
  -webkit-transform: rotate(7deg);
  -moz-transform: rotate(7deg);
  transform: rotate(7deg);
  bottom: 25px;top: auto;
  box-shadow:10px 10px 30px 15px #984D8E;
}
.shadow8{
  box-shadow:
  -6px -6px 8px -4px rgba(250,254,118,0.75),
  6px -6px 8px -4px rgba(254,159,50,0.75),
  6px 6px 8px -4px rgba(255,255,0,0.75),
  6px 6px 8px -4px rgba(0,0,255,2.75);
}
</style>
@endsection
@section('conten')


<!-- Breadcomb area Start-->
<div class="breadcomb-area">
 <div class="container">
  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="breadcomb-list">
     <div class="row">
      <div class="timeline-wrapper"> 
        <link href='https://fonts.googleapis.com/css?family=Didact+Gothic' rel='stylesheet' type='text/css'>

        <h3>สิทธิประโยชน์</h3>
        <br><br>
        <div class="wrap">
          <div class="box box1 shadow1">
            <h3>VIP1</h3>
            <div>-ได้รับสิทธิประโยชน์สูงที่สุด</div>
            <div>-ได้ลดหย่อนค่าใช้จ่าย 25%</div>

          </div>
          <div class="box box2 shadow2">
            <h3>VIP2</h3>
            <div>-ได้รับสิทธิประโยชน์รองจาก VIP 1</div>
            <div>-ได้ลดหย่อนค่าใช้จ่าย 20%</div>
          </div>
          <div class="box box3 shadow3">
            <h3>VIP3</h3>
            <div>-ได้รับสิทธิประโยชน์ระดับกลาง</div>
            <div>-ได้ลดหย่อนค่าใช้จ่าย 15%</div>
          </div>
          <div class="box box4 shadow4">
            <h3>VIP4</h3>
            <div>-ได้รับสิทธิประโยชน์น้อย</div>
            <div>-ได้ลดหย่อนค่าใช้จ่าย 10%</div>
          </div>
          <div class="box box5 shadow5">
            <h3>VIP5</h3>
            <div>-ได้รับสิทธิประโยชน์น้อยที่สุด</div>
            <div>-ได้ลดหย่อนค่าใช้จ่าย 5%</div>
          </div>

        </div>  
      </div>

    </div>
  </div>
</div>
</div>                                     
</div>
</div>

@endsection
@section('js')
<script src="{{asset('public/js/bootstrap-slider.js')}}"></script>
<script src="{{asset('public/js/tawk-chat.js')}}"></script>
<script type="text/javascript">
  var slider = new Slider("#ex1", {
    ticks: [0, 1, 2, 3, 4,],
    ticks_labels: ['VIP1 วันที่ 01/02/2020', 'VIP2 วันที่ 01/02/2020', 'VIP3 วันที่ 01/02/2020', 'VIP4 วันที่ 01/02/2020', 'VIP5 วันที่ 01/02/2020'],
    ticks_snap_bounds: 0,
    tooltip: 'hide',
    enabled: false,
    value: 4
  });

  document.getElementById("ex1").disabled = true;

</script>
@endsection

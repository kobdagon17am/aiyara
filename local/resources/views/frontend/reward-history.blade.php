  
@extends('frontend.layouts.customer.customer_app')
@section('css')
<style>
    .bold{font-weight:bold;}
    .time{position:absolute; left:-110px;}
    .timeline-wrapper {
        padding-left:80px;
        min-width: 400px;
        font-family: 'Helvetica';
        font-size: 14px;
        /*border: 1px solid #CCC;*/
    }
    .StepProgress {
        position: relative;
        padding-left: 45px;
        list-style: none;
    }
    .StepProgress::before {
        display: inline-block;
        content: '';
        position: absolute;
        top: 0;
        left: 15px;
        width: 10px;
        height: 100%;
        border-left: 2px solid #CCC;
    }
    .StepProgress-item {
        position: relative;
        counter-increment: list;
    }
    .StepProgress-item:not(:last-child) {
        padding-bottom: 30px;
    }
    .StepProgress-item::before {
        display: inline-block;
        content: '';
        position: absolute;
        left: -30px;
        height: 100%;
        width: 10px;
    }
    .StepProgress-item::after {
        content: '';
        display: inline-block;
        position: absolute;
        top: 0;
        left: -36px;
        width: 15px;
        height: 15px;
        border: 2px solid #CCC;
        border-radius: 50%;
        background-color: #FFF;
    }
    .StepProgress-item.is-done::before {
        border-left: 2px solid green;
    }
    .StepProgress-item.is-done::after {
        /*content: "?";*/
        font-size: 10px;
        color: #FFF;
        text-align: center;
        border: 2px solid green;
        background-color: green;
    }
/*.StepProgress-item.current::before { 
border-left: 2px solid green;
}
.StepProgress-item.current::after {
content: counter(list);
padding-top: 1px;
width: 19px;
height: 18px;
top: -4px;
left: -40px;
font-size: 14px;
text-align: center;
color: green;
border: 2px solid green;
background-color: white;
}*/
.StepProgress strong {
    display: block;
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
                            <h3>รางวัลเกียรติยศ</h3>
                            <br><br>


                            <ul class="StepProgress">
                                <li class="StepProgress-item is-done">
                                    <h4><div class="bold">BRONZE STAR AWARD (BSA)</div></h4>
                                    <div>วันที่่ 01/02/2020</div>
                                </li>
                                <li class="StepProgress-item is-done">
                                    <h4><div class="bold">SILVER STAR AWARD (SSA)</div></h4>
                                    <div>วันที่ 02/02/2020</div>
                                    <div></div>
                                </li>
                                <li class="StepProgress-item is-done" ">
                                    <h4><div class="bold">GOLD STAR AWARD (GSA)</div></h4>
                                    <div>วันที่ 03/02/2020</div>
                                </li>
                                <li class="StepProgress-item current">
                                    <h4><div class="bold">DOUBLE GOLD STAR (DGS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">

                                    <h4><div class="bold">TRIPPLE GOLD STAR (TGS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">PLATINUM STAR (PS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">PEARL STAR (PES)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">BLACK PEARL STAR (BPS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">RUBY STAR (RUS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">SAPPHIRE STAR (SAS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">BLUE STAR (BLS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">EMERALD STAR (EMS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">DIAMOND STAR (DS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">EXECUTIVE DIAMOND STAR (EDS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">BLACK DIAMOND STAR (BDS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">DOUBLE DIAMOND STAR (DDS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">TRIPLE DIAMOND STAR (TDS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                                <li class="StepProgress-item">
                                    <h4><div class="bold">CROWN DIAMOND STAR (CDS)</div></h4>
                                    <div>&nbsp</div>
                                </li>
                            </ul>
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


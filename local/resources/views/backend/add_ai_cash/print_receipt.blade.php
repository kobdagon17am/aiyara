@php
@include(app_path() . '\Models\MyFunction.php');
@endphp
<style>

    body{
     font-family: "THSarabunNew";
     font-size: 20px;
     font-weight: bold;
     line-height: 14px !important;
    }

    @charset "utf-8";

    .NameAndAddress table {
        width: 100%;
        border-collapse: collapse;
        padding: 0px;

    }

    .NameAndAddress table thead {
        background: #ebebeb;
        color: #222222;
    }

    .NameAndAddress table tbody tr td b {
        width: 130px;
        display: inline-block;
    }

  /* DivTable.com */
  .divTable{
    display: table;
    width: 100%;
  }
  .divTableRow {
    display: table-row;
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
  }
  .divTableCell, .divTableHead {
    border: 1px solid white;
    display: table-cell;
    /*padding: 3px 10px;*/
  }
  .divTableHeading {
    background-color: #EEE;
    display: table-header-group;
    
  }
  .divTableFoot {
    background-color: #EEE;
    display: table-footer-group;
    
  }
  .divTableBody {
    display: table-row-group;
  }
  .divTH {text-align: right;}


  .inline-group {
    max-width: 5rem;
    /*padding: .5rem;*/
  }

  .inline-group .form-control {
    text-align: right;
  }

  .form-control[type="number"]::-webkit-inner-spin-button,
  .form-control[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  .quantity {width: 50px;text-align: right;color: blue;font-size: 16px;}

  .btn-minus,.btn-plus,.btn-minus-pro,.btn-plus-pro,.btn-plus-product-pro,.btn-minus-product-pro {background-color: bisque !important;}

  input[type="text"]:disabled {
    background: #f2f2f2;
  }


</style>
<?php 

         
      $value = DB::select(" 
                  SELECT
                  db_add_ai_cash.id,
                  db_add_ai_cash.customer_id_fk,
                  db_add_ai_cash.aicash_amt,
                  db_add_ai_cash.action_user,
                  db_add_ai_cash.pay_type_id,
                  db_add_ai_cash.cash_pay,
                  db_add_ai_cash.transfer_price,
                  db_add_ai_cash.credit_price,
                  db_add_ai_cash.account_bank_id,
                  db_add_ai_cash.transfer_money_datetime,
                  db_add_ai_cash.file_slip,
                  db_add_ai_cash.fee,
                  db_add_ai_cash.fee_amt,
                  db_add_ai_cash.charger_type,
                  db_add_ai_cash.sum_credit_price,
                  db_add_ai_cash.cash_price,
                  db_add_ai_cash.total_amt,
                  db_add_ai_cash.created_at,
                  db_add_ai_cash.updated_at,
                  db_add_ai_cash.deleted_at,
                  customers.user_name,
                  customers.prefix_name,
                  customers.first_name,
                  customers.last_name
                  FROM
                  db_add_ai_cash
                  Left Join customers ON db_add_ai_cash.customer_id_fk = customers.id
                  where db_add_ai_cash.id =  ".$data[0]."

           ");

 ?>


<div class="NameAndAddress" >

    <table style="" >
      <tr>
        <td style="width:50%;" > 
           <br>
         <?php
                 echo @$value[0]->user_name." <br> ".@$value[0]->prefix_name.@$value[0]->first_name.' '.@$value[0]->last_name."<br>";
         ?>
      </td>
      <td style="width:20%;vertical-align: top;" > 
        <br>
        <br>
        XXXXXXXXXX <br>
        <?=ThDate01(@$value[0]->created_at)?> 
      </td>
      </tr>
    </table>
  
        <br>
        <br>
        <br>

    <table style="border-collapse: collapse;vertical-align: top; " >
<!-- รายการสินค้า -->

          <tr style="  ">
            <td style="width:5%;text-align: center;" > 1 </td>
            <td style="width:20%;text-align: left;"> เติม Ai-Cash </td>
            <td style="width:10%;text-align: center;"> <?=number_format(@$value[0]->aicash_amt,2)?>  </td>
          </tr>


    </table>

<br>
<br>

  <table style="border-collapse: collapse;vertical-align: top;" >

    <tr>
      <td style="width:55%;font-size: 14px;">
       REF : [ 123456 ] AG : [ - ] SK : [ A123456 ] คะแนนครั้งนี้ : [  <?=@$sFrontstorePVtotal[0]->pv_total?> pv ]
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
    </tr>

    <tr>
      <td style="width:55%;font-size: 14px;">
       ชำระ : [ สด= ] พนักงาน : [ admin ] การจัดส่ง : [ 4/ ]
      </td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"></td>
      <td style="text-align: right;"> </td>
    </tr>


  </table>
</div>

 
function sent_type(type){
    if(type == 'other'){
        sent_address('sent_other')
        // document.getElementById("i_sent_address").classList.add('radio-disable');
        // document.getElementById("i_sent_address_card").classList.add('radio-disable');
        // document.getElementById("sent_address_check").disabled = true;
        // document.getElementById("sent_address_card_check").disabled = true;
        document.getElementById("check_user").style.display = "block";
    }else{
        sent_address('sent_address')
        document.getElementById("i_sent_address").classList.remove('radio-disable');
        document.getElementById("i_sent_address_card").classList.remove('radio-disable');
        document.getElementById("sent_address_check").disabled = false;
        document.getElementById("sent_address_card_check").disabled = false;
        document.getElementById("check_user").style.display = "none";

    }
  
}
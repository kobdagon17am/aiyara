@if ($message = Session::get('success'))
<script type="text/javascript">
    swal.fire({
        icon: 'success',
        title:'Success !',
        text:"{{Session::get('success')}}",
        // timer:4000,
        type:'success'
    }).then((value) => {
    }).catch(swal.noop);
</script>
@endif

@if ($message = Session::get('error'))
<script type="text/javascript">
    swal.fire({
    	icon: 'error',
        title:'Error !',
        text:"{{Session::get('error')}}",
        // timer:4000,
        type:'error'
    }).then((value) => {
    }).catch(swal.noop);
</script>
@endif
@if ($message = Session::get('warning'))
<script type="text/javascript">
    swal.fire({
        title:'Warning !',
        text:"{{Session::get('warning')}}",
        // timer:4000,
        type:'warning'
    }).then((value) => {
    }).catch(swal.noop);
</script>
@endif

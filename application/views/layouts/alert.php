<!-- 
<div class="alert alert-success">
  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
  <strong>Well done!</strong> You successfully read this important alert message.
</div> 

<div class="alert alert-info">
  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
  <strong>Heads up!</strong> This alert needs your attention, but it's not super important.
</div> 

<div class="alert alert-warning">
  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
  <strong>Warning!</strong> Best check yo self, you're not looking too good.
</div> 

<div class="alert alert-danger">
  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
  <strong>Oh snap!</strong> Change a few things up and try submitting again.
</div>  -->

<div id="alert" class="alert alert-{class}" style="position:absolute;z-index: 99999 !important;width:90%">
  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
  <strong>{label}</strong> {message}
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#alert").fadeOut(3000);
});
</script>
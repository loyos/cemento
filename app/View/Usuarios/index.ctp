<div id="index">
<?php echo $this->element('index') ?>
</div>
<div id="formulario_index" style="display:none">
<?php echo $this->element('formulario_index') ?>
</div>
<script>
	$("#boton_index").click(function (){
		//alert("sdds");
		$('#index').css('display','none');
		$('#formulario_index').css('display','block');
		return false;
	});
</script>
<div class="col-sm-12">
<h2>Explorador de Dispositivos</h2>
</div>
<div class="col-sm-12">	
	<section class="pinBoot-devices" data-type='<?php echo $_GET['device_type']; ?>' data-page="1" data-limit="8" data-manufacturer="<?php echo $_GET['device_manufacturer']; ?>"></section>
	<div class="spinFormaT spinFormaT-pinBoot">
		<i class="fas fa-spinner fa-spin"></i> 
		Cargando
	</div>	
	<a class="btn btn-sm btn-info btn-more-pblish" href="javascript:cargarDispositivosExplode();">Cargar Mas</a>
</div>

<style>
	article > footer {
		margin:0;
		padding:0;
		position: relative;
		width:100%;
		min-height:15px;
		left: 0px;
		bottom: 0;
		padding-top: 0.5em;
	}

	.pinBoot-devices {
	  position: relative;
	  max-width: 100%;
	  width: 100%;
	}
	img {
	  width: 100%;
	  max-width: 100%;
	  height: auto;
	}
	.white-panel {
	  position: absolute;
	  padding: 10px;
	}
	/*
	stylize any heading tags withing white-panel below
	*/

	.white-panel h1 {
	  font-size: 1em;
	}
	.white-panel h1 a {
	  color: #A92733;
	}
	.white-panel:hover {
	  margin-top: -5px;
	  -webkit-transition: all 0.3s ease-in-out;
	  -moz-transition: all 0.3s ease-in-out;
	  -o-transition: all 0.3s ease-in-out;
	  transition: all 0.3s ease-in-out;
	}
</style>
<script>
	$(document).ready(function() {
		$('.pinBoot-devices').pinterest_grid({
			no_columns: 4,
			padding_x: 10,
			padding_y: 10,
			margin_bottom: 50,
			single_column_breakpoint: 700
		});
	});
</script>

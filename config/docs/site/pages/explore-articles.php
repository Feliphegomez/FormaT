
<div class="col-sm-12">
	<section class="pinBoot" data-type='<?php echo $_GET['type']; ?>' data-page="1" data-limit="8" data-category="<?php echo $_GET['of']; ?>">
	</section>
	<div class="spinFormaT spinFormaT-pinBoot">
		<i class="fas fa-spinner fa-spin"></i> 
		Cargando
	</div>
	<a class="btn btn-sm btn-info btn-more-pblish" href="javascript:cargarUltimasPublicaciones();">Cargar Mas</a>
</div>

<style>
	article > footer {
		margin:0;
		padding:0;
		background-color: rgba(0,255,0,0.0);
		position: relative;
		width:100%;
		min-height:15px;
		left: 0px;
		bottom: 0;
		padding-top: 0.5em;
	}

	#pinBoot {
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
	  background: white;
	  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.3);
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
	  box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);
	  margin-top: -5px;
	  -webkit-transition: all 0.3s ease-in-out;
	  -moz-transition: all 0.3s ease-in-out;
	  -o-transition: all 0.3s ease-in-out;
	  transition: all 0.3s ease-in-out;
	}
</style>
<script>
$(document).ready(function() {
	$('.pinBoot').pinterest_grid({
		no_columns: 4,
		padding_x: 10,
		padding_y: 10,
		margin_bottom: 50,
		single_column_breakpoint: 700
	});
});
</script>
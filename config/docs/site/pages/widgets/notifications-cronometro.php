<style>
	body{ overflow-x:hidden;overflow-y:hidden; }
</style>


<div class="tab-main" onBlur="popUp.focus()" onLoad="popUp.focus()">
	<!--/tabs-inner-->
	<div class="tab-inner">
		<div class="col-md-12 graph-1">
			<!-- <h3 class="inner-tittle">Notificaciones FormaT</h3> -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<i class="fa fa-bell-o"></i>
					Cronometro
				</div>
				<div class="panel-body">

					<?php
						if(isset($_GET['action']) && $_GET['action'] == 'end' && isset($_GET['view']) && $_GET['view'] == 'cronometro'){
							?>
							<h3>Se acabo tu tiempo <br> ¿Necesitas tiempo adicional?</h3>
							<p>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(1);window.close();">+1 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(3);window.close();">+3 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(5);window.close();">+5 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:detenerCronometro(1);window.close();">Cerrar</a>
							</p>
							<?php
						}else if(isset($_GET['action']) && $_GET['action'] == 'timeAlerts' && isset($_GET['value']) && $_GET['value'] > 0){
							?>
							<h3>OYE!! Ten cuidado <br> </h3>
							<p>
								Te faltan <?php echo $_GET['value']; ?> Segundos para completar otro minuto.
								<br>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(1);window.close();">+1 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(3);window.close();">+3 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:PlayCronometro(5);window.close();">+5 Minuto</a>
								<a class="btn btn-sm btn-info" href="javascript:window.close();">Cerrar</a>
							</p>
							<?php
						};
					?>
				</div>
			</div>											
		</div>
		<div class="clearfix"> </div>
	</div>
</div>




<style>
body{
	padding: 2em;
}
#sidebar{
    overflow: hidden;
    display: none;
    visibility: none;
    display: none;
    position: fixed;
    width: 0;
    left: 0;
    top: 0;
    height: 0;
	
}

.navbar-top {
    overflow: hidden;
    display: none;
    visibility: none;
    display: none;
    position: fixed;
    width: 0;
    left: 0;
    top: 0;
    height: 0;
}
footer {
	
    overflow: hidden;
    display: none;
    visibility: none;
    display: none;
    position: fixed;
    width: 0;
    left: 0;
    top: 0;
    height: 0;
}
</style>
<?php if(!isset($_GET['of'])){ $_GET['of'] = 0; }; ?>
	
<!-- Modal View Capas Calendary -->
<div class="modal fade" id="bd-modal-view-capas-calendary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">Capacitaciones Visor Web</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
					<h1 class="second" id="modal-view-capas-calendary-input-category_name">Capa</h1>
						<font disabled="" type="hidden" id="modal-view-capas-calendary-input-category" style="display:none"></font>
					<div class="form-group" style="display:none">
						<label for="modal-view-capas-calendary-input-id" class="form-control-label">id</label>
						<font id="modal-view-capas-calendary-input-id"></font>
					</div>
					<div class="form-group" style="display:none">
						<label for="modal-view-capas-calendary-input-piloto_name" class="form-control-label">Piloto</label>
						<font disabled="" type="hidden" id="modal-view-capas-calendary-input-piloto"></font>
						<font id="modal-view-capas-calendary-input-piloto_name"></font>
					</div>
					
					<div class="form-group">
						<label for="modal-view-capas-calendary-input-lugar" class="form-control-label">Lugar</label>
						<font id="modal-view-capas-calendary-input-lugar"></font>
					</div>
					<div class="form-group">
						<label for="modal-view-capas-calendary-input-encargado" class="form-control-label">Encargado</label>
						<font id="modal-view-capas-calendary-input-encargado"></font>
					</div>
					<div class="form-group">
						<label for="modal-view-capas-calendary-input-fecha" class="form-control-label">Fecha</label>
						<font id="modal-view-capas-calendary-input-fecha"></font>
					</div>
					<div class="form-group">
						<label for="modal-view-capas-calendary-input-hora_inicio" class="form-control-label">Hora Inicio</label>
						<font id="modal-view-capas-calendary-input-hora_inicio"></font>
					</div>
					<div class="form-group">
						<label for="modal-view-capas-calendary-input-hora_fin" class="form-control-label">Hora Fin</label>
						<font id="modal-view-capas-calendary-input-hora_fin"></font>
					</div>
			</div>
			<div class="modal-footer">
				<?php /*if(calendaryEditEnable() == true){ ?>
				<?php } ?>
				<?php if(calendaryDeleteEnable() == true){ ?>
				<?php }*/ ?>
				
				
				<a class="btn btn-warning btn-edit-capa" href="#">Modificar</a>
				<a class="btn btn-danger btn-delete-capa" href="#">Eliminar</a>
			
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Create Capas Calendary -->
<div class="modal fade" id="bd-modal-create-capas-calendary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
		
			<div class="modal-header">
				<h5 class="modal-title" id="">Capacitacion Crear</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-fecha">Fecha</span>
					<input name="fecha" type="date" class="form-control" placeholder="Fecha" aria-describedby="sizing-fecha" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-hora_inicio">Hora Inicio</span>
					<input name="hora_inicio" type="text" class="form-control" placeholder="HH:MM:SS" aria-describedby="sizing-hora_inicio" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-hora_fin">Hora Fin</span>
					<input name="hora_fin" type="text" class="form-control" placeholder="HH:MM:SS" aria-describedby="sizing-hora_fin" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-lugar">Sede y/o Sala</span>
					<input name="lugar" type="text" class="form-control" placeholder="Sede y/o Sala" aria-describedby="sizing-lugar" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-encargado">Encargado</span>
					<input name="encargado" type="text" class="form-control" placeholder="Formador/Persona Encargado" aria-describedby="sizing-encargado" required="true">
				</div>
				
				<input name="category" type="hidden" value="<?php echo $_GET['of']; ?>" required="true">
			</div>
			<div class="modal-footer">
				<button onclick="javascript:createCalendaryModal();" type="button" class="btn btn-primary">Publicar</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		
		</div>
	</div>
</div>

<!-- Modal edit Capas Calendary -->
<div class="modal fade" id="bd-modal-edit-capas-calendary" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">Capacitacion Editar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-fecha">Fecha</span>
					<input name="fecha" type="date" class="form-control" placeholder="Fecha" aria-describedby="sizing-fecha" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-hora_inicio">Hora Inicio</span>
					<input name="hora_inicio" type="text" class="form-control" placeholder="Hora Inicio" aria-describedby="sizing-hora_inicio" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-hora_fin">Hora Fin</span>
					<input name="hora_fin" type="text" class="form-control" placeholder="Hora Fin" aria-describedby="sizing-hora_fin" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-lugar">Sede y/o Sala</span>
					<input name="lugar" type="text" class="form-control" placeholder="Sede y/o Sala" aria-describedby="sizing-lugar" required="true">
				</div>
				<div class="input-group input-group-sm">
					<span class="input-group-addon" id="sizing-encargado">Encargado</span>
					<input name="encargado" type="text" class="form-control" placeholder="Formador/Persona Encargado" aria-describedby="sizing-encargado" required="true">
				</div>
				
				<input name="category" type="hidden" value="" required="true">
				<input name="id" type="hidden" value="" required="true">
				<input name="action_forms" type="hidden" value="editCalendary" required="true">
			</div>
			<div class="modal-footer">
				<button onclick="javascript:editCalendaryModal();" type="button" class="btn btn-primary">Modificar</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
	
	
<div class="sub-heard-part">
	<ol class="breadcrumb m-b-0">
		<li class="active">Calendario</li>
	</ol>
</div>

<div class="col-sm-12">
	<h2 class="inner-tittle">Calendario</h2>
</div>
<div class="col-sm-12">
	<div class="group-block">
		<a class="btn btn-sm btn-success" href="javascript:$('#bd-modal-create-capas-calendary').modal('show');"><i class="fas fa-plus"></i></a>
		<a class="btn green two" href="javascript:cargarCalendary(<?php echo (int) $_GET['of']; ?>,'none');">Ninguno</a>
		<a class="btn green two" href="javascript:cargarCalendary(<?php echo (int) $_GET['of']; ?>,'formador');">Por Formador</a>
		<a class="btn green two" href="javascript:cargarCalendary(<?php echo (int) $_GET['of']; ?>,'tema');">Por Capa</a>
	</div>
</div>
<div class="col-sm-12">
	<div class="block-page">
		<div class="gantt"></div>
		<script>
			$(function() {
				cargarCalendary(<?php echo $_GET['of']; ?>,'formador');
			});
		</script>
	</div>
</div>

<?php
	
?>

<div class="col-sm-12 history-alerts-page-view">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Historial de Alertas</div>
		<div class="panel-body">
			<p>Aqui podras ver las alertas que ya fueron desactivadas y su respectiva informacion</p>
		</div>

		<!-- Table -->
		<table class="table table-responsive table-history">
			<thead>
				<tr>
					<th>Id interna</th>
					<th>Titulo</th>
					<th>Mensaje</th>
					<th>Ticket</th>
					<th>Fecha Apertura</th>
					<th>Fecha Cierre</th>
					<th>Re-Activar</th>
				</tr>
			</thead>
			<tbody class="table-history-body">
				<?php if(isset($history[0])){ ?>
					<?php foreach($history As $element){ ?>
						<tr id="history-alert-id-<?php echo $element['id']; ?>">
							<td><?php echo $element['id']; ?></td>
							<td><?php echo $element['title']; ?></td>
							<td><?php echo $element['message']; ?></td>
							<td><?php echo $element['ticket']; ?></td>
							<td><?php echo $element['fecha_apertura']; ?></td>
							<td><?php echo $element['fecha_cierre']; ?></td>
							<td><a href="javascript:reactivarAlert(<?php echo $element['id']; ?>);"><i class="fas fa-toggle-on"></i></a></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>			
		</table>
	</div>
</div>
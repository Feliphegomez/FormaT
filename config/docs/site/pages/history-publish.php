

<div class="col-sm-12 history-publish-page-view">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Historial</div>
		<div class="panel-body">
			<p>Aqui podras ver las publicaciones que ya fueron desactivadas y su respectiva informacion</p>
		</div>

		<!-- Table -->
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Id interna</th>
					<th>Titulo</th>
					<th>category</th>
					<th>author</th>
					<th>Fecha Creacion</th>
					<th>Fecha Modificacion</th>
					<th>Re-Activar</th>
				</tr>
			</thead>
			<tbody class="table-history-body">
				<?php if(isset($history[0])){ ?>
					<?php foreach($history As $element){ 
						$element['author'] = cargarNamePeopleForUserid($element['author']);
					?>
						<tr id="history-publish-id-<?php echo $element['id']; ?>">
							<td><?php echo $element['id']; ?></td>
							<td><?php echo $element['title']; ?></td>
							<td><?php echo categoryNameById($element['category']); ?></td>
							<td><?php echo $element['author']['user']; ?></td>
							<td><?php echo $element['fcreate']; ?></td>
							<td><?php echo $element['fchange']; ?></td>
							<td><a href="javascript:reactivarPublish(<?php echo $element['id']; ?>);"><i class="fas fa-toggle-on"></i></a></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>			
		</table>
	</div>
</div>

<div class="col-sm-12 draft-quiz-page-view">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Borradores de Quiz</div>
		<div class="panel-body">
			<p>Aqui podras ver las alertas que ya fueron desactivadas y su respectiva informacion</p>
		</div>

		<!-- Table -->
		<table class="table table-responsive">
			<thead>
				<tr>
					<th>Id interna</th>
					<th>Titulo</th>
					<th>Fecha Creacion</th>
					<th>Contiuar Editando</th>
				</tr>
			</thead>
			<tbody class="table-drafts-body">
				<?php if(isset($history[0])){ ?>
					<?php foreach($history As $element){ ?>
						<tr id="history-alert-id-<?php echo $element['id']; ?>">
							<td><?php echo $element['id']; ?></td>
							<td><?php echo $element['title']; ?></td>
							<td><?php echo $element['fecha_creation']; ?></td>
							<td><a href="index.php?pageActive=create&type=quiz&draft=<?php echo $element['id']; ?>"><i class="fas fa-toggle-on"></i></a></td>
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>			
		</table>
	</div>
</div>
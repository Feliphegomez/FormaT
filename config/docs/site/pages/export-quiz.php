
<div class="col-sm-12 stadist-quiz-page-export">
	<div class="col-sm-12">
		<h3>Exportar Último Quiz</h3>
		<p>Seleccione el tipo de archivo en el que desea exportar la informacion</p>
		<p>
			<button class="btn btn-sm btn-info" id="btnExport" onclick="javascript:xport.toCSV('testTable');"> Exportar en CSV</button> <em>&nbsp;&nbsp;&nbsp;Exportar la tabla a CSV para todos los navegadores</em>
			<!--
			<button id="btnExport" onclick="javascript:xport.toXLS('testTable');"> Export to XLS</button> <em>&nbsp;&nbsp;&nbsp;Export the table to XLS with CSV fallback for IE & Edge</em>
			<button id="btnExport" onclick="javascript:xport.toXLS('testTable', 'outputdata');"> Export to XLS</button> <em>&nbsp;&nbsp;&nbsp;Export the table to XLS with custom filename</em>
			-->
		</p>
		<br />
	</div>
	<hr>
</div>

<div class="col-sm-12">
	<div class="col-sm-12">
		<h3 class="title-quiz">title</h3>
	</div>
	<div class="col-sm-4">
		Fecha: <font class="fecha_creation-quiz"></font>
	</div>
	<div class="col-sm-4">
		Total: <font class="total-result-quiz"></font>
	</div>
	<div class="col-sm-4">
		Total Preguntas: <font class="total-quiz"></font>
	</div>
</div>

<div class="col-sm-12">
	<div class="container">
		<div class="row">
			<div class="panel panel-primary filterable">
				<div class="panel-heading">
					<h3 class="panel-title title-quiz">title</h3>
					<div class="pull-right">
						<button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filtro</button>
					</div>
				</div>
				<table class="table table-responsive" id="testTable">
					<thead>
						<tr class="filters">
							<th><input type="text" class="form-control" placeholder="Login" disabled></th>
							<th><input type="text" class="form-control" placeholder="Nombre" disabled></th>
							<th><input type="text" class="form-control" placeholder="Respuestas" disabled></th>
							<th><input type="text" class="form-control" placeholder="T. Correctas" disabled></th>
							<th><input type="text" class="form-control" placeholder="T. A Mejorar" disabled></th>
							<th><input type="text" class="form-control" placeholder="% Aprobacion" disabled></th>
						</tr>
					</thead>
					<tbody class="querys-body">
						<?php /*foreach($results as $person){ 
							$person['user'] = ProfileForUserid($person['user']);
							$person['result'] = json_decode($person['result']);
						?>
						<tr>
							<td><?php echo $person['user']['user']; ?></td>
							<td><?php echo $person['user']['nombre']; ?></td>
							<td>
								<ul class="list-group">
									<?php
										foreach($person['result'] As $queryR){
											$nota = (int) $queryR->response->value;
											if($nota>0){
												$color = "success";
											}else{
												$color = "danger";
											}
											
											echo "<li class='list-group-item alert-{$color}' title='{$queryR->response->text}' data-toggle='tooltip'>{$queryR->query}</li>";
											#echo $queryR['response']->text;
										}
									?>
								</ul>
							</td>
							<td><?php echo $person['result_note'] ?></td>
							<td><?php echo (count($querys)-$person['result_note']); ?></td>
						</tr>
						<?php }*/ ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
.filterable {
    margin-top: 15px;
}
.filterable .panel-heading .pull-right {
    margin-top: -20px;
}
.filterable .filters input[disabled] {
    background-color: transparent;
    border: none;
    cursor: auto;
    box-shadow: none;
    padding: 0;
    height: auto;
}
.filterable .filters input[disabled]::-webkit-input-placeholder {
    color: #333;
}
.filterable .filters input[disabled]::-moz-placeholder {
    color: #333;
}
.filterable .filters input[disabled]:-ms-input-placeholder {
    color: #333;
}

</style>

<script>
/*
Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
*/
$(document).ready(function(){
    $('.filterable .btn-filter').click(function(){
        var $panel = $(this).parents('.filterable'),
        $filters = $panel.find('.filters input'),
        $tbody = $panel.find('.table tbody');
        if ($filters.prop('disabled') == true) {
            $filters.prop('disabled', false);
            $filters.first().focus();
        } else {
            $filters.val('').prop('disabled', true);
            $tbody.find('.no-result').remove();
            $tbody.find('tr').show();
        }
    });

    $('.filterable .filters input').keyup(function(e){
        /* Ignore tab key */
        var code = e.keyCode || e.which;
        if (code == '9') return;
        /* Useful DOM data and selectors */
        var $input = $(this),
        inputContent = $input.val().toLowerCase(),
        $panel = $input.parents('.filterable'),
        column = $panel.find('.filters th').index($input.parents('th')),
        $table = $panel.find('.table'),
        $rows = $table.find('tbody tr');
        /* Dirtiest filter function ever ;) */
        var $filteredRows = $rows.filter(function(){
            var value = $(this).find('td').eq(column).text().toLowerCase();
            return value.indexOf(inputContent) === -1;
        });
        /* Clean previous no-result if exist */
        $table.find('tbody .no-result').remove();
        /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
        $rows.show();
        $filteredRows.hide();
        /* Prepend no-result row if all rows are filtered */
        if ($filteredRows.length === $rows.length) {
            $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
        }
    });
});

var xport = {
  _fallbacktoCSV: true,  
  toXLS: function(tableId, filename) {   
    this._filename = (typeof filename == 'undefined') ? tableId : filename;
    
    //var ieVersion = this._getMsieVersion();
    //Fallback to CSV for IE & Edge
    if ((this._getMsieVersion() || this._isFirefox()) && this._fallbacktoCSV) {
      return this.toCSV(tableId);
    } else if (this._getMsieVersion() || this._isFirefox()) {
      alert("Not supported browser");
    }

    //Other Browser can download xls
    var htmltable = document.getElementById(tableId);
    var html = htmltable.outerHTML;

    this._downloadAnchor("data:application/vnd.ms-excel" + encodeURIComponent(html), 'xls'); 
  },
  toCSV: function(tableId, filename) {
    this._filename = (typeof filename === 'undefined') ? tableId : filename;
    // Generate our CSV string from out HTML Table
    var csv = this._tableToCSV(document.getElementById(tableId));
    // Create a CSV Blob
    var blob = new Blob([csv], { type: "text/csv" });

    // Determine which approach to take for the download
    if (navigator.msSaveOrOpenBlob) {
      // Works for Internet Explorer and Microsoft Edge
      navigator.msSaveOrOpenBlob(blob, this._filename + ".csv");
    } else {      
      this._downloadAnchor(URL.createObjectURL(blob), 'csv');      
    }
  },
  _getMsieVersion: function() {
    var ua = window.navigator.userAgent;

    var msie = ua.indexOf("MSIE ");
    if (msie > 0) {
      // IE 10 or older => return version number
      return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
    }

    var trident = ua.indexOf("Trident/");
    if (trident > 0) {
      // IE 11 => return version number
      var rv = ua.indexOf("rv:");
      return parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
    }

    var edge = ua.indexOf("Edge/");
    if (edge > 0) {
      // Edge (IE 12+) => return version number
      return parseInt(ua.substring(edge + 5, ua.indexOf(".", edge)), 10);
    }

    // other browser
    return false;
  },
  _isFirefox: function(){
    if (navigator.userAgent.indexOf("Firefox") > 0) {
      return 1;
    }
    
    return 0;
  },
  _downloadAnchor: function(content, ext) {
      var anchor = document.createElement("a");
      anchor.style = "display:none !important";
      anchor.id = "downloadanchor";
      document.body.appendChild(anchor);

      // If the [download] attribute is supported, try to use it
      
      if ("download" in anchor) {
        anchor.download = this._filename + "." + ext;
      }
      anchor.href = content;
      anchor.click();
      anchor.remove();
  },
  _tableToCSV: function(table) {
    // We'll be co-opting `slice` to create arrays
    var slice = Array.prototype.slice;

    return slice
      .call(table.rows)
      .map(function(row) {
        return slice
          .call(row.cells)
          .map(function(cell) {
            return '"t"'.replace("t", cell.textContent);
          })
          .join(",");
      })
      .join("\r\n");
  }
};

</script>
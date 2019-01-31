
<!-- Crear una nueva pregunta para el quiz -->
<div class="modal fade" id="modal-create-question-quiz" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Nueva Pregunta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4>Tu pregunta</h4>
					<input name="topic" type="hidden" class="form-control" >
					<input name="query" type="text" class="form-control" >
					
					<h4>Respuestas:</h4>
					<div class="contacts">
						<div class="form-group multiple-form-group">
							<div class="col-sm-8">
								<input name="text" type="text" class="form-control" >
							</div>
							<div class="col-sm-3">
								<select name="value" class="form-control">
									<option value="false">Erronea</option>
									<option value="true">Correcta</option>
								</select>
							</div>
							<div class="col-sm-1">
								<span class="input-group-btn">
									<button type="button" class="btn btn-success btn-add">+</button>
								</span>
							</div>
						</div>
					</div>
					
				</div>
		
			</div>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <a href="javascript:createQuestionQuizFast()" class="btn btn-primary"  >Crear</a>
      </div>
    </div>
  </div>
</div>

<div class="col-sm-12 quiz-page-edit">
	<h3>Edicion de Quiz</h3>	
	
	<div class="col-sm-12">
		<h3 ><font class="title-edit-quiz"></font></h3>
		<div class="col-sm-6">
			Fecha: <font class="title-edit-fecha_creation"></font>
		</div>
		<div class="col-sm-6">
			Total Preguntas: <font class="title-edit-total"></font>
		</div>
		
		
		<div class="container">
			<div class="row">
				<h2>Preguntas y Respuestas</h2>
				
				<div class="col-sm-12 querys-body"></div>
				
				<div class="col-sm-12">
					<a class="btn btn-sm btn-info hideRun btn-quiz-create-querys" href="javascript:dialogCreateQuestionQuizFast(<?php echo $_GET['draft']; ?>)"><i class="fas fa-plus"></i> Nueva Pregunta</a>
					<a class="btn btn-sm btn-success hideRun btn-quiz-publish" href="javascript:activeQuiz(<?php echo $_GET['draft']; ?>);">Guardar y Publicar</a>
					<a class="btn btn-sm btn-danger hideRun btn-quiz-delete" href="javascript:deleteQuiz(<?php echo $_GET['draft']; ?>)">Eliminar</a>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
(function ($) {
    $(function () {
        var addFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');
            var $formGroupClone = $formGroup.clone();

            $(this)
                .toggleClass('btn-success btn-add btn-danger btn-remove')
                .html('–');

            $formGroupClone.find('input').val('');
            $formGroupClone.find('.concept').text('Phone');
            $formGroupClone.insertAfter($formGroup);

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', true);
            }
        };

        var removeFormGroup = function (event) {
            event.preventDefault();

            var $formGroup = $(this).closest('.form-group');
            var $multipleFormGroup = $formGroup.closest('.multiple-form-group');

            var $lastFormGroupLast = $multipleFormGroup.find('.form-group:last');
            if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
                $lastFormGroupLast.find('.btn-add').attr('disabled', false);
            }

            $formGroup.remove();
        };

        var selectFormGroup = function (event) {
            event.preventDefault();

            var $selectGroup = $(this).closest('.input-group-select');
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();

            $selectGroup.find('.concept').text(concept);
            $selectGroup.find('.input-group-select-val').val(param);

        }

        var countFormGroup = function ($form) {
            return $form.find('.form-group').length;
        };

        $(document).on('click', '.btn-add', addFormGroup);
        $(document).on('click', '.btn-remove', removeFormGroup);
        $(document).on('click', '.dropdown-menu a', selectFormGroup);

    });
})(jQuery);

tinymce.init({
	selector: '.title-edit-quiz',
	inline: true,
	plugins: 'save',
	toolbar: 'undo redo save',
	save_onsavecallback: function (r) {		
		FormaT.app("POST", "quiz", {
			"action":"change",
			"id":<?php echo $_GET['draft']; ?>,
			"title":$(".title-edit-quiz").text()
		}, function(r){
			console.log(r);
			if(r.error === false){
				$.notify("Guardado...", "success");
			}else{
				$.notify("Error modificando el titulo...", "error");
			}
		});
	},
	menubar: false
});
</script>

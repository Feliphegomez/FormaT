/**  --------------------------------------------------------------- #
#  author: FelipheGomez
#  author URL: http://demedallo.com  && http://feliphegomez.info
#  License: Creative Commons Attribution 3.0 Unported
#  License URL: http://creativecommons.org/licenses/by/3.0/
#  --------------------------------------------------------------- **/

function cargarImportPage(){
	console.log("Cargando cargarImportPage()")
	param = getUrlVars();
	if(param.import && param.import>0){
		console.log("Cargando Personal.")
		FormaT.app("POST", "masive", 
		{
			"action":"export",
			"id":param.import,
			"type":"people"
			//"update":15
		}, function(r){
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				
				if (typeof r.data !== 'undefined' && r.data !== null) {
					for (b = 0; b < r.data.length; b++) {
						
						
						if(r.data[b].user !== '' && r.data[b].nombre !== ''){
							$number = b+1;
							$ht = '<tr class="people-import-id-'+$number+'">';
								$ht += '<td><a href="javascript:deletePeopleImport('+$number+');" class="btn btn-danger"><i class="fas fa-user-times"></i></a></td>';
								$ht += '<td>'+$number+'</td>';
								$ht += '<td>'+r.data[b].user+'</td>';
								$ht += '<td>'+r.data[b].nombre+'</td>';
								$ht += '<td>'+r.data[b].cargo_name+'</td>';
								$ht += '<td>'+r.data[b].piloto_name+'</td>';
								$ht += '<td>'+r.data[b].estado_name+'</td>';
								$ht += '<td><a data-update="'+$number+'" data-id="'+param.import+'" href="javascript:updatePeopleImport('+$number+','+param.import+');" class="btn btn-success btn-update-people"><i class="fas fa-user-plus"></i></a></td>';
							$ht += '</tr>';
							$(".people-body").append($ht);
						}
								
					}
				}
				
			}else{
				$.notify(r.message, "error");
			}
		});
	}
};

function updateAllPeopleImport(){
	$('.btn-update-people').each(function(index) {
		datos = $(this).data();
		if(datos.id !== undefined && datos.update !== undefined){
			updatePeopleImport(datos.update,datos.id);
		}
		
	});
};

function updatePeopleImport(updateTemp,idTemp){
	FormaT.app("POST", "masive", 
	{
		"action":"export",
		"id":idTemp,
		"type":"people",
		"update":updateTemp
	}, function(r){
		console.log(r);
		if(r.error === false){
			//$.notify(r.message, "success");
			if(r.data[0].create == true){
				$.notify("Usuario Actualizado.", "success");
				deletePeopleImport(updateTemp);
			}else{
				$.notify("No se actualizo el usuario.", "success");
			}
		}else{
			$.notify(r.message, "error");
		}
	});
};

function deletePeopleImport(id){
	if($(".people-import-id-"+id).length > 0){
		$(".people-import-id-"+id).remove();
	}
};

function cargarNotificacionPage(){
	console.log("Cargando cargarNotificacionPage().");
	$("#inbox").html("");
	$("#sidebar").html("");
	$(".navbar-top").html("");
	
	param = getUrlVars();
	if(param.pageActive && param.view && param.id){
		console.log("Cargando Notificacion.");
		FormaT.app("POST", "alerts", 
		{
			"action":"view",
			"id":param.id
		}, function(r){
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				$(".view-page-alerts-id").html(r.data.id);
				$(".view-page-alerts-title").html(r.data.title);
				$(".view-page-alerts-message").html(r.data.message);
				$(".view-page-alerts-ticket").html(r.data.ticket);
				$(".view-page-alerts-fecha_apertura").html(r.data.fecha_apertura);
				
			}else{
				$.notify(r.message, "error");
			}
		});
		
	}
}

function cargarQuizEditPage(){
	console.log("Cargando cargarQuizEditPage().");
	
	param = getUrlVars();
	if(param.pageActive == 'create-quiz' && param.type == 'quiz' && param.draft > 0){
		console.log("Cargando quiz.");
		FormaT.app("POST", "quiz", 
		{
			"action":"view",
			"page":"quiz",
			"id":param.draft
		}, function(r){
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				if(r.permisos && r.permisos !== undefined){
					if(r.permisos.create == true){ $(".btn-quiz-create-querys").show(); };
					if(r.permisos.edit == true){ $(".btn-quiz-publish").show(); };
					if(r.permisos.delete == true){ $(".btn-quiz-delete").show(); };
				}
				
				
				$(".title-edit-quiz").html(r.quiz.title);
				$(".title-edit-fecha_creation").html(r.quiz.fecha_creation);
				$(".title-edit-total").html(r.quiz.querys.length);
				
				
				if (typeof r.quiz.querys !== 'undefined' && r.quiz.querys !== null) {
					for (b = 0; b < r.quiz.querys.length; b++) {
						$(".querys-body").append(parseQueryPageCreate(r.quiz.querys[b]));
					}
				}
				//r.quiz.querys
				//$(".querys-body").append(crearQueryPageCreate(r));
			}else{
				$.notify(r.message, "error");
			}
		});
		
	}
}

function parseQueryPageCreate(element){
	$j = '<div class="col-sm-12" id="question-id-'+element.id+'">';
		$j += '<div class="col-sm-1">';
			$j += '<a href="javascript:deleteQuestionQuiz('+element.id+');"><i class="fas fa-ban"></i></a>';
		$j += '</div> ';
		$j += '<div class="col-sm-5">';
			$j += '<label class="query-question-'+element.id+'">'+element.query+'</label>';
		$j += '</div>';
		$j += '<div class="col-sm-6">';
			$j += '<ul class="list-group query-question-'+element.id+'">';
			
			if(element.response != undefined){
				console.log("Cargando element.response");
				for (i = 0; i < element.response.length; i++) {
					
					$j += '<li class="list-group-item">';
						$j += element.response[i].text;
						if(element.response[i].value == "true"){
							$j += ' [ Correcta ] ';
							
						}else if(element.response[i].value == "false"){
							$j += ' [ Incorrecta ] ';
						}
					$j += '</li>';
				}
			}
			$j += '</ul>';
		$j += '</div>';
	$j += '</div>';
	return $j;
}

function cargarQuizCurrentPageResult(){
	console.log("Cargando cargarQuizCurrentPageResult().");
	
	FormaT.app("POST", "quiz", 
	{
		"action":"view",
		"page":"quiz",
		"current":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if(r.quiz.presencia == true){
				
				$(".quiz-current-title").html(r.quiz.title);
				$(".quiz-current-id").html(r.quiz.id);
				$(".quiz-current-fecha_creation").html(r.quiz.fecha_creation);
				$(".quiz-current-points-result").html(r.quiz.result.result_note+' / '+r.quiz.querys.length);
				
				for (b = 0; b < r.quiz.result.result_note; b++) {
					$y = '<button type="button" class="" aria-label="Left Align"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></button>';
					$(".quiz-current-items-stars").append($y);
				}
				
				
				if (typeof r.quiz.result.result !== 'undefined' && r.quiz.result.result !== null) {
					$(".quiz-current-total-querys").html(r.quiz.querys.length);
					
					$h = '';
					for (i = 0; i < r.quiz.result.result.length; i++) {
						$h += createQueryQuizCurrentpage(r.quiz.result.result[i]);
					}
					$(".quiz-current-items").append($h);
				};
				
				
			}
		}else{
			$.notify(r.message, "error");
		}
	});
}

function createQueryQuizCurrentpage(element){
	console.log("Cargando createQueryQuizCurrentModal().");
	$ht = '<div class="form-group">';
		$ht += '<label for="name" class="cols-sm-2 control-label " >'+element.query+'</label>';
		$ht += '<div class="cols-sm-10">';
			$ht += '<div class="input-group">';
				$ht += '<span class="input-group-addon"><i class="fas fa-question fa" aria-hidden="true"></i></span>';				
				$ht += '<select name="'+element.id+'" class="form-control quiz-current-query-response"  data-query="'+element.query+'" >';
					if (typeof element.response !== 'undefined' && element.response !== null) {
						$ht += '<option value="'+element.response.value+'">'+element.response.text+'</option>';
					}
				$ht += '</select>';
			$ht += '</div>';
		$ht += '</div>';
	$ht += '</div>';
	return $ht;
};

function cargarQuizCurrent(){
	console.log("Cargando cargarQuizCurrent().");
	
	FormaT.app("POST", "quiz", 
	{
		"action":"view",
		"page":"quiz",
		"current":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if(r.quiz.presencia == false && r.quiz.querys.length > 0){
				$.notify("Debes presentar el quiz para continuar.", "info");
				
				$link = FormaT.options.site_url+'index.php?pageActive=f5-quiz&piloto='+FormaT.loadSession().authResponse.signedRequest.piloto+'&id='+FormaT.loadSession().authResponse.signedRequest.id;
				
				$("#modal-view-quiz-current").modal("show");
				
				$(".quiz-current-title").html(r.quiz.title);
				$(".quiz-current-id").html(r.quiz.id);
				$(".quiz-current-fecha_creation").html(r.quiz.fecha_creation);
				$('#modal-view-quiz-current input[name="response_quiz"]').val(r.quiz.id);
				
				
				if (typeof r.quiz.querys !== 'undefined' && r.quiz.querys !== null) {
					$(".quiz-current-total-querys").html(r.quiz.querys.length);
					
					$h = '';
					for (i = 0; i < r.quiz.querys.length; i++) {
						$h += createQueryQuizCurrentModal(r.quiz.querys[i]);
					}
					$(".quiz-current-items").append($h);
				};
				
				if(FormaT.loadSession().authResponse.signedRequest.permisos.quiz.edit == true){
					$(".quiz-current-btn-edit").show();
					$(".quiz-current-btn-edit").attr('href',"javascript:disableQuizAndEdit("+r.quiz.id+");");
				}
				
				
			}
		}else{
			// Mensaje / No hay quiz activo o error de la API
			//$.notify(r.message, "error");
		}
	});
};

function createQueryQuizCurrentModal(element){
	console.log("Cargando createQueryQuizCurrentModal().");
	$ht = '<div class="form-group">';
		$ht += '<label for="name" class="cols-sm-2 control-label " >'+element.query+'</label>';
		$ht += '<div class="cols-sm-10">';
			$ht += '<div class="input-group">';
				$ht += '<span class="input-group-addon"><i class="fas fa-question fa" aria-hidden="true"></i></span>';				
				$ht += '<select name="'+element.id+'" class="form-control quiz-current-query-response"  data-query="'+element.query+'" >';
					if (typeof element.response !== 'undefined' && element.response !== null) {
						$ht += '<option value=""> Elija su respuesta... </option>';
						for (a = 0; a < element.response.length; a++) {
							$ht += '<option value="'+element.response[a].value+'">'+element.response[a].text+'</option>';
						}
					}
				$ht += '</select>';
			$ht += '</div>';
		$ht += '</div>';
	$ht += '</div>';
	return $ht;
};

function submitQuizCurrent(){
	console.log("Cargando submitQuizCurrent().");
	response = new Array();
	$('.quiz-current-query-response').each(function(index) {
		if($(this).val() !== ''){
			$thisElement = $(this);
			$datos = {};
			$datos.id = $(this).attr("name");
			$datos.query = $(this).data("query");
			$datos.response = {};
			$datos.response.text = $('#modal-view-quiz-current select[name="'+$(this).attr("name")+'"] option:selected').text();
			$datos.response.value = $(this).val();
			response.push($datos);
			
		}else{
			$(this).focus();
			 return false;
		}
	});
	
	if(response.length > 0){
		$topic = $('#modal-view-quiz-current input[name="response_quiz"]').val();
		console.log("Enviando Respuestas de Quiz.");
		
		FormaT.app("POST", "quiz", 
		{
			"action":"create",
			"page":"response",
			"quiz":$topic,
			"result":JSON.stringify(response)
		}, function(r){
			console.log(r);
			
			$("#modal-view-quiz-current").modal("hide");
			if(r.error === false){
				$.notify(r.message, "success");
				location.replace(FormaT.options.site_url+'index.php?pageActive=f5-result-last');
			}else{
				$.notify(r.message, "error");
				cargarQuizCurrent();
			}
		});
	}
}

/* OK */
function cargarBtnFloat(){
	console.log("Cargando cargarBtnFloat().");
	if(
		FormaT.loadSession().authResponse.signedRequest.permisos.ecards.create && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.create == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos.ecards.edit && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.edit == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos.articles.create && FormaT.loadSession().authResponse.signedRequest.permisos.articles.create == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos.articles.edit && FormaT.loadSession().authResponse.signedRequest.permisos.articles.edit == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos.quiz.create && FormaT.loadSession().authResponse.signedRequest.permisos.quiz.create == true
	){
		$e = '';
		if(FormaT.loadSession().authResponse.signedRequest.permisos.ecards.create && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.create == true){
			$e += crearItemBtnFloat({
				"href":"javascript:createPublishBorrador('ecards');",
				"icon":"glyphicon glyphicon-blackboard",
				"title":"Nueva eCard"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.articles.create && FormaT.loadSession().authResponse.signedRequest.permisos.articles.create == true){
			$e += crearItemBtnFloat({
				"href":"javascript:createPublishBorrador('articles');",
				"icon":"glyphicon glyphicon-book",
				"title":"Nuevo Articulo"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true){
			$e += crearItemBtnFloat({
				"href":"javascript:$('#modal-create-alerts').modal('show');",
				"icon":"fas fa-bell",
				"title":"Nueva Alerta"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.ecards.edit && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.edit == true){
			$e += crearItemBtnFloat({
				"href":FormaT.options.site_url+"index.php?pageActive=saves-publishs",
				"icon":"fas fa-save",
				"title":"Borradores [Publicaciones]"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.quiz.create && FormaT.loadSession().authResponse.signedRequest.permisos.quiz.create == true){
			$e += crearItemBtnFloat({
				"href":"javascript:crearQuizDraft();",
				"icon":"fab fa-quora",
				"title":"Crear Quiz"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.quiz.edit && FormaT.loadSession().authResponse.signedRequest.permisos.quiz.edit == true){
			$e += crearItemBtnFloat({
				"href":FormaT.options.site_url+"index.php?pageActive=saves-quiz",
				"icon":"fas fa-save",
				"title":"Quiz Borradores"
			});
		};
		if(FormaT.loadSession().authResponse.signedRequest.permisos.quiz.delete && FormaT.loadSession().authResponse.signedRequest.permisos.quiz.delete == true){
			$e += crearItemBtnFloat({
				"href":"javascript:eliminarQuizActual();",
				"icon":'fas fa-eraser',
				"title":"Eliminar Quiz Actual"
			});
		};
		$(".menu-btnfloat").append($e);
		$("#inbox").show();
		console.log("Cargado menu-btnfloat append.");
	}
	console.log("Finaliza cargarBtnFloat().");
};

function crearQuizDraft(){
	console.log("Cargando crearQuizDraft()");
	
	FormaT.app("POST", "quiz", 
	{
		"action":"create",
		"page":"quiz",
		"draft":true
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify(r.message, "success");
			window.location.href = FormaT.options.site_url+'index.php?pageActive=create-quiz&type=quiz&draft='+r.id;
		}else{
			$.notify(r.message, "error");
			console.log(r);
		}
	});
}

/* OK */
function createPublishBorrador(type){
	console.log("Cargando createPublishBorrador().");
	FormaT.app("POST", "publicaciones", 
	{
		"action":"create",
		"type":type,
		"draft":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			$.notify(r.message, "success");
			window.location.href = FormaT.options.site_url+'index.php?pageActive=single&type='+type+'&id_ref='+r.id;
		}else{
			$.notify(r.message, "error");
		}
	});
	
	
}

/* OK */
function eliminarQuizActual(){
	console.log("Cargando eliminarQuizActual().");
	FormaT.app("POST", "quiz", 
	{
		"action":"delete",
		"page":"quiz",
		"type":"current"
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			$.notify(r.message, "success");
		}else{
			$.notify(r.message, "error");
		}
	})
};

/* OK */
function crearItemBtnFloat(element){
	console.log("Cargando crearItemBtnFloat().");
	return '<li><a href="'+element.href+'"><i data-toggle="tooltip" title="'+element.title+'" class="'+element.icon+'"></i></a></li>';
}

/* OK */
function cargarHistoryQuizPagina(){
	console.log("Cargando cargarHistoryQuizPagina().");
	
	get = getUrlVars();
	
	FormaT.app("POST", "quiz", 
	{
		"page":"quiz",
		"action":"history"
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if (typeof r.data !== 'undefined' && r.data !== null) {
				$h = '';
				for (i = 0; i < r.data.length; i++) {
					$h += createFilaTablaHistoryQuiz(r.data[i]);
				}
				$(".table-history-body").append($h);
			}	
		}else{
			$.notify(r.message, "error");
		}
	});
};
/* OK */
function cargarDraftsQuizPagina(){
	console.log("Cargando cargarDraftsQuizPagina().");
	
	FormaT.app("POST", "quiz", 
	{
		"action":"view",
		"draft":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if (typeof r.data !== 'undefined' && r.data !== null) {
				$h = '';
				for (i = 0; i < r.data.length; i++) {
					$h += createFilaTablaDraftsQuiz(r.data[i]);
				}
				$(".table-drafts-body").append($h);
			}	
			$.notify(r.message, "success");
		}else{
			$.notify(r.message, "error");
		}
	});
}
/* OK */
function cargarDraftsPublishPagina(){
	console.log("Cargando cargarDraftsPublishPagina().");
	
	FormaT.app("POST", "publicaciones", 
	{
		"action":"view",
		"type":"list",
		"draft":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if (typeof r.data !== 'undefined' && r.data !== null) {
				$h = '';
				for (i = 0; i < r.data.length; i++) {
					$h += createFilaTablaDraftsPublish(r.data[i]);
				}
				$(".table-drafts-body").append($h);
			}	
			$.notify(r.message, "success");
		}else{
			$.notify(r.message, "error");
		}
	});
};

/* OK */
function createFilaTablaDraftsQuiz(element){
	console.log("Comenzando createFilaTablaDraftsQuiz()");
	
	$ht = '<tr id="drafts-quiz-id-'+element.id+'">';
		$ht += '<td>'+element.id+'</td>';
		$ht += '<td>'+element.title+'</td>';
		$ht += '<td>'+element.fecha_creation+'</td>';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=create-quiz&type=quiz&draft='+element.id+'"><i class="fas fa-toggle-on"></i></a></td>';
	$ht += '</tr>';
	return $ht;
}

/* OK */
function createFilaTablaDraftsPublish(element){
	console.log("Comenzando createFilaTablaDraftsPublish()");
	
	$ht = '<tr id="drafts-publish-id-'+element.id+'">';
		$ht += '<td>'+element.id+'</td>';
		$ht += '<td>'+element.type_name+'</td>';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=single&type='+element.type+'&id_ref='+element.id+'">'+element.title+'</a></td>';
		$ht += '<td>'+element.category_name+'</td>';
		$ht += '<td>'+element.author.user+'</td>';
		$ht += '<td>'+element.fcreate+'</td>';
		$ht += '<td>'+element.fchange+'</td>';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=single&type='+element.type+'&id_ref='+element.id+'"><i class="fas fa-toggle-on"></i></a></td>';
	$ht += '</tr>';
	return $ht;
}

/* OK */
function cargarHistoryPublishPagina(){
	console.log("Cargando cargarHistoryPublishPagina().");
	get = getUrlVars();
	FormaT.app("POST", "publicaciones", 
	{
		"action":"history",
		"type":get.type
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if (typeof r.data !== 'undefined' && r.data !== null) {
				$h = '';
				for (i = 0; i < r.data.length; i++) {
					$h += createFilaTablaHistoryPublish(r.data[i]);
				}
				$(".table-history-body").append($h);
				$.notify(r.message, "success");
			}
		}else{
			$.notify(r.message, "error");
		}
	});
};

function cargarHistoryAlertsPagina(){
	console.log("Cargando cargarHistoryAlertsPagina().");
	
	
	FormaT.app("POST", "alerts", 
	{
		"action":"history"
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			if (typeof r.data !== 'undefined' && r.data !== null) {
				$h = '';
				for (i = 0; i < r.data.length; i++) {
					$h += createFilaTablaHistoryAlerts(r.data[i]);
				}
				$(".table-history-body").append($h);
				$.notify(r.message, "success");
			}	
		}else{
			$.notify(r.message, "error");
		}
	});
};

function createFilaTablaHistoryAlerts(element){
	console.log("Comenzando createFilaTablaHistoryAlerts()");
	
	$ht = '<tr id="history-alert-id-'+element.id+'">';
		$ht += '<td>'+element.id+'</td>';
		$ht += '<td>'+element.title+'</td>';
		$ht += '<td>'+element.message+'</td>';
		$ht += '<td>'+element.ticket+'</td>';
		$ht += '<td>'+element.fecha_apertura+'</td>';
		$ht += '<td>'+element.fecha_cierre+'</td>';
		$ht += '<td><a href="javascript:reactivarAlert('+element.id+');"><i class="fas fa-toggle-on"></i></a></td>';
	$ht += '</tr>';
	return $ht;
}

/* OK */
function createFilaTablaHistoryQuiz(element){
	console.log("Comenzando createFilaTablaHistoryQuiz()");
	
	$ht = '<tr id="history-publish-id-'+element.id+'">';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=create-quiz&type=quiz&draft='+element.id+'">'+element.id+'</a></td>';
		$ht += '<td>'+element.title+'</td>';
		$ht += '<td>'+element.fecha_creation+'</td>';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=export-quiz&topic='+element.id+'"><i class="fas fa-toggle-on"></i></a></td>';
	$ht += '</tr>';
	return $ht;
}

function createFilaTablaHistoryPublish(element){
	console.log("Comenzando createFilaTablaHistoryPublish()");
	
	$ht = '<tr id="history-publish-id-'+element.id+'">';
		$ht += '<td>'+element.id+'</td>';
		$ht += '<td><a href="'+FormaT.options.site_url+'index.php?pageActive=single&type='+element.type+'&id_ref='+element.id+'">'+element.title+'</a></td>';
		$ht += '<td>'+element.category_name+'</td>';
		$ht += '<td>'+element.author.user+'</td>';
		$ht += '<td>'+element.fcreate+'</td>';
		$ht += '<td>'+element.fchange+'</td>';
		$ht += '<td><a href="javascript:reactivarPublish('+element.id+');"><i class="fas fa-toggle-on"></i></a></td>';
	$ht += '</tr>';
	return $ht;
}

/* OK */
function reactivarPublish(idPublish){
	FormaT.app("POST", "publicaciones", {
		"action":"change",
		"id":idPublish,
		"active":true
	}, function(r){
		console.log(r);
		if(r.error === false){
			$('#history-publish-id-'+idPublish).hide();
			$.notify(r.message, "success");
		}else{
			$.notify(r.message, "error");
		}
	});
};

function cargarForumQuestionsPagina(){
	console.log("Cargando cargarForumQuestionsPagina().");
	
	param = getUrlVars();
	param.of.replace('#','');
	if(param.pageActive && param.type && param.of){
		console.log("Cargando preguntas de la pagina del foro");
		
		FormaT.app("POST", "comments", 
		{
			"action":"view",
			"type":param.type,
			"category":param.of
		}, function(r){
			console.log(r);			
			if(r.error === false){
				$(".forum-name").html(r.forum.name);
				
				$ha = '';
				
				$ha += '<div class="col-sm-12">';
					$ha += '<div class="testimonial testimonial-default-filled">';
						$ha += '<div class="testimonial-section">';
							$ha += '<input class="form-control input-query" placeholder="Escribe aquí tu duda o pregunta" value="" name="" id="create-comments-forum-input-query-0" />';
						$ha += '</div>';
						$ha += '<div class="testimonial-desc">';
							$ha += '<img src="'+FormaT.loadSession().authResponse.signedRequest.avatar_url+'" alt="" />';
							$ha += '<div class="testimonial-writer">';
								$ha += '<div class="testimonial-writer-name">'+FormaT.loadSession().authResponse.signedRequest.nombre+'</div>';
								$ha += '<div class="testimonial-writer-designation"><button class="btn btn-sm btn-primary" onclick="javascript:createCommentsForum('+param.of+',0);" >Publicar pregunta</button></div>';					
							$ha += '</div>';
						$ha += '</div>';
					$ha += '</div>';
				$ha += '</div>';
				
				if (typeof r.data !== 'undefined' && r.data !== null) {
					for (i = 0; i < r.data.length; i++) {
						$ha += parseQueryForumPage(r.data[i]);
					};
				}
				$(".body-forum").html($ha);
			
				$('[data-toggle="tooltip"]').tooltip(); 
			}else{
				$.notify(r.message, "error");
			}
		});
	};
}

function parseQueryForumPage(element){
	console.log('cargando parseQueryForumPage();');
	if(element.reply == 1){
		$color = 'success';
	}else{
		$color = 'info';
	};
	$h = '<div class="col-sm-12">';
		$h += '<div id="questions-id-'+element.id+'" class="testimonial testimonial-'+$color+'-filled">';
			$h += '<div class="testimonial-section">';
				$h += element.query;
			$h += '</div>';
			$h += '<div class="testimonial-desc">';
				$h += '<img src="'+element.author.avatar_url+'" alt="" />';
				$h += '<div class="testimonial-writer">';
					$h += '<div class="testimonial-writer-name">'+element.author.nombre+'</div>';
					$h += '<div class="testimonial-writer-designation">'+element.f_query+'</div>';
					
					
					if(element.reply == 0){
						$h += '<a href="#" class="testimonial-writer-company response-query-id-'+element.id+'">Esperando Respuesta...</a>';						
					}else{
						$h += '<a href="#" class="testimonial-writer-company response-query-id-'+element.id+'">'+element.comment+'</a>';
					}
					
					
					if(FormaT.loadSession().authResponse.signedRequest.permisos.comments.response == true){
						$href = "javascript:responseReplyModal("+element.id+",'"+element.f_query+"','Tema')";
						if(element.reply == 0){
							$h += '<br><a href="'+$href+'" class="testimonial-writer-company btn btn-xs btn-warning" title="Responder" data-toggle="tooltip"><i class="fas fa-share-square"></i></a>';				
						}else{
							$h += '<br><a href="'+$href+'" class="testimonial-writer-company btn btn-xs btn-warning" title="Editar Respuesta" data-toggle="tooltip"><i class="fas fa-pen-square"></i></a>';
						}
					}
					
					if(FormaT.loadSession().authResponse.signedRequest.permisos.comments.delete == true){
						$href = "javascript:deleteCommentsForum("+element.id+",'"+element.query+"','Tema')";
						$h += '<a href="'+$href+'" class="testimonial-writer-company btn btn-xs btn-danger" title="Responder" data-toggle="tooltip"><i class="fas fa-trash"></i></a>';
					}	
					
				$h += '</div>';
			$h += '</div>';
		$h += '</div>';
	$h += '</div>';
	
	$h += '<div class="col-sm-1">';
	$h += '</div>';
	$h += '<div class="col-sm-11">';
		if (typeof element.tree !== 'undefined' && element.tree !== null) {
		
			$h += '<div class="col-sm-12">';
				$h += '<div class="testimonial testimonial-default-filled">';
					$h += '<div class="testimonial-section">';
						$h += '<input class="form-control input-query" placeholder="Escribe aquí tu duda o pregunta" value="" name="" id="create-comments-forum-input-query-'+element.id+'" />';
					$h += '</div>';
					$h += '<div class="testimonial-desc">';
						$h += '<img src="'+FormaT.loadSession().authResponse.signedRequest.avatar_url+'" alt="" />';
						$h += '<div class="testimonial-writer">';
							$h += '<div class="testimonial-writer-name">'+FormaT.loadSession().authResponse.signedRequest.nombre+'</div>';
							$h += '<div class="testimonial-writer-designation"><button class="btn btn-sm btn-primary" onclick="javascript:createCommentsForum('+element.raiz+','+element.id+');" >Publicar pregunta</button></div>';					
						$h += '</div>';
					$h += '</div>';
				$h += '</div>';
			$h += '</div>';
			
			for (a = 0; a < element.tree.length; a++) {
				$h += parseQueryForumPage(element.tree[a]);
			};
			
		}
		
	$h += '</div>';
	return $h;
	
};

function cargarNavBarTop(){
	console.log("Cargando Menu Top -> cargarNavBarTop().");
	console.log(FormaT.loadSession().authResponse.signedRequest.permisos);
	
	/* CARGAR MUNDIAL */
	if(FormaT.loadSession().authResponse.signedRequest.permisos.mundial !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.mundial.view == true){
		console.log("Mostrando Btn Mundial en la NavBarTop");
		$(".item-mundial-navbartop").show();
		
		console.log("Configurando Btn de Mundial en la NavBarTop");
		$(".format-mundial-link").attr('href', FormaT.loadSession().authResponse.signedRequest.permisos.mundial.link);
		$(".format-mundial-link").attr('target', "_blank");
	};
	
	/* CARGAR FORO */
	if(FormaT.loadSession().authResponse.signedRequest.permisos.forum !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.forum.response == true){
		console.log("Mostrando Btn Foro en la NavBarTop");
		$(".item-questions-response-navbartop").show();
		
		//FormaT.options.api_url_large/comments.php?accesstoken= { ACCESSTOKEN } &action=pending&type=forum
		console.log("Cargando Preguntas Pendientes del Foro");
		FormaT.app("POST", "comments", 
		{
			"action":"view",
			"pending":true,
			"type":"forum"
		}, function(r){
			console.log(r);			
			if(r.error === false){
				console.log("Cargando Total de Preguntas Pdtes en la NavBarTop");
				console.log(r.data.length);
				
				$(".total-questions-response-navbartop").html(r.data.length);
				
				
				for (i = 0; i < r.data.length; i++) { crearItemQuestionsPendingNavBarTop(r.data[i]); };
				
				$(".spin-questions-response-navbar").hide();
				
				if(r.data.length==0){
					$("#menu-questions-response-top").append('<li><a>No tienes preguntas pdtes.</a></li>');
				}
			}else{
				$("#menu-questions-response-top").append('<li><a>No tienes preguntas.</a></li>');
			}
		})
	};
	
	if(FormaT.loadSession().authResponse.signedRequest.permisos.chat !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.chat.view == true){
		console.log("Mostrando Btn Messeger en la NavBarTop");
		$(".item-messenger-pending-navbartop").show();
		
		//FormaT.options.api_url_large/messenger.php?accesstoken= { ACCESSTOKEN } &action=pending
		console.log("Cargando Chats Pendientes del Messenger");
		FormaT.app("POST", "messenger", 
		{
			"action":"pending"
		}, function(r){
			console.log(r);			
			if(r.error === false){
				console.log("Cargando Total de Chats Pdtes en la NavBarTop");
				console.log(r.data.length);
				
				$(".total-messenger-pending-navbartop").html(r.data.length);
				
				for (i = 0; i < r.data.length; i++) { crearItemChatNavBarTop(r.data[i]); };
				
				if(r.data.length==0){
					$("#menu-chat-top").append('<li><a>No tienes conversaciones pdtes.</a></li>');
				}
			}else{
				
			}
			$(".spin-chat-messenger-navbar").hide();
		})
	};
		
	if(FormaT.loadSession().authResponse.signedRequest.permisos.indicators !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.indicators.view == true){
		console.log("Mostrando Btn Indicadores en la NavBarTop");
		$(".item-indicators-navbartop").show();
		
		//FormaT.options.api_url_large/kpis.php?accesstoken= { ACCESSTOKEN } &action=my_indicators
		console.log("Cargando Indicadores Activos");
		FormaT.app("POST", "kpis", 
		{
			"action":"view",
			"my":true
		}, function(r){
			console.log(r);			
			if(r.error === false){
				console.log("Cargando Total de Indicadores Activos en la NavBarTop");
				console.log(r.data.length);
				
				$(".total-indicators-navbartop").html(r.data.length);
				
				if(r.data.length > 0){
					for (i = 0; i < r.data.length; i++) { crearItemKpisNavBarTop(r.data[i]); };
				}
				else{
					$("#menu-kpis-top").append('<li><a>No tienes KPIs registrados.</a></li>');
				}
				
				
				
				
			}else{
				$.notify(r.message, "error");
			}
			$(".spin-kpis-navbar").hide();
		});		
	};
	
	if(FormaT.loadSession().authResponse.signedRequest.permisos.stopwatch !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.stopwatch.view == true){
		console.log("Mostrando Btn Cronometro en la NavBarTop");
		$(".item-stopwatch-navbartop").show();
		
	};
	
	if(FormaT.loadSession().authResponse.signedRequest.permisos.alerts !== undefined && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.view == true){
		console.log("Mostrando Btn Alertas en la NavBarTop");
		$(".item-alerts-navbartop").show();
		
		//FormaT.options.api_url_large/alerts.php?accesstoken= { ACCESSTOKEN } &action=actives
		console.log("Cargando Alertas Activas");
		FormaT.app("POST", "alerts", 
		{
			"action":"view"
		}, function(r){
			console.log(r);			
			if(r.error === false){
				console.log("Cargando Total de Alertas Activas en la NavBarTop");
				console.log(r.data.length);
				
				$(".total-alerts-navbartop").html(r.data.length);
			
				if(
					FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true
					|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.edit == true
					|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.delete == true
				){
					console.log("Cargando Alertas en Sidebar.");
					
					$html_a = '<li>';
						$html_a += '<a href="#alertsSubmenu" data-toggle="collapse" aria-expanded="false">';
							$html_a += '<i class="far fa-bell"></i>';
							$html_a += "Alertas";
						$html_a += '</a>';
						$html_a += '<ul class="collapse list-unstyled" id="alertsSubmenu">';
				}
				
				for (i = 0; i < r.data.length; i++) {
					crearItemAlertsNavBarTop(r.data[i]);
					
					if(
						FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true
						|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.edit == true
						|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.delete == true
					){
						console.log("Cargando Alertas en Sidebar.");
					
						$html_a += crearItemAlertsSideBar(r.data[i],FormaT.loadSession().authResponse.signedRequest.permisos.alerts);
						
					}
				};
				
				if(
					FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true
					|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.edit == true
					|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.delete == true
				){
						if(r.data[i],FormaT.loadSession().authResponse.signedRequest.permisos.alerts !== undefined && r.data[i],FormaT.loadSession().authResponse.signedRequest.permisos.alerts.create == true){
							$html_a += '<li><a href="javascript:$('+"'#modal-create-alerts'"+').modal('+"'show'"+');"><i class="fas fa-plus"></i> Crear</a></li>';
						}
						$html_a += '</ul>';
					$html_a += '</li>';
					
					$("#menu-sidebar").append($html_a);
				}
				
				$(".spin-alerts-navbar").hide();
			}else{
			}
		});
	};
};

function crearItemAlertsSideBar(element,permisos){
	$t = '';
	if($("#alert-sidebar-id-"+element.id).length > 0){
		console.log("Elemento [alert-navbar-id-"+element.id+" ] Ya existe, No se va a crear...");
	}else{
		$t += '<li id="alert-sidebar-id-'+element.id+'">';

			$t += '<a>';
				if(permisos !== undefined && permisos.delete == true){
					$t += '<span onclick="javascript:deleteAlert('+element.id+');"><i class="fas fa-ban"></i></span> ';
				}
				if(permisos !== undefined && permisos.edit == true){
					$t += '<span onclick="javascript:openEditAlert('+element.id+');"><i class="fas fa-pencil-alt"></i></span>';
				}
				
				$t += '<span onclick="javascript:cargarAlertIndv('+element.id+');">'+element.title+' ['+element.ticket+']</span>';
			$t += '</a>';
			
		$t += '</li>';
	}
	return $t;
	//if(permisos !== undefined && permisos.create == true){}
	
};

// Delete alerts
function deleteAlert(idAlert){
	bootbox.confirm({
		message: "Confirma que desea eliminar esta alerta?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "alerts", {
					"action":"delete",
					"id":idAlert
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminada con exito!...", "success");
						location.reload();
					}else{
						$.notify("Error eliminando la alerta...", "error");
					}
				});
				
			}
		}
	});
};

// Crear alerts
function createAlert(){
	title = $('#modal-create-alerts input[name="title"]').val();
	message = $('#modal-create-alerts textarea[name="message"]').val();
	ticket = $('#modal-create-alerts input[name="ticket"]').val();
	
	FormaT.app("POST", "alerts", {
		"action":"create",
		"title":title,
		"message":message,
		"ticket":ticket
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify("Alerta creada con exito!...", "success");
			location.reload();
		}else{
			$.notify("Error creando alerta...", "error");
		}
	});
	
};

// Crear alerts
function openEditAlert(idAlert){
	$("#modal-edit-alerts").modal("show");
	
	FormaT.app("POST", "alerts", {
		"action":"view",
		"id":idAlert
	}, function(r){
		console.log(r);
		if(r.error === false){
			
			id = $('#modal-edit-alerts input[name="id"]').val(r.data.id);
			title = $('#modal-edit-alerts input[name="title"]').val(r.data.title);
			message = $('#modal-edit-alerts textarea[name="message"]').val(r.data.message);
			ticket = $('#modal-edit-alerts input[name="ticket"]').val(r.data.ticket);
			
		}else{
			$.notify("Error cargando la alerta...", "error");
		}
	});
	
};

function editAlert(){
	id = $('#modal-edit-alerts input[name="id"]').val();
	title = $('#modal-edit-alerts input[name="title"]').val();
	message = $('#modal-edit-alerts textarea[name="message"]').val();
	ticket = $('#modal-edit-alerts input[name="ticket"]').val();
	
	
	FormaT.app("POST", "alerts", {
		"action":"change",
		"id":id,
		"title":title,
		"message":message,
		"ticket":ticket
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify("Guardado con exito...", "success");
			location.reload();
		}else{
			$.notify("Error cargando la alerta...", "error");
		}
	});
};

function reactivarAlert(idAlert){
	FormaT.app("POST", "alerts", {
		"action":"change",
		"id":idAlert,
		"active":true
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify("Re-Activada con exito...", "success");
			$('#history-alert-id-'+idAlert).hide();
		}else{
			$.notify("Error activando la alerta...", "error");
		}
	});
};

function cargarCalendary(category,filtro){
	console.log('Cargado cargarCalendary();');
	
	
	FormaT.app("POST", "calendary", {
		"action":"view",
		"style":"gantt",
		"order":filtro,
		"category":category
	}, function(r){
		console.log(r);
		if(r.error === false){			
			$(".gantt").gantt({
				//source: 'api/v1.0/calendary.php?accesstoken='+FormaT.AccessToken()+'&action=view&style=gantt&category=1&order=formador',
				source: r.data,
				navigate: "scroll",
				scale: "days",
				//scale: "hours",
				maxScale: "weeks",
				minScale: "hours",
				itemsPerPage: 50,
				useCookie: false,
				onItemClick: function(data) {
					verCalendario(data);
					return;
				},
				onAddClick: function(dt, rowId) {
					if(FormaT.loadSession().authResponse.signedRequest.permisos.calendary.edit == true){
						var date = new Date(dt);
						dia = date.getDate();
						mes = (date.getMonth()+1);
						anho = date.getFullYear();
						
						hora_inicio = addZero(date.getHours());
						minutos_inicio = addZero(date.getMinutes());
						segundos_inicio = addZero(date.getSeconds());
						
						var datos = {};
						datos.category = category;
						datos.fecha = anho+"-"+mes+"-"+dia;
						//datos.hora_inicio = hora_inicio+":"+minutos_inicio+":"+segundos_inicio;
						datos.hora_inicio = hora_inicio+":"+minutos_inicio;
						
						
						$('input[name="fecha"]').val(datos.fecha);
						$('input[name="hora_inicio"]').val(datos.hora_inicio);
						$("#bd-modal-create-capas-calendary").modal("show");
					}
					return;
				},
				onRender: function() {
					if (window.console && typeof console.log === "function") {
						$.notify("Calendario cargado",'info');
					}
				},
				months: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
				dow: ["D","L","M","W","J","V","S"],
				waitText: "Cargando, Espere...",
			});
			
		}else{
			$.notify(r.message, "error");
		}
	});
	
	
};

function createCalendaryModal(){
	var infoLocalSession = FormaT.loadSession();
	
	datos = {};
	datos.action = "create";
	datos.fecha = $('#bd-modal-create-capas-calendary input[name="fecha"]').val();
	datos.hora_inicio = $('#bd-modal-create-capas-calendary input[name="hora_inicio"]').val();
	datos.hora_fin = $('#bd-modal-create-capas-calendary input[name="hora_fin"]').val();
	datos.lugar = $('#bd-modal-create-capas-calendary input[name="lugar"]').val();
	datos.encargado = $('#bd-modal-create-capas-calendary input[name="encargado"]').val();
	datos.category = $('#bd-modal-create-capas-calendary input[name="category"]').val();
	datos.piloto = infoLocalSession.authResponse.signedRequest.piloto;
	
	if(
		datos.fecha !== ''
		&& datos.hora_inicio !== ''
		&& datos.hora_fin !== ''
		&& datos.lugar !== ''
		&& datos.encargado !== ''
		&& datos.category !== ''
	){
		console.log(datos);
		FormaT.app("POST", "calendary", datos, function(r){
			$('#bd-modal-create-capas-calendary').modal('hide');
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				location.reload();
			}else{
				$.notify(r.message, "error");
				console.log(datos);
				console.log(r);
			}
		});
	}else{
		alert("Completa Todos los campos",'error');
	}
};

function editCalendaryModal(){
	datos = {};
	datos.action = "change";
	datos.id = $('#bd-modal-edit-capas-calendary input[name="id"]').val();
	datos.fecha = $('#bd-modal-edit-capas-calendary input[name="fecha"]').val();
	datos.hora_inicio = $('#bd-modal-edit-capas-calendary input[name="hora_inicio"]').val();
	datos.hora_fin = $('#bd-modal-edit-capas-calendary input[name="hora_fin"]').val();
	datos.lugar = $('#bd-modal-edit-capas-calendary input[name="lugar"]').val();
	datos.encargado = $('#bd-modal-edit-capas-calendary input[name="encargado"]').val();
	datos.category = $('#bd-modal-edit-capas-calendary input[name="category"]').val();
	
	if(
		datos.id !== ''
		&& datos.fecha !== ''
		&& datos.hora_inicio !== ''
		&& datos.hora_fin !== ''
		&& datos.lugar !== ''
		&& datos.encargado !== ''
		&& datos.category !== ''
	){
		console.log(datos);
		FormaT.app("POST", "calendary", datos, function(r){
			$('#bd-modal-edit-capas-calendary').modal('hide');
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				location.reload();
			}else{
				$.notify(r.message, "error");
			}
		});
	}else{
		alert("Completa Todos los campos",'error');
	}
};

// Cargar el dialogo para editar capacitacion
function loadEditCapa(idCapa){
	FormaT.app("POST", "calendary", {
		"action":"view",
		"id":idCapa
	}, function(r){
		if(r.error === false){
			target = r.data;
			for (var k in target){		
				if (target.hasOwnProperty(k)) {
					$('#bd-modal-edit-capas-calendary input[name="'+k+'"]').val(target[k])
				}
			}
			console.log("cargado");
		}else{
			$.notify("Error...", "error");
		}
	});
	
	
	$('#bd-modal-edit-capas-calendary input[name="action_forms"]').val()
	
	$("#bd-modal-edit-capas-calendary").modal("show");
	console.log(idCapa);
};

// Ver un calendario
function verCalendario(target){
	$(".btn-edit-capa").attr("href",'javascript:loadEditCapa('+target['id']+');');
	$(".btn-delete-capa").attr("href",'javascript:deleteCalendary('+target['id']+');');
	for (var k in target){		
		if (target.hasOwnProperty(k)) {
			$("#modal-view-capas-calendary-input-"+k).text(target[k]);
			
		}
	}
	
	if(FormaT.loadSession().authResponse.signedRequest.permisos.calendary.edit == true){ $(".btn-edit-capa").show(); }
	else{ $(".btn-edit-capa").hide(); };
	if(FormaT.loadSession().authResponse.signedRequest.permisos.calendary.delete == true){ $(".btn-delete-capa").show(); }
	else{ $(".btn-delete-capa").hide(); };
	
	$("#bd-modal-view-capas-calendary").modal("show");
};

// Eliminar un calendario
function deleteCalendary(id){
	bootbox.confirm({
	message: "Confirma que desea eliminar el calendario?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "calendary", {
					"action":"delete",
					"id":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminado con exito!...", "success");
						location.reload();
					}else{
						$.notify("Error eliminando el calendario...", "error");
					}
				});
				
			}
		}
	});
	
};

// Convertir fechas de UNIX
function convertFechaUnix(unix_timestamp){
	var date = new Date(unix_timestamp);
	
	var options = {  
		weekday: "long", year: "numeric", month: "short",  
		day: "numeric", hour: "2-digit", minute: "2-digit"  
	};  
	date = date.toLocaleTimeString("es-CO", options);
	
	return date;
};

function crearItemQuestionsPendingNavBarTop(element){
	if($("#question-response-navbar-id-"+element.id).length > 0){
		console.log("Elemento [question-response-navbar-id-"+element.id+" ] Ya existe, No se va a crear...");
	}else{
		FormaT.AccessToken()+"&id="+element.author.avatar;
		
		$("#menu-questions-response-top").append(crearItemNavBarTop({
			"id":"question-response-navbar-id-"+element.id,
			"picture":"api/v1.0/pictures.php?accesstoken="+FormaT.AccessToken()+"&id="+element.author.avatar,
			"href":"javascript:responseReplyModal("+element.id+",'"+element.query+"','"+element.raiz_name+"')",
			"description": "<p><b>"+element.author.nombre+"</b></p>"+
				"<p>"+element.query+"</p>"+
				"<p>"+element.f_query+"</p>"
		})); /** append -> AGREGAR DESDE EL FINAL // prepend -> AGREGAR DESDE EL INICIO **/
	}
};

function crearItemChatNavBarTop(element){
	if($("#chat-navbar-id-"+element.id).length > 0){
		console.log("Elemento [chat-navbar-id-"+element.id+" ] Ya existe, No se va a crear...");
	}else{
		$listPeople = element.related_people[0].nombre;
		for (a = 1; a < element.related_people.length; a++) { $listPeople += ', '+(element.related_people[a].nombre); };
	
		$("#menu-chat-top").append(crearItemNavBarTop({
			"id":"chat-navbar-id-"+element.id,
			"picture":"images/avatars/team001.png",
			"href":FormaT.options.site_url+"index.php?pageActive=messenger&read="+element.id+"&view=true",
			"description":
			"<p><b>Integrantes</b>: "+$listPeople+"</p>"+
			"<p><b>Ultimo mensaje</b>: "+element.message.message+"</p>"+
			"<p>"+element.last_activity+"</p>"
		})); /** append -> AGREGAR DESDE EL FINAL // prepend -> AGREGAR DESDE EL INICIO **/
	}
};

function crearItemKpisNavBarTop(element){	
	if($("#kpi-navbar-id-"+element.name).length > 0){
		console.log("Elemento [kpi-navbar-id-"+element.name+" ] Ya existe, No se va a crear...");
	}else{
		
		$h = '<li id="kpi-navbar-id-'+element.name+'">';
			$h += '<a href="#">';
				$h += '<div class="task-info">';
					$h += '<span class="task-desc"><b>'+element.name+'</b> </span>';
					$h += '<span class="task-desc">Tu Meta: '+element.meta+'</span>';
					$h += '<div class="clearfix"></div>';
				$h += '</div>';
				$h += '<span class="percentage">'+element.actual+' Actualmente</span>';
				$h += '<div class="clearfix"></div>';
				$h += '<div class="progress progress-striped active">';
					$h += '<div class="bar '+element.color_label+'" style="width:'+element.porcentage+'%;" title="titulo" data-toggle="tooltip"></div>';
				$h += '</div>';
			$h += '</a>';
		$h += '</li>';
		
		$("#menu-kpis-top").append($h); /** append -> AGREGAR DESDE EL FINAL // prepend -> AGREGAR DESDE EL INICIO **/
	}
};

function crearItemNavBarTop(datos){
	console.log("Creando Item para NavBarTop");
	$h = '<li id="'+datos.id+'">';
		$h += '<a href="'+datos.href+'">';
			$h += '<div class="user_img"><img src="'+datos.picture+'" alt=""></div>';
			$h += '<div class="notification_desc">';
				$h += ''+datos.description+'';
			$h += '</div>';
			$h += '<div class="clearfix"></div>';
		$h += '</a>';
	$h += '</li>';
	
	return $h;
};

function crearItemAlertsNavBarTop(element){	
	if($("#alert-navbar-id-"+element.id).length > 0){
		console.log("Elemento [alert-navbar-id-"+element.id+" ] Ya existe, No se va a crear...");
	}else{
		$("#menu-alerts-top").append(crearItemNavBarTop({
			"id":"alert-navbar-id-"+element.id,
			"picture":"images/icons/alarm.png",
			"href":"javascript:cargarAlertIndv("+element.id+")",
			"description":"<p>"+element.title+"</p><p><span>Fecha: "+element.fecha_apertura+"</span></p><p><span>Ticket: "+element.ticket+"</span></p>"
		})); /** append -> AGREGAR DESDE EL FINAL // prepend -> AGREGAR DESDE EL INICIO **/
	}
};

function cargarSideBar(){
	$sidebar = $("#menu-sidebar");
	if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.view == true){
		console.log("Cargando Menu de eCards.");
		
		crearMenuSideBarDefault({
			"type":"ecards",
			"icon":"glyphicon glyphicon-blackboard",
			"name":"Info Banner"
		});
	}
	if(FormaT.loadSession().authResponse.signedRequest.permisos 
		&& FormaT.loadSession().authResponse.signedRequest.permisos.articles.view == true){
		console.log("Cargando Menu de Articulos.");
		
		crearMenuSideBarDefault({
			"type":"articles",
			"icon":"glyphicon glyphicon-book",
			"name":"Top Semanal"
		});
	}
	if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.forum.view == true){
		console.log("Cargando Menu de Foro.");
		
		crearMenuSideBarDefault({
			"type":"forum",
			"icon":"fas fa-question-circle",
			"name":"Foro"
		});
	}
	if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.calendary.view == true){
		console.log("Cargando Menu de calendary.");
		
		crearMenuSideBarDefault({
			"type":"calendary",
			"icon":"fas fa-calendar",
			"name":"Capacitaciones"
		});
	}
	if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.devices.view == true){
		console.log("Cargando Dispositivos de Simuladores.");
		cargarDevicesSideBar();
	}
	
	if(
		FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.history == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.articles.history == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.history == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.quiz.history == true
	){
		console.log("Cargando Historicos de contenidos.");
		
		setTimeout(function(){
			cargarMenuHistorySideBar();
		}, 1500);
	}
	
	if(
		FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.import.people == true
		|| FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.import.indicators == true
	){
		console.log("Cargando Importacion de contenidos.");
		
		setTimeout(function(){
			$html = '<li>';
				$html += '<a href="#importSubmenu" data-toggle="collapse" aria-expanded="false">';
					$html += '<i class="fas fa-upload"></i>';
					$html += "Importar	";
				$html += '</a>';
				$html += '<ul class="collapse list-unstyled" id="importSubmenu">';
					if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.import.people == true){
						$html += itemMenuSideBar({
							"name":"Personal",
							"icon":"fas fa-users",
							"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=import-people'"
						});
					}
					
					if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.import.indicators == true){
						$html += itemMenuSideBar({
							"name":"Indicadores",
							"icon":"fas fa-tasks",
							"href":"javascript: window.location.href = '#'"
						});
					}
				$html += '</ul>';
			$html += '</li>';
			$("#menu-sidebar").append($html);
		}, 1600);
	}
	
	if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.export.quiz == true){
		console.log("Cargando Exportacion de contenidos.");
		
		setTimeout(function(){
			$html = '<li>';
				$html += '<a href="#exportSubmenu" data-toggle="collapse" aria-expanded="false">';
					$html += '<i class="fas fa-upload"></i>';
					$html += "Exportar";
				$html += '</a>';
				$html += '<ul class="collapse list-unstyled" id="exportSubmenu">';
					if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.export.quiz == true){
						$html += itemMenuSideBar({
							"name":"Último Quiz",
							"icon":"fas fa-graduation-cap",
							"href":"javascript:stadisticsCurrentQuiz();"
						});
					}
				$html += '</ul>';
			$html += '</li>';
			$("#menu-sidebar").append($html);
		}, 1700);
	}
};

function stadisticsCurrentQuiz(){
	console.log("Cargando stadisticsCurrentQuiz().");
	
	FormaT.app("POST", "quiz", 
	{
		"action":"view",
		"page":"quiz",
		"current":true
	}, function(r){
		console.log(r);
		
		if(r.error === false){
			$.notify(r.message, "success");
			if(r.quiz.querys.length > 0){
				window.location.href = FormaT.options.site_url+'index.php?pageActive=export-quiz&topic='+r.quiz.id;
			}else{
				$.notify("No hay quiz activo.", "error");
			}
		}else{
			$.notify(r.message, "error");
		}
	});
};

function cargarMenuHistorySideBar(){
	$html = '';
	$html += '<li>';
		$html += '<a href="#historiesSubmenu" data-toggle="collapse" aria-expanded="false">';
			$html += '<i class="fas fa-laptop"></i>';
			$html += "Historicos";
		$html += '</a>';
		$html += '<ul class="collapse list-unstyled" id="historiesSubmenu">';
			
			if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.alerts.history == true){
				$html += itemMenuSideBar({
					"name":"Alertas",
					"icon":"far fa-bell",
					"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=history-alerts'"
				});
			}
			if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.articles.history == true){
				$html += itemMenuSideBar({
					"name":"Top Semanal",
					"icon":"glyphicon glyphicon-book",
					"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=history-publish&type=articles'"
				});
			}
			if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.history == true){
				$html += itemMenuSideBar({
					"name":"Info Banner",
					"icon":"glyphicon glyphicon-blackboard",
					"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=history-publish&type=ecards'"
				});
			}
			if(FormaT.loadSession().authResponse.signedRequest.permisos && FormaT.loadSession().authResponse.signedRequest.permisos.ecards.history == true){
				$html += itemMenuSideBar({
					"name":"Evaluaciones",
					"icon":"fas fa-graduation-cap",
					"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=history-quiz'"
				});
			};
		$html += '</ul>';
	$html += '</li>';
	$("#menu-sidebar").append($html);
	
};

function cargarManualsDevicesPagina(){
	console.log("Comenzando cargarManualsDevicesPagina()");
	
	param = getUrlVars();
	if(param.ref_id && param.topic && param.device_id && param.pageActive == 'view-vstep'){
		console.log("Cargando manual de dispositivo");
		
		FormaT.app("POST", "devices", 
		{
			"vsteps_id":param.ref_id,
			"device_id":param.device_id,
			"action":"view"
		}, function(r){
			console.log(r);
			
			if(r.error === false){
				$(".manufacturer-link").attr('href',FormaT.options.site_url+'index.php?pageActive=explore-category&type=devices&device_type='+r.data.device.type+'&device_manufacturer='+r.data.manufacturer.id);
				$(".device-link").attr('href',FormaT.options.site_url+'index.php?pageActive=explore-category&type=devices&device_type='+r.data.device.type+'&device_id='+r.data.device.id);
				
				$(".device-name").html(r.data.device.name);
				$(".manufacturer-name").html(r.data.manufacturer.name);
				$(".vsteps-title").html(r.data.manual.name);
				
				$ht = '';
				if(r.data.manual.instructions.length>0){
					for (i = 0; i < r.data.manual.instructions.length; i++) {
						$ht += crearItemVStepsPage(r.data.manual.instructions[i]);
					}
				};
				
				$(".instruction").html($ht);
				
				HighlightOptionsTemp = {
					"cdn" : FormaT.options.api_url_large+'/pictures.php?accesstoken=',
					"api_url" : FormaT.options.api_url_large,
					"maxWidth" : r.data.device.size.maxWidth,
					"maxHeight" : r.data.device.size.maxHeight,
					"masterImage" : {
						"name" : r.data.device.image_icon,
						"width" : r.data.device.size.width,
						"height" : r.data.device.size.height
					},
				};
				
				if(r.data.device.size.screenPositionLeft !== undefined){ HighlightOptionsTemp.masterImage.screenPositionLeft = r.data.device.size.screenPositionLeft; };
				if(r.data.device.size.screenPositionTop !== undefined){ HighlightOptionsTemp.masterImage.screenPositionTop = r.data.device.size.screenPositionTop; };
				if(r.data.device.size.screenHeight !== undefined){ HighlightOptionsTemp.masterImage.screenHeight = r.data.device.size.screenHeight; };
				if(r.data.device.size.screenWidth !== undefined){ HighlightOptionsTemp.masterImage.screenWidth = r.data.device.size.screenWidth; };
				
				FormaTManuals = {
					CdnUrl: 'https://wmstatic.global.ssl.demedallo.com/ml/fundators-feliphegomez',
					ApiUrl: 'https://demedallo.com/fundators-feliphegomez',
					Resources: { title_prefix: 'FormaT', title_suffix: ' - FormaT' },
					HighlightOptions: HighlightOptionsTemp,
				};
				
				setTimeout(function(){
					cargarVirtualStepsFelipheGomez();
				}, 1000);
			}else{
				$.notify("Error cargando el manual del dispositivo...", "error");
			}
		});
	}
	
};

function crearItemVStepsPage(element){
	console.log("Comenzando crearItemVStepsPage()");
	
	$h = '<div class="blocks">';
		$h += '<h3>'+element.title+'</h3>';
			if(element.steps.length>0){
				for (b = 0; b < element.steps.length; b++) {
					
					$h += '<div class="block" id="'+element.steps[b].id+'">';
						if(element.steps[b].points.length>0){
							for (c = 0; c < element.steps[b].points.length; c++) {
								$h += '<span ';
									$h += 'class="'+element.steps[b].points[c].class+'"  ';
									$h += 'data-display="'+element.steps[b].points[c].display+'" ';
									$h += 'data-display-width="'+element.steps[b].points[c].displayWidth+'" ';
									$h += 'data-display-height="'+element.steps[b].points[c].displayHeight+'" ';
									$h += 'data-pointer-speed="'+element.steps[b].points[c].pointerSpeed+'" ';
									$h += 'data-pointer-frames="'+element.steps[b].points[c].pointerFrames+'" ';
									$h += 'data-pointer-width="'+element.steps[b].points[c].pointerWidth+'" ';
									$h += 'data-pointer-height="'+element.steps[b].points[c].pointerHeight+'" ';
									$h += 'data-top="'+element.steps[b].points[c].top+'" ';
									$h += 'data-left="'+element.steps[b].points[c].left+'" ';
									$h += 'data-orientation="'+element.steps[b].points[c].orientation+'" ';
									$h += 'data-pointer-type="'+element.steps[b].points[c].pointerType+'" ';
									$h += 'data-pointer="'+element.steps[b].points[c].pointer+'" ';
									$h += 'data-pointer-top="'+element.steps[b].points[c].pointerTop+'" ';
									$h += 'data-pointer-left="'+element.steps[b].points[c].pointerLeft+'"';
								$h += '>'+element.steps[b].text+'</span>';
							}
						}
					$h += '</div>';
				}
			}
	$h += '</div>';
	return $h;
}

function cargarTopicsDevicesPagina(){
	console.log("Comenzando cargarTopicsDevicesPagina()");
	
	param = getUrlVars();
	if(param.device_id && param.device_type && param.type && param.type=='devices'){
		console.log("Cargando pagina de temas dispositivo");
		
		FormaT.app("POST", "devices", 
		{
			"id":param.device_id,
			"action":"view"
		}, function(r){
			console.log(r);
			if(r.error === false){
				$(".device-name").html(r.data.name);
				$(".device-manufacturer-name").html(r.data.manufacturer_name);
				$(".iamgurdeeposahan").attr('src',r.data.image_icon_url);
				$(".iamgurdeeposahan").attr('title',r.data.name);
				$(".iamgurdeeposahan").attr('alt',r.data.name);
				
				r.data.name
				
				if(r.data.topics.length>0){
					for (i = 0; i < r.data.topics.length; i++) {
						$h = '<div class="panel panel-primary">';
							$h += '<div class="panel-heading" role="tab" id="headingThree-'+r.data.topics[i].id+'">';
								$h += '<h4 class="panel-title">';
									$h += '<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">';
										$h += r.data.topics[i].name;
									$h += '</a>';
								$h += '</h4>';
							$h += '</div>';
							$h += '<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree-'+r.data.topics[i].id+'">';
								$h += '<ul class="list-group">';
									if(r.data.topics[i].items.length>0){
										for (a = 0; a < r.data.topics[i].items.length; a++) {
$h += '<li class="list-group-item"><a href="'+FormaT.options.site_url+'index.php?pageActive=view-vstep&ref_id='+r.data.topics[i].items[a].id+'&device_id='+r.data.id+'&topic='+r.data.topics[i].id+'">'+r.data.topics[i].items[a].name+'</a></li>';
										}
									}
								$h += '</ul>';
							$h += '</div>';
						$h += '</div>';
						$("#accordion").append($h);
					}
				}
			}else{
				
			}
		});
	};		
}

function cargarDevicesSideBar(){
	console.log("Comenzando cargarDevicesSideBar()");

	FormaT.app("POST", "devices", 
	{
		"action":"view",
		"list":true,
		"type":"sidebar"
	}, function(r){
		console.log(r);
		if(r.error === false){
		$html = '';
		$html += '<li>';
			$html += '<a href="#simulatorsSubmenu" data-toggle="collapse" aria-expanded="false">';
				$html += '<i class="fas fa-laptop"></i>';
				$html += "Simuladores";
			$html += '</a>';
			$html += '<ul class="collapse list-unstyled" id="simulatorsSubmenu">';
		
				for (i = 0; i < r.data.length; i++) {
					$html += itemMenuSideBarDevices(r.data[i]);
				}
			$html += '</ul>';
		$html += '</li>';
		$("#menu-sidebar").append($html);
		}else{
			console.log("Error generando sidebar de dispositivos.");
		}
	});
}

function itemMenuSideBarDevices(element){
	console.log("Cargado itemMenuSideBarDevices()");	
	$html = '<li>';
		$html += '<a href="#pageSubmenu-'+element.id+'" data-toggle="collapse" aria-expanded="false">';
			$html += '<i class="'+element.icon+'"></i>';
			$html += element.name;
		$html += '</a>';
		$html += '<ul class="collapse list-unstyled" id="pageSubmenu-'+element.id+'">';			
			
			if(element.tree !== undefined){
				console.log("Tree En Devices Exite, agregando dispositivos...");
				
				for (a = 0; a < element.tree.length; a++) {
					$html += itemMenuSideBar({
						"name":element.tree[a].name,
						"icon":element.tree[a].icon,
						"href":"javascript: window.location.href = '"+FormaT.options.site_url+"index.php?pageActive=explore-category&type=devices&device_type="+element.id+"&device_manufacturer="+element.tree[a].id+"'"
					});
					/*
					$html += '<li>';
						$html += '<a href="+'">';
							$html += '<i class="'+element.tree[a].name+'"></i>';
							$html += element.tree[a].name;
						$html += '</a>';
					$html += '</li>';*/
				}
			}else{
				$html += '<li>';
					$html += '<a href="#">Ninguna</a>';
				$html += '</li>';
			}
		$html += '</ul>';
	$html += '</li>';
	return $html;
}

function crearMenuSideBarDefault(data){
	FormaT.app("POST", "categories", 
	{
		"action":"view",
		"list":true,
		"type":data.type
	}, function(r){
		console.log(r);
		if(r.error === false){
			
			$html = '';
			$html += '<li>';
				$html += '<a href="#'+data.type+'Submenu" data-toggle="collapse" aria-expanded="false">';
					$html += '<i class="'+data.icon+'"></i> ';
					$html += data.name;
				$html += '</a>';
				$html += '<ul class="collapse list-unstyled" id="'+data.type+'Submenu">';
					for (i = 0; i < r.data.length; i++) { 
						if(r.data[i].name){
							$html += parseLiMenuSideBar(r.data[i],r.permisos);								
						}
					}
					
					if(r.permisos !== undefined && r.permisos.create == true){
						$html += itemMenuSideBar({
							"name":"Nueva Categoria",
							"icon":"glyphicon glyphicon-plus",
							"href":"javascript:dialogCreateCategoryFast('"+data.type+"');"
						});
					};
					
				$html += '</ul>';
			$html += '</li>';
			console.log("Agregando crearMenuSideBarDefault()");
				
			$("#menu-sidebar").append($html);
		}else{
			console.log("Error Cargando Categoria SideBar "+data.type);
		}
	});
};

function itemMenuSideBar(element){
	$html = '<li>';
		$html += '<a>';
			$html += '<span class="cursor-pointer" onclick="'+element.href+'">';
				$html += '<i class="'+element.icon+'"></i> ';
				$html += element.name+' ';
			$html += '</span>';
			
			
		$html += '</a>';
	$html += '</li>';
	
	
	return $html;
}

function parseLiMenuSideBar(element,permisos){
	$html = '';
	if(element.name != undefined){
		console.log('creando menú de: '+ element.name);
		
		$html += '<li>';
			$html += '<a>';
				$html += '<span class="cursor-pointer" onclick="location.replace('+"'"+FormaT.options.site_url+'index.php?pageActive=explore-category&type='+element.type+'&of='+element.id+"&v=t'"+');">';
					$html += '<i class="'+element.icon+'"></i> ';
					$html += element.name+' ';
				$html += '</span>';
				if(permisos !== undefined && permisos.delete == true){
					$html += '<span class="cursor-pointer" onclick="javascript:deleteCategory('+element.id+');">';
						$html += '<i class="fas fa-ban"></i>';
					$html += '</span> ';
				};
				if(permisos !== undefined && permisos.edit == true){
					$html += '<span class="cursor-pointer" onclick="javascript:dialogEditCategoryFast('+element.id+','+"'"+element.name+"'"+','+element.raiz+','+"'"+element.type+"'"+');">';
						$html += '<i class="fas fa-pencil-alt"></i>';
					$html += '</span>';
				};
			$html += '</a>';
		$html += '</li>';
		
		if(element.tree.length>0){
			$html += '<li>';
				$html += '<a href="#'+element.id+'-Submenu" data-toggle="collapse" aria-expanded="false">';
					$html += '<i class="'+element.icon+'"></i>';
					$html += element.name+' [Categorias]';
				$html += '</a>';
				$html += '<ul class="collapse list-unstyled" id="'+element.id+'-Submenu">';
					if(element.tree.length>0){
						for (a = 0; a < element.tree.length; a++) { 
							if(element.tree[a].name){
								console.log("Creando Menu Sidebar: " + element.tree[a].name);
								$html += parseLiMenuSideBar(element.tree[a],permisos);								
							}
						}
					};
					
				$html += '</ul>';
			$html += '</li>';
		}
	}
	
	return $html;
};

function cargarDatosPerfil(){
	session = FormaT.loadSession();
	$datos = session.authResponse.signedRequest;
	console.log($datos);
	
	$(".format-micuenta-nombre").html($datos.nombre);
	$(".format-micuenta-link-profile").attr('href',FormaT.options.site_url+'index.php?pageActive=view-profiles&nick_profile='+$datos.user+'&id_profile='+$datos.id);
	$(".format-micuenta-cargo-name").html($datos.cargo_name);
	$(".format-micuenta-rol-name").html($datos.rol_name);
	$('.format-micuenta-avatar-url').css('background-image', 'url(' + $datos.avatar_url + ')');
	$(".format-micuenta-piloto-name").html($datos.piloto_name);
}

function parsePublish($data){
	$link = FormaT.options.site_url+'index.php?pageActive=single&type='+$data.type+'&id_ref='+$data.id;
	
	$h = '<article id="publish-id-'+$data.id+'" class="white-panel animated pulse"><img onclick="javascript:window.location = '+"'"+$link+"'"+';" src="'+$data.thumbnail_url+'" alt="">';
		$h += '<div class="panel-heading panel-default"><h5><a href="'+$link+'">'+$data.title+'</a></h5></div>';
		$h += '<div class="panel-body">';
			$h += '<p>'+$data.short_description+'</p>';
		$h += '</div>';
		$h += '<div class="panel-footer">';
			$h += '<p>'+$data.fcreate+'</p>';
		$h += '</div>';
		
		
		
		$h += '<footer>';
			if($data.edit == true){
				$h += '<a href="'+$link+'" class="btn btn-sm btn-warning"> Modificar / Leer Más.</a>';
			}else{
				$h += '<a href="'+$link+'" class="btn btn-sm btn-info">Leer Más.</a>';
			};
			if($data.delete == true){
				$h += '<a class="btn btn-sm btn-danger" href="javascript:deletePublish('+$data.id+');">Eliminar</a>';
			};
		$h += '</footer>';
	$h += '</article>';
	
	return $h;
}

function cargarUltimasEcards(){
	console.log("Cargando cargarUltimasEcards()");
	
	$('.banner-carousel').each(function(index) {
		$thisElement = $(this);
		$datos = $(this).data();
		
		if(!$datos.page){ $datos.page = 1; }else{ $datos.page = ($datos.page); };
		if(!$datos.limit){ $datos.limit = 10; };
		
		if($datos.type != undefined && $datos.page != undefined && $datos.limit != undefined){
			console.log("Enviando Peticion...");
			
			FormaT.app("POST", "publicaciones", 
			{
				"action":"view",
				"type":$datos.type,
				"page":$datos.page,
				"limit":$datos.limit
			}, function(r){
				console.log(r);
				if(r.error === false && r.data.length > 0){
					$thisElement.data("page",r.fields.page_next);
					console.log("page_next: "+r.fields.page_next);
					
					$.each(r.data,function(index, element){
						if($(".pinBoot #publish-id-"+element.id).length > 0){
							console.log("SI EXITE");
						}else{
							if(index == 0){ $label = 'active'; }else{ $label = ''; };
							$link = FormaT.options.site_url+'index.php?pageActive=single&type='+element.type+'&id_ref='+element.id;
							console.log("NO EXITE");
							
							// Validar si existe URL
							$linkBanner = '';
							$linkBannerSup = '';
							$linkBannerDetect = false;
							if(element.type == 'ecards' && element.data != '#'){
								$linkBannerSup = ' onclick="javascript:window.open('+"'"+element.data+"'"+');" data-toggle="tooltip" title="Visitar Enlace" class="cursor-pointer" ';
								$linkBanner = ' onclick="javascript:window.open('+"'"+element.data+"'"+');" data-toggle="tooltip" title="Visitar Enlace" ';
								$linkBannerDetect = true;
							};
							
							$h = '<div id="publish-id-'+element.id+'" class="item '+$label+'">';
								$h += '<img '+$linkBannerSup+' src="'+element.thumbnail_url+'">';
								
								$h += '<div class="carousel-caption">';
									$h += '<!-- <h3>title</h3> -->';
									$h += '<p>';
										if(r.permisos !== undefined && r.permisos.edit == true){
											$h += '<a href="'+$link+'" class="btn btn-sm btn-warning">Modificar</a>';
										}
										if(r.permisos !== undefined && r.permisos.delete == true){
											$h += '<a class="btn btn-sm btn-danger" href="javascript:deletePublish('+element.id+');">Eliminar</a>';
										}
									$h += '</p>';
								$h += '</div>';
							$h += '</div>';
							//CREAR CODIGO PARA AGREGAR AL BANNER EL ELEMENTO -> element
							$(".banner-carousel .carousel-inner").append($h);
														
							$h = '<li data-target="#myCarousel" data-slide-to="'+index+'" class="'+$label+'">';
								$h += '<a href="#">';
									$h += element.category_name;
									$h += '<small>';
										$h += element.title;
									$h += '</small>';
									if($linkBannerDetect == true){
										$h += '<button '+$linkBanner+' class="btn btn-xs btn-danger">Saber Mas</button>';
									}
								$h += '</a>';
							$h += '</li>';
							$(".banner-carousel .carousel-info").append($h);
							
							
							//$(".banner-carousel .carousel-inner").append(parsePublish(element)); //AGREGAR DESDE EL FINAL
							//$(".pinBoot").prepend(parsePublish(element)); AGREGAR DESDE EL INICIO
						}
					});
				}else{
					$.notify("Error cargando las ultimas ecards o No hay mas contenido para el banner...", "info");
					console.log("No hay mas contenido en banner");
				}
			});
		}else{
			console.log("Error cargarUltimasPublicaciones()");
		}
	});
};

function cargarUltimasPublicaciones(){
	console.log("Cargando cargarUltimasPublicaciones()");
	$(".spinFormaT-pinBoot").show();
	
	$('.pinBoot').each(function(index) {
		$thisElement = $(this);
		$datos = $(this).data();
		
		if(!$datos.page){ $datos.page = 1; }else{ $datos.page = ($datos.page); };
		if(!$datos.limit){ $datos.limit = 10; };
		if(!$datos.category){ $datos.category = 0; };
		
		if($datos.type != undefined && $datos.page != undefined && $datos.limit != undefined){
			console.log("Enviando Peticion...");
			
			FormaT.app("POST", "publicaciones", 
			{
				"action":"view",
				"type":$datos.type,
				"page":$datos.page,
				"of":$datos.category,
				"limit":$datos.limit
			}, function(r){
				console.log(r);
				
				$(".spinFormaT-pinBoot").hide();
				if(r.error === false && r.data.length > 0){
					$thisElement.data("page",r.fields.page_next);
					console.log("page_next: "+r.fields.page_next);					
					$.each(r.data,function(index, element){
						if($(".pinBoot #publish-id-"+element.id).length > 0){
							console.log("SI EXITE");
						}else{
							console.log("NO EXITE");
							
							$(".pinBoot").append(parsePublish(element)); //AGREGAR DESDE EL FINAL
							//$(".pinBoot").prepend(parsePublish(element)); AGREGAR DESDE EL INICIO
						}
					});
				}else{
					$.notify("Error cargando las ultimas publiciones o No hay mas contenido...", "info");
					console.log("No hay mas contenido");
				}
			});
		}else{
			console.log("Error cargarUltimasPublicaciones()");
		}
		
	});
};

function cargarDispositivosExplode(){
	console.log("Cargando cargarDispositivosExplode()");
	$(".spinFormaT-pinBoot").show();
	
	$('.pinBoot-devices').each(function(index) {
		$thisElement = $(this);
		$datos = $(this).data();
		
		if(!$datos.page){ $datos.page = 1; }else{ $datos.page = ($datos.page); };
		if(!$datos.limit){ $datos.limit = 10; };
		if(!$datos.type){ $datos.type = 0; };
		if(!$datos.manufacturer){ $datos.manufacturer = 0; };
		
		if($datos.type != undefined && $datos.page != undefined && $datos.limit != undefined){
			console.log("Enviando Peticion...");
			
			FormaT.app("POST", "devices", 
			{
				"action":"view",
				"view_devices":true,
				"type":$datos.type,
				"manufacturer":$datos.manufacturer,
				"page":$datos.page,
				"limit":$datos.limit
			}, function(r){
				console.log(r);

				$(".spinFormaT-pinBoot").hide();
				//alert(r.data.length);
				
				if(r.error === false && r.data.length > 0){
					$thisElement.data("page",r.fields.page_next);
					console.log("page_next: "+r.fields.page_next);					
					$.each(r.data,function(index, element){
						if($(".pinBoot-devices #device-id-"+element.id).length > 0){
							console.log("SI EXITE");
						}else{
							console.log("NO EXITE");
							
							$(".pinBoot-devices").append(parseDevicesFeed(element)); //AGREGAR DESDE EL FINAL
							//$(".pinBoot-devices").prepend(parseDevicesFeed(element)); AGREGAR DESDE EL INICIO
						}
					});
				}else{
					$.notify("Error cargando lo dispositivos o No hay mas contenido...", "info");
					$.notify(r.message, "error");
					console.log("No hay mas contenido");
				}
			});
		}else{
			console.log("Error cargarDispositivosExplode()");
		};
	});
};

function parseDevicesFeed($data){
	$link = FormaT.options.site_url+'index.php?pageActive=explore-category&type=devices&device_type='+$data.type+'&device_id='+$data.id;
	
	$h = '<article id="device-id-'+$data.id+'" class="white-panel animated pulse"><img src="'+$data.image_icon_url+'" alt="">';
		$h += '<h4><a href="'+$link+'">'+$data.name+'</a></h4>';
		//$h += '<p>'+$data.short_description+'</p>';
		//$h += '<p>'+$data.fcreate+'</p>';
		
		$h += '<footer>';
				$h += '<a href="'+$link+'" class="btn btn-sm btn-info">Leer Más.</a>';
		$h += '</footer>';
	$h += '</article>';
	
	return $h;
}

/// Eliminar una categoria
function deleteCategory(id){
	bootbox.confirm({
		message: "Confirma que desea eliminar la categoria ["+id+"]?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){				
				FormaT.app("POST", "categories", {
					"action":"delete",
					"id":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminada con exito!...", "success");
						location.reload();
					}else{
						$.notify("Error eliminando la categoria...", "error");
					}
				});
				
			}
		}
	});
	
};

function dialogCreateCategoryFast(type){
	$("#modal-create-categories").modal("show");
	$('#modal-create-categories input[name="type"]').val(type);
	
	FormaT.app("POST", "categories", {
		"action":"option_list",
		"type":type
	}, function(r){
		if(r.error === false){
			
			$('#modal-create-categories select[name="raiz"]').html("");
			$select = $('#modal-create-categories select[name="raiz"]');
			$select.append('<option value="">Seleccione...</option>');
			$select.append('<option value="0">Ninguna...</option>');
			
			$.each(r.data,function(index, element){
				$select.append('<option value=' + element.value + '>' + element.text + '</option>');
			});
		}else{
			$.notify("Error cargando las categorias...", "error");
		}
	});
};

function createCategoryFast(){
	$fields = {};
	$fields.name = $('#modal-create-categories input[name="name"]').val();
	$fields.raiz = $('#modal-create-categories select[name="raiz"]').val();
	$fields.type = $('#modal-create-categories input[name="type"]').val();
	
	if($fields.name != '' && $fields.raiz != ''){
		FormaT.app("POST", "categories", {
			"action":"create",
			"name":$fields.name,
			"raiz":$fields.raiz,
			"type":$fields.type
		}, function(r){			
			if(r.error === false){
				$.notify(r.message, "success");
				location.reload();
			}else{
				$.notify("Error creando la categoria...", "error");
				console.log(r);
			};
		});
	}else{
		$.notify("Completa los datos...", "error");
	};
};

function dialogEditCategoryFast(id,name,raiz,type){
	$("#modal-edit-categories").modal("show");
	
	FormaT.app("POST", "categories", {
		"action":"view",
		"option_list":true,
		"type":type
	}, function(r){
		if(r.error === false){
			$('#modal-edit-categories select[name="raiz"]').html("");
			$select = $('#modal-edit-categories select[name="raiz"]');
			$select.append('<option value="">Seleccione...</option>');
			$select.append('<option value="0">Ninguna...</option>');
			$.each(r.data,function(index, element){
				$select.append('<option value=' + element.value + '>' + element.text + '</option>');
			});
			
			
			$('#modal-edit-categories input[name="id"]').val(id);
			$('#modal-edit-categories input[name="name"]').val(name);
			$('#modal-edit-categories select[name="raiz"]').val(raiz);
			$('#modal-edit-categories input[name="type"]').val(type);
		}else{
			$.notify("Error cargando las categorias...", "error");
		}
	});
};

function editCategoryFast(){
	$fields = {};
	$fields.id = $('#modal-edit-categories input[name="id"]').val();
	$fields.name = $('#modal-edit-categories input[name="name"]').val();
	$fields.raiz = $('#modal-edit-categories select[name="raiz"]').val();
	$fields.type = $('#modal-edit-categories input[name="type"]').val();
	
	if($fields.name != '' && $fields.raiz != ''){
		FormaT.app("POST", "categories", {
			"action":"change",
			"id":$fields.id,
			"name":$fields.name,
			"raiz":$fields.raiz,
			"type":$fields.type
		}, function(r){			
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				location.reload();
			}else{
				$.notify("Error eliminando la categoria...", "error");
			};
		});
	}else{
		$.notify("Completa los datos...", "error");
	};
};

function cargarAlertIndv(theId){
	var objeto_window_referencia;
	var configuracion_ventana = "menubar=no,location=yes,resizable=n0,scrollbars=0,status=0,width=420,height=240";
	objeto_window_referencia = window.open(FormaT.options.site_url+'index.php?pageActive=notifications&view=single&id='+theId, "Visor de Notificaciones", configuracion_ventana);
}

/// Comenzar Busqueda en la Web
function searchIntro(){
	$('.main-search').show();
	$("#search-bar-results").append("");
	value = $( "#search-bar-input" ).val();

	FormaT.app("POST", "search", 
	{
		"type":"list",
		"q":value
	}, function(r){
		if(r.error === false){
			data = r.data;
			$html = '';
			for (i = 0; i < data.length; i++) {
				$html += '<li>';
					$html += '<a href="javascript:location.replace('+"'"+data[i].direct_url+"'"+')">';
						$html += data[i].type;
						$html += " | ";
						$html += data[i].title;
					$html += '</a>';
				$html += '</li>';
			}
			$("#search-bar-results").html($html);
		}else{
			$("#search-bar-results").append("No hay contenido");
		}
	});
};

/// Responder las preguntas
function responseReplyModal(id,query,topic){
	bootbox.prompt({
		title: 'Tema: '+topic+' | Duda: '+query,
		inputType: 'textarea',
		callback: function (result) {
			if(result != '' && result != null){
				var datos = {};
				datos.action = "response";
				datos.id = id;
				datos.response = result;
				
				console.log(datos);
				
				if(datos.id != '' && datos.response != ''){
					FormaT.app("POST", "comments", datos, function(r){
						console.log(r);
						if(r.error === false){
							$.notify(r.message, "success");
							if($("#question-response-navbar-id-"+datos.id).length > 0){
								$("#question-response-navbar-id-"+datos.id).remove();
								$( "#questions-id-"+datos.id ).addClass( 'testimonial-success-filled' );
								$( "#questions-id-"+datos.id ).removeClass( 'testimonial-info-filled' );
								
								$('.response-query-id-'+datos.id).html(result);
								
							};
							$(".total-questions-response-navbartop").html($(".total-questions-response-navbartop").text()-1);
						}else{
							$.notify("Error respondiendo el comentario...", "error");
						}
					});
				}else{
					$.notify("Faltan algunos campos...", "warn");
				}
			}else{
				$.notify("Responde...", "error");
			}
		}
	});
};

/// Hacer una pregunta
function createCommentsForum(id,comment_raiz){
	var datos = {};
	datos.action = "create";
	datos.raiz = id;
	datos.comment_raiz = comment_raiz;
	datos.type = "forum";
	datos.query = $("#create-comments-forum-input-query-"+comment_raiz).val();
	console.log(datos);
	
	if(datos.raiz != '' && datos.type != '' && datos.query != ''){
		FormaT.app("POST", 'comments', datos, function(r){
			console.log(r);
			if(r.error === false){
				$.notify(r.message, "success");
				location.reload();
			}else{
				$.notify("Error creando la pregunta...", "error");
			}
		});
	}else{
		$("#create-comments-forum-input-query-"+comment_raiz).focus();
		$.notify("Faltan tu pregunta, duda o comentario...", "warn");
	}
};

/// Eliminar una pregunta
function deleteCommentsForum(id,query,topic){
	bootbox.confirm({
		message: "Confirma que desea eliminar la pregunta [ "+query+" ] [ "+id+" ]?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){				
				FormaT.app("POST", "comments", {
					"action":"delete",
					"query":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminado con exito!...", "success");
						location.reload();
					}else{
						$.notify("Error eliminando la pregunta...", "error");
						console.log(r);
					}
				});
				
			}
		}
	});
	
};

// guardar publicacion
function savePublish(idPublish,public,type){
	FormaT.app("POST", "publicaciones", {
		"action":"change",
		"id":idPublish,
		"type":type,
		"public":public
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify("Guardado...", "success");
			location.reload();
		}else{
			$.notify("Error guardando la publicacion...", "error");
		}
	});
};

// Eliminar publicacion
function deletePublish(idPublish){
	bootbox.confirm({
		message: "Confirma que desea eliminar la publicacion?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "publicaciones", {
					"action":"delete",
					"id":idPublish
				}, function(r){
					if(r.error === false){
						$.notify("eliminado...", "success");
						location.replace(FormaT.options.site_url);
					}else{
						$.notify("Error eliminando la publicacion...", "error");
					}
				});
			}
		}
	});
};

// Dar Like a una publicacion
function likePublish(idPublish,type){
	FormaT.app("POST", "my", 
	{
		"action":"create",
		"like_add":true,
		"type":type,
		"id_ref":idPublish
	}, function(r){
		console.log(r);
		if(r.error === false){
			if($(".total-likes-"+idPublish).length > 0){
				$(".total-likes-"+idPublish).text(r.stadistics.likes);
				$( ".btn-like-"+idPublish ).attr( "href" , "#");

				$.notify("Gracias por tu aporte...", "success");
			}
		}else{
			$.notify("Error sumando tu Like...", "success");
		}
	});
};

function cargarPublicacionPagina(){
	console.log("Iniciando cargarPublicacionPagina()");
	param = getUrlVars();
	if(param.type && param.id_ref){
		console.log("Cargando publicacion...");
		$(".btn-edit-publish").hide(); //hideRun poner
		$(".btn-delete-publish").hide();
		$(".btn-delete-publish").hide();
		$(".btn-file-image").hide();
		
		FormaT.app("POST", "publicaciones", {
			"action":"view",
			"type":param.type,
			"id":param.id_ref
		}, function(r){
			console.log(r);
			console.log("Cargado cargarPublicacionPagina()");
			
			if(r.error === false){
				$(".publish-page-view .title-edit").html(r.data.title);
				$(".publish-page-view .sub-title .plublish-category").html(r.data.category_name);
				$(".publish-page-view .name-author").html("Por "+r.data.author.nombre);				
				$(".publish-page-view .photo-author").attr("src",'api/v1.0/pictures.php?accesstoken='+FormaT.AccessToken()+'&id='+r.data.author.avatar);
				if(r.data.thumbnail > 0){
					$(".publish-page-view .picture-publish").attr("src",r.data.thumbnail_url);
				}
				
				$(".publish-page-view .total-likes-"+r.data.id).html(r.data.stadistics.likes);
				$(".publish-page-view .total-views-"+r.data.id).html(r.data.stadistics.views);
				$(".publish-page-view .publish-tags").html(r.data.tags.join());
				
				
				if(r.data.type == 'articles'){
					$(".publish-page-view .publish-data-page").html(r.data.data);
				}
				else if(r.data.type == 'ecards'){
					//r.data.data
					
					$ha = '<b>Link: </b><span class="publish-data-page-link">'+r.data.data+'</span><br>';
					$ha += '<img style="width:100%;" src="'+r.data.thumbnail_url+'" class="zoomImages image-preview-publish" />';
					
					$(".publish-page-view .publish-data-page").html($ha);
				}
						
				if(r.data.delete == true){
					$(".btn-delete-publish").show();
					
					if(r.data.trash == 0){
						$(".btn-trash-publish").html('<i class="fas fa-trash"></i>');
						$(".btn-trash-publish").attr('href','javascript:deletePublish('+r.data.id+');');
					}else if(r.data.trash == 1){
						$(".btn-trash-publish").html('<i class="fas fa-check"></i>');
						$(".btn-trash-publish").attr('href','javascript:reactivarPublish('+r.data.id+');');
					}
					
				};
				if(r.data.edit == true){
					$(".btn-edit-category").show();
					$(".btn-edit-category").attr('href',"javascript:changeCategoryPublish("+r.data.id+",'"+r.data.type+"');");
					$(".btn-public-publish").show();
					$(".btn-file-image").show();
					
					
					if(r.data.public == 0){
						$(".btn-public-publish").html('<i class="fas fa-eye"></i>');
						$(".btn-public-publish").attr('href','javascript:savePublish('+r.data.id+',1,'+"'"+r.data.type+"'"+');');
					}else if(r.data.public == 1){
						$(".btn-public-publish").html('<i class="fas fa-eye-slash"></i>');
						$(".btn-public-publish").attr('href','javascript:savePublish('+r.data.id+',0,'+"'"+r.data.type+"'"+');');
					}
					
					tinymce.init({
						selector: '.publish-tags',
						inline: true,
						plugins: 'save',
						toolbar: 'undo redo save',
						save_onsavecallback: function (save_onsavecallback) {
							console.log('Saved');							
							FormaT.app("POST", "publicaciones", {
								"action":"change",
								"id":r.data.id,
								"tags":$(".publish-tags").text()
							}, function(r){
								if(r.error === false){
									$.notify("Guardado...", "success");
								}else{
									$.notify("Error modificando el titulo...", "error");
								}
							});
						},
						menubar: false
					});
					
					tinymce.init({
						selector: '.header .title-edit',
						inline: true,
						plugins: 'save',
						toolbar: 'undo redo save',
						save_onsavecallback: function (save_onsavecallback) {
							FormaT.app("POST", "publicaciones", {
								"action":"change",
								"id":r.data.id,
								"title":$(".header .title-edit").text()
							}, function(r){
								if(r.error === false){
									$.notify("Guardado...", "success");
								}else{
									$.notify("Error modificando el titulo...", "error");
								}
							});
						},
						menubar: false
					});
					
					if(r.data.type == 'articles'){
						tinymce.init({
							selector: '.publish-data-page',
							//theme: "inline",
							inline: true,
							height: 2400,
							language_url: 'api/plugins/tinymce/langs/es.js',
							//powerpaste advcode tinymcespellchecker a11ychecker mediaembed linkchecker fullpage fullscreen preview
							plugins: 'paste save searchreplace autolink directionality visualblocks visualchars image link media template codesample code table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools  contextmenu colorpicker textpattern',
							toolbar1: 'save charmapsave formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | code numlist bullist outdent indent  | removeformat charmap',
							image_advtab: true,
							templates: [
								{ title: 'Test template 1', content: 'Test 1' },
								{ title: 'Test template 2', content: 'Test 2' }
							],
							content_css: [
								//'<?php echo url_site; ?>/dist/bootstrap/3.3.7/css/bootstrap.min.css',
								//'<?php echo url_site; ?>/dist/glyphicons/css/bootstrap.icon-large.min.css',
								//'<?php echo url_site; ?>/dist/fontawesome/5.0.6/css/fontawesome-all.min.css',
								//'<?php echo url_site; ?>/css/sidebar.css',
								//'<?php echo url_site; ?>/css/animate.css'
							],
							charmap_append: [
								[0x2600, 'sun'],
								[0x2601, 'cloud']
							],
							save_onsavecallback: function (save_onsavecallback) {
								console.log('Saved');
								
								FormaT.app("POST", "publicaciones", {
									"action":"change",
									"id":r.data.id,
									"data":escapeHtml($(".publish-data-page").html())
								}, function(r){
									if(r.error === false){
										$.notify("Guardado...", "success");
									}else{
										$.notify("Error modificando el titulo...", "error");
									}
								});
							},
							
							
							// enable title field in the Image dialog
							image_title: true, 
							// enable automatic uploads of images represented by blob or data URIs
							automatic_uploads: true,
							// URL of our upload handler (for more details check: https://www.tinymce.com/docs/configure/file-image-upload/#images_upload_url)
							// images_upload_url: 'postAcceptor.php',
							// here we add custom filepicker only to Image dialog
							file_picker_types: 'image', 
							// and here's our custom image picker
							file_picker_callback: function(cb, value, meta) {
								var input = document.createElement('input');
								input.setAttribute('type', 'file');
								input.setAttribute('accept', 'image/*');
								
								// Note: In modern browsers input[type="file"] is functional without 
								// even adding it to the DOM, but that might not be the case in some older
								// or quirky browsers like IE, so you might want to add it to the DOM
								// just in case, and visually hide it. And do not forget do remove it
								// once you do not need it anymore.

								input.onchange = function() {
								  var file = this.files[0];
								  
								  
								  var reader = new FileReader();
								  reader.onload = function (e) {
								  
									FormaT.app("POST", "pictures", 
									{
										"action":"create",
										"data":e.target.result
									}, function(r){
										if(r.error === false){
												
											var base64 = reader.result.split(',')[1];
											
											
											
											// Note: Now we need to register the blob in TinyMCEs image blob
											// registry. In the next release this part hopefully won't be
											// necessary, as we are looking to handle it internally.
											var url = FormaT.options.api_url_large+'/pictures.php?accesstoken='+FormaT.AccessToken()+"&id="+r.id;
											cb(url, { title: file.name });
											
											
											$.notify("imagen cargada...", "success");
										}else{
											$.notify("Error subiendo la imagen...", "error");
										}
									});
									
								  };
								  reader.readAsDataURL(file);
								};
								
								input.click();
							}
						});
					}else if(r.data.type == 'ecards'){

	
						tinymce.init({
							selector: '.publish-data-page-link',
							inline: true,
							plugins: 'save',
							toolbar: 'undo redo save',
							save_onsavecallback: function (save_onsavecallback) {
								FormaT.app("POST", "publicaciones", {
									"action":"change",
									"id":r.data.id,
									"data":$(".publish-data-page-link").text()
								}, function(r){
									if(r.error === false){
										$.notify(r.message, "success");
									}else{
										$.notify(r.message, "error");
									}
								});
							},
							menubar: false
						});
					}
				}
				
				$(".spin-publish-page").hide();
				$.notify(r.message, "success");
			}else{
				$.notify(r.message, "error");
				$.notify("la publicacion no exite o no tienes permisos para acceder.", "error");
			}
		});
	}
}

// cambiar categoria de una publicacion
function changeCategoryPublish(id,type){
	FormaT.app("POST", "categories", {
		"action":"view",
		"option_list":true,
		"type":type
	}, function(r){
		if(r.error === false){
			bootbox.prompt({
				title: "Selecciona la categoria donde deseas publicar el contenido.",
				inputType: 'select',
				inputOptions: r.data,
				callback: function (result) {
					if(result>0){						
						FormaT.app("POST", "publicaciones", {
							"action":"change",
							"id":id,
							"category":result
						}, function(r2){
							if(r2.error === false){
								$.notify("Guardado...", "success");
								
								FormaT.app("POST", "categories", {
									"action":"view",
									"id":result
								}, function(c){
									if(c.error === false){
										$(".name-category-edit").text(c.data.name);
									}
								});
								location.reload();
							}else{
								$.notify("Error modificando la imagen de la publicacion...", "error");
							}
						});
					}else{
						$.notify("La categoria no se modifico...", "error");
					}
				}
			});
			/*
				location.reload();
			*/
		}else{
			$.notify("Error cargando las categorias...", "error");
		}
	});
};

function stadistQuizExportPage(){
	console.log("Cargado stadistQuizExportPage();");
	
	param = getUrlVars();
	
	FormaT.app("POST", "quiz", 
	{
		"action":"view",
		"page":"quiz",
		"export":true,
		"id":param.topic
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify(r.message, "success");
			
			
			
			$(".title-quiz").html(r.quiz.title);
			$(".fecha_creation-quiz").html(r.quiz.fecha_creation);
			$(".total-quiz").html(r.quiz.querys.length);
			
			if(r.permisos && r.permisos !== undefined){
				if(r.permisos.create == true){ $(".btn-quiz-create-querys").show(); };
				if(r.permisos.edit == true){ $(".btn-quiz-publish").show(); };
				if(r.permisos.delete == true){ $(".btn-quiz-delete").show(); };
				
				
				
				if(r.permisos.export.quiz == true){
					
					
					if (typeof r.export !== 'undefined' && r.export !== null) {
						for (b = 0; b < r.export.length; b++) {
							element = (r.export[b]);
							console.log(element);
							
							$j = '<tr>';
								$j += '<td>'+element.user.user+'</td>';
								$j += '<td>'+element.user.nombre+'</td>';
								$j += '<td>';
									console.log(element.user.result);
								
									if (typeof element.result !== 'undefined' && element.result !== null) {
										$j += '<table>';
											for (e = 0; e < element.result.length; e++) {
												if(element.result[e].response.value>0 || element.result[e].response.value == 'true'){
													$color = "success";
												}else{
													$color = "danger";
												}
												
												$j += '<tr class="list-group-item alert-'+$color+'">';
													$j += '<td>'+element.result[e].query+'</td>';
													$j += '<td>'+element.result[e].response.text+'</td>';
												$j += '</tr>';
											}
										$j += '</table>';
									}
								$j += '</td>';
								$j += '<td>'+element.result_note+'</td>';
								$j += '<td>'+(r.quiz.querys.length-(element.result_note))+'</td>';
								$j += '<td>'+((element.result_note*100)/r.quiz.querys.length)+'</td>';
							$j += '</tr>';
							
							$(".querys-body").append($j);
						}
					}
					
				};
			}
			
		}else{
			$.notify(r.message, "error");
		}
	});
}


























function dialogCreateQuestionQuizFast(id){
	console.log(id);
	$("#modal-create-question-quiz").modal("show");
	
	$('#modal-create-question-quiz input').val("");
	$('#modal-create-question-quiz input[name="topic"]').val(id);
	$(".btn-remove").click();
};

function createQuestionQuizFast(){
	console.log("Comenzar createQuestionQuizFast()");
	$datos = {};
	$datos.topic = $('#modal-create-question-quiz input[name="topic"]').val();
	$datos.query = $('#modal-create-question-quiz input[name="query"]').val();
	$datos.response = new Array();
	
	
	$Total = 0;
	$TotalOk = 0;
	if($datos.query != ''){
		$('#modal-create-question-quiz .contacts').find('.multiple-form-group').each(function(indx) {
			$Total++;
			$arreglo = {};
			$(this).find('input[name="text"]').each(function() { $arreglo.text = $(this).val() });
			$(this).find('select[name="value"]').each(function() { $arreglo.value = $(this).val() });
			if($arreglo.text != '' && $arreglo.value != ''){
				$datos.response.push($arreglo);
				$TotalOk = $TotalOk + 1;
			}else{
				$.notify("Completa los datos...", "error");
				$TotalOk = $TotalOk - 1;
			};
		});
		
		if($TotalOk == $Total){
			FormaT.app("POST", "quiz", {
				"page":"questions",
				"action":"create",
				"topic":$datos.topic,
				"query":$datos.query,
				"response":JSON.stringify($datos.response)
			}, function(r){
				console.log(r);
				if(r.error === false){
					$.notify(r.message, "success");
					$(".querys-body").append(crearQueryPageCreate(r));
				}else{
					$.notify(r.message, "error");
				};
				$("#modal-create-question-quiz").modal("hide");
			});
		}else{
			$.notify("Completa los datos...", "error");
		}
	}else{
		$.notify("Completa los datos...", "error");
	}
};

function crearQueryPageCreate(element){
	console.log(element);
	$j = '<div class="col-sm-12" id="question-id-'+element.id+'">';
		$j += '<div class="col-sm-1">';
			$j += '<a href="javascript:deleteQuestionQuiz('+element.id+');"><i class="fas fa-ban"></i></a>';
		$j += '</div> ';
		$j += '<div class="col-sm-5">';
			$j += '<label class="query-question-'+element.id+'">'+element.fields.query+'</label>';
		$j += '</div>';
		$j += '<div class="col-sm-6">';
			$j += '<ul class="list-group query-question-'+element.id+'">';
			
			if(element.fields.response != undefined){
				console.log("Cargando element.fields.response");
				for (i = 0; i < element.fields.response.length; i++) {
					
					$j += '<li class="list-group-item">';
						$j += element.fields.response[i].text;
						if(element.fields.response[i].value == "true"){
							$j += ' [ Correcta ] ';
							
						}else if(element.fields.response[i].value == "false"){
							$j += ' [ Incorrecta ] ';
						}
					$j += '</li>';
				}
			}
			$j += '</ul>';
		$j += '</div>';
	$j += '</div>';
	return $j;
}

// Crear alerts
function deleteQuestionQuiz(id){
	bootbox.confirm({
		message: "Confirma que desea eliminar esta pregunta?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "quiz", {
					"page":"questions",
					"action":"delete",
					"id":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminada con exito!...", "success");
						$("#question-id-"+id).remove();
						
						//location.reload();
					}else{
						$.notify("Error eliminando la pregunta...", "error");
					}
				});
				
			}
		}
	});
};

function activeQuiz(id){
	bootbox.confirm({
		message: "Confirma que desea activar el quiz?, al activar este quiz se desactiva de manera automatica cualquier otro quiz que esté activo.",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "quiz", {
					"page":"quiz",
					"action":"change",
					"active":true,
					"id":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify(r.message, "success");
						
						//setInterval(function(){ location.replace(FormaT.options.site_url+'/index.php?pageActive=export-quiz'); },1000);
					}else{
						$.notify(r.message, "error");
					}
				});
				
			}
		}
	});
}

function deleteQuiz(id){
	bootbox.confirm({
		message: "Confirma que desea eliminar este quiz?",
		buttons: {
			confirm: {
				label: 'Continuar',
				className: 'btn-success'
			},
			cancel: {
				label: 'Cancelar',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if(result == true){
				FormaT.app("POST", "quiz", {
					"action":"delete",
					"id":id
				}, function(r){
					console.log(r);
					if(r.error === false){
						$.notify("Eliminada con exito!...", "success");
						setInterval(function(){ location.replace(FormaT.options.site_url); },1000);
					}else{
						$.notify("Error eliminando la pregunta...", "error");
					}
				});
				
			}
		}
	});
};

function disableQuizAndEdit(id){
	FormaT.app("POST", "quiz", {
		"action":"change",
		"id":id,
		"view":0
	}, function(r){
		console.log(r);
		if(r.error === false){
			$.notify("Quiz Desactivado con exito!...", "success");
			
			setInterval(function(){ location.replace(FormaT.options.site_url+'/index.php?pageActive=create-quiz&type=quiz&draft='+id); },1000);
		}else{
			$.notify("Error Desactivando el Quiz...", "error");
		}
	});
}

/** FIN **/

function getUrlVars() {
	var map = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		map[key] = value;
	});
	return map;
}

function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}



/** VIRTUAL STEPS **/
function cargarVirtualStepsFelipheGomez(){ 
	(function($) {
		$(function() {
			var FormaTManuals = window.FormaTManuals || {};
			FormaTManuals.HighlightHandler = $('.highlights').actions(window.FormaTManuals.HighlightOptions);
			
			$('.worldmanuals').on('click', 'a', function(e) {
				var $elm = $(this);
				var url = $elm.attr('href');
				var rel = $elm.attr('rel');
				var firstRel = typeof rel !== "undefined" ? rel.split(' ')[0] : "";
				var event = e;

				switch (firstRel) {
					case 'promo-image':
						var $target = $('.device-image-carousel .device-image');
						var loading = new Spinner();

						$target.attr('src', '').hide().parent().append(loading.el);

						$('.device-image-thumbnails .active').removeClass('active');
						$elm.parents('li').addClass('active');

						$target.bind('load', function() {
							$target.show();
						});

						$target.attr('src', url);
						return false;
					case 'pagination':
						if ($elm.hasClass('disabled')) {
							return false;
						}
						var forward = $elm.parents('li').hasClass('next');
						var activeStep = $('.block').index($('.block.active-line'));
						var nextStep = forward ? parseInt(activeStep) + 1 : parseInt(activeStep) - 1;

						$('.block:eq(' + nextStep + ')').trigger('click');
						return false;
					case 'change-view':
						changeInstructionView();
						return false;
					default:
						if (($elm.attr('href') || "").substr(0, 1) === "#") {
							return false;
						}
						return;
				}
				return false;
			});

			$('a[rel="promo-image"]').mouseover(function() {
				var $this = $(this);

				return false;
			});

			$('.worldmanuals').on('click', '.blocks .block', function(e) {
				var $block = $(this),
					$pointer = $block.find('.pointer:first');


				if ($pointer.hasClass('active') || $block.hasClass('active-line')) {
					return;
				}

				if ($pointer.length == 0) {
					$('.block.active-line').removeClass('active-line');
					$block.addClass('active-line');
					updateSteps();
				}

				$pointer.click();
			});
			$('.worldmanuals').on('click', '.blocks .pointer', function(e) {
				e.stopPropagation();

				var $pointer = $(this),
					$block = $pointer.parents('.block:first');

				if ($pointer.length == 0) return;

				if (isMobile()) {
					// highlight
					var highlight = $('<div class="highlight"></div>').actions({
						maxWidth: $(document).width() - 60,
						maxHeight: $(document).height() - 60,
						cdn: window.WorldManuals.HighlightOptions.cdn,
						masterImage: window.WorldManuals.HighlightOptions.masterImage
					});

					if ($pointer.length > 0) {
						highlight = highlight.generateHighlight($pointer.data());

						$('<div class="worldmanuals worldmanuals-lightbox"></div>')
							.append(highlight, $('<div/>').addClass('close-corner close'))
							.lightbox_me();
					}
					return;
				}

				$('.blocks .active-line').removeClass('active-line');
				$block.addClass('active-line');
				updateSteps();

				$('.pointer.active').removeClass('active');
				$pointer.addClass('active');

				window.FormaTManuals.HighlightHandler.show($pointer.data(), true);

			});

			$('.chapters .chapter h2 a').on('click', function() {
				$('.chapters .chapter h2').removeClass("active");
				$('.chapters .chapter .accordion').removeClass("active");
				var $this = $(this);

				$this.parent().next('.accordion').toggleClass('active');
				$this.parent('h2').toggleClass('active');

				// update url
				history.pushState({}, '', $this.attr("href"));
				return false;
			});

			if (!isMobile()) {
				$('.block:first').trigger('click');
			}

			$('.worldmanuals').on('click', '.highlights .wm-hl-pointer', function() {
				var pagination = $('.pagination');
				if (pagination.length) {
					$('.device-image .pagination .next a[rel=pagination]').trigger('click');
				} else {
					var activeStep = $('.pointer').index($('.pointer.active'));
					var nextStep = parseInt(activeStep) + 1;

					$('.pointer:eq(' + nextStep + ')').trigger('click');
				}
			});

			$('select#manufacturers').on('change', function() {
				var $this = $(this).val();
				var manufacturerDevices = window.jsonDevices;

				var returnedData = $.grep(manufacturerDevices, function(element, index) {
					return element.ManufacturerSlug == $this;
				});

				$('#devices').find('option').remove().end().append('<option>Modelo</option>');
				$.each(returnedData, function(index, value) {
					$('#devices').append('<option value="' + value.Url + '">' + value.Name + '</option>');
				});
			});

			$('select#devices').on('change', function() {
				window.location = $(this).val();
			});

			$(window).scroll(function() {
				if ($('.worldmanuals .view .left').is(':visible') && !$('.worldmanuals .view .left').hasClass('disable-scroll')) {
					var $elementToScrollWithIn = $('.worldmanuals .view'),
						$elementToScroll = $('.worldmanuals .view .left .highlights'),
						$window = $(window);

					var topOffset = 60;

					var scrollOffset = $elementToScroll.parents('.left').offset().top;
					var minScroll = 0;
					var maxScroll = $elementToScrollWithIn.height() - $elementToScroll.outerHeight() - scrollOffset;
					var distanceScrolled = $window.scrollTop();
					var distanceToScroll = distanceScrolled - scrollOffset + topOffset;

					if (distanceToScroll > maxScroll) {
						$elementToScroll.stop(true).css({
							'margin-top': maxScroll
						});
						return;
					}
					if (distanceToScroll < minScroll) {
						$elementToScroll.stop(true).css({
							'margin-top': minScroll
						});
						return;
					}
					if (distanceToScroll > minScroll && distanceToScroll < maxScroll) {
						$elementToScroll.stop(true).animate({
							'marginTop': distanceToScroll
						});
					}
				}
			});

			$('.feedback .links .no').on('click', function() {
				$('.comment').toggle();
			});

			$('.feedback .question .comment a.send').on('click', function() {
				var radioButtonId = $(".comment ul li input[type='radio']:checked").attr("id");
				var radioButtonVal = $(".comment ul li input[type='radio']:checked").val();
				var comment = $(this).parents(".comment").find('ul li.text input').val();
				var title = $("meta[property='og:title']").attr("content").replace(window.DeviceName + " - ", "").replace(" - FormaT", "");

				var isAmena = (window.location.pathname.indexOf("/sp/") > -1);

				$.ajax({
					url: "http://wm-admin.com/feedback/SaveFeedbackFromAjax",
					dataType: "jsonp",
					data: {
						applicationId: 2,
						clientId: 'ORES2011',
						comment: radioButtonVal + ": " + comment,
						vote: 0,
						ip: window.UserIp,
						userAgent: window.UserAgent,
						manualId: window.ManualId,
						device: window.DeviceName,
						isSatisfied: false,
						contentTitle: title
					},
					success: function(data) {
						cosole.log(data);
						$('.feedback .question').hide();
						switch (radioButtonId) {
							case "r1":
								$('.feedback .message').html("Gracias por su comentario. Tu opinion nos ayudara a mejorar.");
								break;
							case "r2":
								$('.feedback .message').html("Gracias por su comentario. Tu opinion nos ayudara a mejorar.");
								break;
							case "r3":
								$('.feedback .message').html("Gracias por su comentario. Tu opinion nos ayudara a mejorar.");
								break;
							case "r4":
								$('.feedback .message').html("Gracias por su comentario. Tu opinion nos ayudara a mejorar.");
								break;
							case "r5": //Other
								$('.feedback .message .r5').show();
								break;
							default:
								$('.feedback .message').html("");
								break;
						}

						$('.feedback .message').show();
					}
				});

				return false;
			});

			$('.feedback .links .yes').on('click', function() {
				var title = $("meta[property='og:title']").attr("content").replace(window.DeviceName + " - ", "").replace(" - FormaT", "");
				$.ajax({
					url: "http://wm-admin.com/feedback/SaveFeedbackFromAjax",
					dataType: "jsonp",
					data: {
						applicationId: 2,
						clientId: 'ORES2011',
						comment: '',
						vote: 0,
						ip: window.UserIp,
						userAgent: window.UserAgent,
						manualId: window.ManualId,
						device: window.DeviceName,
						isSatisfied: true,
						contentTitle: title
					},
					success: function(data) {
						$('.feedback .question').hide();
						$('.feedback .message').html("Gracias por usar las Guías de dispositivos. Esperamos haberte ayudado.").show();
					}
				});

				return false;
			});
			window.FormaTManuals = $.extend({}, window.FormaTManuals, FormaTManuals);
		});

		$(window).on('resize', function() {
			$('.worldmanuals-lightbox').trigger('close');

			if ($('#instruction-slider').length > 0) {
				initMobileSlider();
			}

			if (!isMobile()) {
				$('.block:first').trigger('click');
			}
		});

		if ($('#instruction-slider').length > 0) {
			initMobileSlider();
		}
	})(wmjQuery);

	function updateSteps(current, total) {
		var $ = wmjQuery;
		current = current || $('.instruction .blocks .block').index($('.instruction .blocks .block.active-line')) + 1;
		total = total || $('.instruction .blocks .block').length;
		var progress = (100 / total) * current;
		$('.progress-bar').stop(true, true).css({
			width: progress + "%"
		});
		$('.pagination .steps').html(current + " de " + total);

		$('.pagination a').removeClass('disabled');

		letsScroll();

		if (current == 1)
			$('.pagination .previous a[rel=pagination]').addClass('disabled');

		if (current == total)
			$('.pagination .next a[rel=pagination]').addClass('disabled');
	}

	function letsScroll() {
		var $ = wmjQuery;
		var offset = 230,
			$wrapper = $('.scroller-wrap'),
			$scroller = $('.scroller');

		if ($wrapper.height() < $scroller.height()) {
			var activeBlock = $('.block.active-line');
			var activeBlockTop = activeBlock.position().top;

			if (offset - activeBlockTop < 0) {
				$wrapper.addClass('scrolled');
				$scroller.css({
					'top': offset - activeBlockTop + "px"
				});
			} else {
				$wrapper.removeClass('scrolled');
				$scroller.css({
					'top': 0 + "px"
				});
			}
		}
	}

	function isMobile() {
		var $ = wmjQuery;
		return $(window).width() < 940;
	}

	function changeInstructionView() {
		var $ = wmjQuery;
		var view = $('.view');
		var instructions = view.find('.left:first');
		var highlights = view.find('.right:first');

		if (view.hasClass('alternative-view')) {
			instructions.hide();
			view.removeClass('alternative-view');

			highlights
				.removeClass('disable-scroll')
				.animate({
					'width': 320 + 'px'
				}, function() {
					instructions.show();
					letsScroll();
				});
			return;
		}

		instructions.fadeOut(function() {
			view.addClass('alternative-view');

			highlights
				.addClass('disable-scroll')
				.animate({
					'width': 924 + 'px'
				}, function() {
					instructions.show();
				});

		});
	}


	function initMobileSlider() {
		var $ = wmjQuery;
		var highlights = $('.block');
		var captionTmpl = $('<div class="active-block"></div>');
		var paginationTmpl = $('<div class="pagination"></div>');
		$('.swiper-wrapper').html("");
		$.each(highlights, function(i, obj) {
			$('.swiper-wrapper').append('<div class="swiper-slide"><div id="slide-' + i + '"></div></div>');
		});

	};
}
/** FIN VIRTUAL STEPS **/

/** INICIO MESSENGER CHAT **/

function cargarMessegerPage(){
	console.log("Cargando cargarMessegerPage().");
	param = getUrlVars();
	
	if(param.read !== undefined && param.read > 0){
		console.log("Detectando conversacion con id: "+param.read);
		localStorage.lastMessenger = param.read;
		cargarConversacionMessengerPageForId();
	}
	cargarLastChatsSidebar();
}

function cargarMasChatsPage(){
	console.log("Cargando cargarMasChatsPage()");
	
	$thisElement = $("#ankitjain28");
	$datos = $thisElement.data();
	if(!$datos.page){ $datos.page = 2; }else{ $datos.page = ($datos.page); };
	if(!$datos.limit){ $datos.limit = 10; };
	
	if($datos.page != undefined && $datos.limit != undefined){
		console.log("Cargando mas chats...");
		console.log($datos);
		
		FormaT.app("POST", "messenger", 
		{
			"list_chats":"true",
			"read_chat":"true",
			"page":$datos.page,
			"limit":$datos.limit,
			"conversation":localStorage.lastMessenger
		}, function(r){
			console.log(r);
			if(r.error === false){
				console.log("Cargando los nuevos chats");
				
					if(r.data.length > 0){
						
						$thisElement.data('page',$datos.page+1);
					
						for (i = 0; i < r.data.length; i++) {
							if($('#chat-id-'+r.data[i].id).length<=0){
								console.log("chat no exite, agregando");
							
								if(r.data[i].enviado_por.id == r.enviado_por.id){
									$(".chat-active-page").prepend(creatMensajeMy(r.data[i])); //append -> o -> prepend
								}else{
									$(".chat-active-page").prepend(creatMensajeUser(r.data[i]));					
								}
								if(i == (r.data.length-1)){
									if(window.location.hash !== undefined){ window.location.hash = ''; };
									
									idTempo = r.data.length-1;
								
									window.location.hash = "#chat-id-"+idTempo;
									//$("#chat-id-"+idTempo).focus();
								}
							}
						}
					}else{
						console.log("No hay mas chats");
						$.notify("No hay mas chats",'error');
					}
			}else{
				console.log("Error cargando mas chats");
			}
		})
	}
}

function cargarConversacionMessengerPageForId(){
	cargarLastChatsSidebar();
	
	console.log("Cargando coversacion messenger id: "+localStorage.lastMessenger);
	$("#text-response-send").data('sendid',localStorage.lastMessenger);
	
	
	FormaT.app("POST", "messenger", 
	{
		"list_chats":"true",
		"read_chat":"true",
		"conversation":localStorage.lastMessenger
	}, function(r){
		if(localStorage.lastMessengerResponse == JSON.stringify(r)){
			console.log("El mensaje no a cambiado.");
		}else{
			if(r.error === false){
				console.log(r);
				$para = new Array();
				for (c = 0; c < r.enviado_para.length; c++) {
					$para.push(r.enviado_para[c].nombre);
				}
				//console.log("Para: "+$para.join());
				$(".chat-to-page").text("Integrantes: "+$para.join());
				
				if(r.data.length>0){
					for (i = 0; i < r.data.length; i++) {
						if($('#chat-id-'+r.data[i].id).length<=0){
							console.log("chat no exite, agregando");
						
							if(r.data[i].enviado_por.id == r.enviado_por.id){
								$(".chat-active-page").append(creatMensajeMy(r.data[i])); //append -> o -> prepend
							}else{
								$(".chat-active-page").append(creatMensajeUser(r.data[i]));					
							}
							if(i == (r.data.length-1)){
								if(window.location.hash !== undefined){ window.location.hash = ''; };
								
								idTempo = r.data[i].id;
							
								window.location.hash = "#chat-id-"+idTempo;
								//$("#chat-id-"+idTempo).focus();
							}
						}
					}
				}else{
					$(".chat-active-page").html("No hay chats");
				}
				$('[data-toggle="tooltip"]').tooltip();

			}else{
				//console.log(r);
			}
		}
	});
}

/// Cargar Lista de amigos
function cargarPeopleList(){
	FormaT.app("POST", "messenger", 
	{
		"list_people":"true"
	}, function(r){
		if(r.error === false){
			$fechaActual = new Date(r.connection).getTime() / 1000;
			$totalSpan = 0;
			$html = '';
			$(".people-chat-list").html('');
			for (i = 0; i < r.friends.length; i++) {
				$totalSpan++;
				$active = false;
				$fechaUltimaConnection = new Date(r.friends[i].last_connection).getTime() / 1000;
				$timeS = ($fechaActual-$fechaUltimaConnection);
				if($fechaUltimaConnection>0){
					if($timeS<=(60*5)){
						$active = true;
					}
				}
				if($active == true){
					$activeColor = 'color:darkgreen;';
					$label = 'Conectado';
				}else{
					$activeColor = 'color:red;';
					$label = 'No Conectado';
				}
				
				
				$html += '<div id="list-people-id-'+r.friends[i].id+'" class="row sideBar-body list-group-item" onclick="javascript:cargarConversacionMessengerPage('+"'"+r.friends[i].id+"'"	+');" data-user="'+r.friends[i].user+'" data-name="'+r.friends[i].nombre.toLowerCase()+'">';
					$html += '<div class="col-sm-3 col-xs-3 sideBar-avatar">';
						$html += '<div class="avatar-icon">';
							$html += '<i title="'+$label+'" data-toggle="tooltip" data-placement="right" class="fa fa-circle" alt="'+r.friends[i].user+'" style="'+$activeColor+'"></i>';
							//$html += '<img src="https://bootdey.com/img/Content/avatar/avatar1.png">';
							
						$html += '</div>';
					$html += '</div>';
					$html += '<div class="col-sm-9 col-xs-9 sideBar-main">';
						$html += '<div class="row">';
							$html += '<div class="col-sm-8 col-xs-8 sideBar-name">';
							  $html += '<span class="name-meta name" title="'+r.friends[i].user+'" data-toggle="tooltip" data-placement="bottom">'+r.friends[i].nombre+'</span>';
							$html += '</div>';
							$html += '<div class="col-sm-4 col-xs-4 pull-right sideBar-time">';
								$html += '<span class="time-meta pull-right">'+r.friends[i].last_connection+'</span>';
							$html += '</div>';
						$html += '</div>';
					$html += '</div>';
				$html += '</div>';
			};
			
			$(".people-chat-list").prepend($html);
			
			$(".groups-list-messenger").html('');
			
			for (i = 0; i < r.viewGroups.length; i++) {
				$(".groups-list-messenger").append('<span style="font-size:0.7em;"> • '+r.viewGroups[i].name+'(s/es)</span> ');
			}
			
			
			$('[data-toggle="tooltip"]').tooltip();
		}else{
			console.log(r);
		}
	});
};

/// Cargar Lista de amigos
function cargarLastChatsSidebar(){
	FormaT.app("POST", "messenger", 
	{
		"last_chats":"true"
	}, function(r){
		console.log(r);
		if(r.error === false){
			//alert("Cargados los ultimos chats");
			if(r.data.length > 0){
				
				for (i = 0; i < r.data.length; i++) {
					createItemChatSideBarPageMessenger(r.data[i]);
				}
				
			}
		}else{
			console.log("No hay chats.");
		}
	});
};

function createItemChatSideBarPageMessenger(element){
	$para = new Array();
	for (c = 0; c < element.profiles.length; c++) { $para.push(element.profiles[c].nombre); };
	console.log($para.join());
	//$para.join();
	
	if(getUrlVars().read !== undefined && getUrlVars().read > 0 && getUrlVars().read == element.id){ $style = 'background-color: beige;'; }else{ $style = ''; };
	
	$ht = '<div id="last-chat-page-id-'+element.id+'" class="row sideBar-body" style="'+$style+'">';
		$ht += '<div class="col-sm-12 col-xs-12 sideBar-main">';
			$ht += '<div class="row">';
				$ht += '<a href="'+FormaT.options.site_url+'index.php?pageActive=messenger&read='+element.id+'&view=true">';
					$ht += '<div class="col-sm-8 col-xs-8 sideBar-name">';
						for (c = 0; c < element.profiles.length; c++) {
							if(element.profiles[c].id == FormaT.loadSession().authResponse.signedRequest.id){ element.profiles[c].nombre = 'Tu'; }
							$ht += '<span class="name-meta"><b>• '+element.profiles[c].nombre+'</b></span><br>';
						};
					$ht += '</div>';
					$ht += '<div class="col-sm-4 col-xs-4 pull-right sideBar-time">';
						$ht += '<span class="time-meta pull-right">'+element.last_activity+'</span>';
					$ht += '</div>';
				$ht += '</a>';
			$ht += '</div>';
		$ht += '</div>';
	$ht += '</div>';
	
	if($('#last-chat-page-id-'+element.id).length<=0){
		$(".last-chats-messenger").append($ht);
	}
	
	
}

/// Parse Chat Enviado <> Perfil actual
function creatMensajeUser($data){
	if($data.leerChat == true){ $color = 'alert-success'; }
	else { $color = ''; };
	
	$html = '<div class="row message-body" id="chat-id-'+$data.id+'">';
		$html += '<div class="col-sm-12 message-main-receiver">';
			$html += '<div class="receiver '+$color+'">';
				$html += '<div class="message-text">'+$data.enviado_por.user+' Dijo: </div>';
				$html += '<div class="message-text">'+$data.message+'</div>';
				$html += '<span class="message-time pull-right">'+$data.fcreate+'</span>';
			$html += '</div>';
		$html += '</div>';
	$html += '</div>';

	return $html;
};

/// Parse Chat Enviado = Perfil actual
function creatMensajeMy($data){
	$html = '<div class="row message-body" id="chat-id-'+$data.id+'">';
		$html += '<div class="col-sm-12 message-main-sender">';
			$html += '<div class="sender">';
				$html += '<div class="message-text">'+$data.message+'</div>';
				$html += '<span class="message-time pull-right">'+$data.fcreate+'</span>';
			$html += '</div>';
		$html += '</div>';
	$html += '</div>';
	
	return $html;
};

/// Cargar conversacion segun list ids de amigos (se verifica si existe una conversacion sino se crea una
function cargarConversacionMessengerPage(list){
	console.log(list);
	
	FormaT.app("POST", "messenger", 
	{
		"return":"id_conversacion",
		"list":list
	}, function(r){
		console.log(r);
		if(r.error === false){
			cargarChatMessengerPage(r.id);
		}else{
			$.notify("Hubo un problema cargardo la conversacion.","");
		}
	});
};

/// Cargar y activar chat
function cargarChatMessengerPage(idConversacion){
	location.replace('index.php?pageActive=messenger&read='+idConversacion);
	cargarConversacionMessengerPageForId();
};

/// Enviar Chat actual == Intro
function enviarMensajePageChat(){
	$mensaje_para = $("#text-response-send").data('sendid');
	$message = $("#text-response-send").val();
	
	if($mensaje_para == 0){
		$.notify("Selecciona a quien vas a enviar el mensaje.","error");
	}else{
		if($message == 0){
			$.notify("El mensaje está en blanco.","error");
		}else{
			FormaT.app("POST", "messenger", 
			{
				"chat_send":"true",
				"to":$mensaje_para,
				"message":$message
			}, function(r){
				console.log(r);
				if(r.error === false){
					$("#text-response-send").val("");
					
	
					cargarConversacionMessengerPageForId();
	
					
					$.notify("Mensaje enviado con éxito...", "success");
				}else{
					$.notify("Error enviado el mensaje...", "error");
				}
			});
		}
	}
};

/// Agregar usuario a la conversacion activa
function agregarUserConversacion(){
	$.notify("Cargando lista de amigos...","info");
	
	FormaT.app("POST", "messenger", 
	{
		"list_people":"true"
	}, function(r){
		console.log(r);
		if(r.error === false){
			$options = new Array();
			
			for (i = 0; i < r.friends.length; i++) {
				if($options[r.friends[i].id]){
				}else{
					arreglo = {};
					arreglo.value = r.friends[i].id;
					arreglo.text = r.friends[i].nombre+' [ '+r.friends[i].user+' ] ';
					
					$options.push(arreglo);
				};
			}
			
			bootbox.prompt({
				title: "This is a prompt with select!",
				inputType: 'select',
				inputOptions: $options,
				callback: function (result) {
					if(result>0){
						console.log(result);
						FormaT.app("POST", "messenger", 
						{
							"add_member":result,
							"conversation":localStorage.lastMessenger
						}, function(r){
							console.log(r);
							if(r.error === false){
								$.notify("Persona Agregada con exito!", "success");
								cargarConversacionMessengerPageForId();
								location.reload();
							}else{
								$.notify("Error agregando a la conversacion...", "error");
							}
						});
					}
				}
			});
		}else{
			console.log(r);
		}
	});
};

/** FIN MESSENGER CHAT **/

/** INICIO CRONOMETO **/
console.log("Cargando Funciones del Cronometro")
function initCronometro(){
	if(localStorage.cronometro && localStorage.cronometro != undefined && localStorage.cronometro != '' && localStorage.cronometro != ' ' && localStorage.cronometro != 0){
		continuarCronometro();
	}else{
		//console.log("No hay cronometro iniciado.");
	}
}

function detenerCronometro(){
	localStorage.setItem('cronometro', 0);
	localStorage.setItem("cronometro_time", 0);
	localStorage.setItem("cronometro_end", 0);
	min = 0;
	seg = 0;
	
	$(".cronometro-minutos").html(addZero(min));
	$(".cronometro-segundos").html(addZero(seg));
}

function PlayCronometro(minutes){
	var f = new Date();
	localStorage.setItem("cronometro", f);
	localStorage.setItem("cronometro_time", minutes);
	
	texta = f.getFullYear()+'-'+(f.getMonth()+1)+'-'+(f.getDate())+' '+f.getHours()+':'+(f.getMinutes()+minutes)+':'+f.getSeconds();
	
	localStorage.setItem("cronometro_end", new Date(texta));
	continuarCronometro();
}

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
};

function continuarCronometro(){
	if(localStorage.cronometro && localStorage.cronometro != undefined && localStorage.cronometro != '' && localStorage.cronometro != ' ' && localStorage.cronometro != 0){
		StopTimeCronometro = localStorage.cronometro_time;
		timeInicio = new Date(localStorage.cronometro);
		timeEnd = new Date(localStorage.cronometro_end);
		f_actual = new Date();
		f_actual2 = new Date(f_actual-timeInicio)
		
		//console.log(f_actual2.getMinutes());
		//console.log(f_actual2.getSeconds());
		//console.log(f_actual.getTime());
		
		if(f_actual2.getMinutes() == (StopTimeCronometro-1) && f_actual2.getSeconds()  == 50){
			AlertaDeTiempo1Minuto();
		}else if(f_actual2.getMinutes() >= (StopTimeCronometro) && f_actual2.getSeconds() == 0){
			minutos = 0;
			segundos = 0;
			detenerCronometro();
			PedirMasTiempoCronometro();
		}
		
		$(".cronometro-minutos").html(addZero(f_actual2.getMinutes()));
		$(".cronometro-segundos").html(addZero(f_actual2.getSeconds()));
	
	}
}

function openPopUp(url,options){
	var windowName = 'userConsole'+makeid();
	var popUp = open(url, windowName, options);
	if (popUp == null || typeof(popUp)=='undefined') {  
		$.notify('Por favor deshabilita el bloqueador de ventanas emergentes.','error');
	}else {  
		popUp.focus();
	}
}

function PedirMasTiempoCronometro(){
	var configuracion_ventana = "menubar=no,location=yes,resizable=n0,scrollbars=0,status=0,width=550,height=350";	
	openPopUp(FormaT.options.site_url+'index.php?pageActive=notifications&view=cronometro&action=end',configuracion_ventana);
}

function AlertaDeTiempo1Minuto(){
	var configuracion_ventana = "menubar=no,location=yes,resizable=n0,scrollbars=0,status=0,width=550,height=350";	
	openPopUp(FormaT.options.site_url+'index.php?pageActive=notifications&view=cronometro&action=timeAlerts&value=10',configuracion_ventana);
}

iniciarCronometro = setInterval(function() { initCronometro(); }, 1000);
/** FIN CRONOMETO **/



/** ready document ****/
$(document).ready( function() {
	$(".send-image-conversation").change(function(){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				console.log("Enviando Imagen por chat.");
				FormaT.app("POST", "pictures", 
				{
					"action":"create",
					"data":e.target.result
				}, function(r){
					if(r.error === false){
						FormaT.app("POST", "messenger", 
						{
							"chat_send":"true",
							"to":$("#text-response-send").data('sendid'),
							"message":'<img src="'+FormaT.options.api_url_large+'/pictures.php?accesstoken='+FormaT.AccessToken()+'&id='+r.last_id+'" width="100%" />'
						}, function(r){
							console.log(r);
							if(r.error === false){
								$("#text-response-send").val("");
								
								cargarConversacionMessengerPageForId();					
								$.notify("Mensaje enviado con éxito...", "success");
							}else{
								$.notify("Error enviado el mensaje...", "error");
							}
						});
					}else{
						$.notify("Error subiendo la imagen...", "error");
					}
				});
			}
			reader.readAsDataURL(this.files[0]);
		}
	});	
	
	
	/// Detectar Intro en el text del chat
	$( "#text-response-send" ).keypress(function(e) { if(e.key == 'Enter'){ enviarMensajePageChat(); } });
	
	
	if($("#sidebar").length > 0){
		
		$("#sidebar").mCustomScrollbar({
			theme: "minimal"
		});

		$('#sidebarCollapse').on('click', function () {
			$('#sidebar, #content').toggleClass('active');
			$('.collapse.in').toggleClass('in');
			$('a[aria-expanded=true]').attr('aria-expanded', 'false');
		});
	};
	optionsTooltips = {
		"delay": { "show": 200, "hide": 100 }
	};
	
	$( "img" ).tooltip(optionsTooltips);
	$('[data-toggle="tooltip"]').tooltip(optionsTooltips);
	
	
	$('[data-toggle="tooltip"]').click(function () {
		var _this = this;
		setTimeout(function () {
			$(_this).tooltip('destroy');
		}, 1000)
	})
		

	$( ".zoomImage" ).click(function() {
		var modal = document.getElementById('myModal');
		var modalImg = document.getElementById("img01");
		var captionText = document.getElementById("caption");
		modal.style.display = "block";
		modalImg.src = $(this)[0].src;
		captionText.innerHTML = $(this)[0].alt;

		var span = document.getElementsByClassName("close")[0];
		span.onclick = function() { 
			modal.style.display = "none";
		}
	});

	/// Cambiar imagen de perfil / avatar
	$(".change-my-avatar").change(function(){
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				FormaT.app("POST", "my", 
				{
					"action":"change",
					"avatar":true,
					"data":e.target.result
				}, function(r){
					if(r.error === false){
						imgId = r.id;
						$(".format-micuenta-link-profile").attr("src",FormaT.options.api_url_large+"/pictures.php?accesstoken="+FormaT.AccessToken()+"&id="+imgId);
						recargarSessionActiva();
						location.reload();
					}else{
						$.notify("Error subiendo la imagen...", "error");
					}
				});
			}
			reader.readAsDataURL(this.files[0]);
		}
	});
	
	/// Cambiar imagen en publish
	$("input[type='file'].change-image-publish").change(function(){
		$id = $( this ).data('id_ref')
		
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				FormaT.app("POST", "pictures", 
				{
					"action":"create",
					"data":e.target.result
				}, function(r){
					console.log(r);
					if(r.error === false){
						FormaT.app("POST", "publicaciones", {
							"action":"change",
							"id":$id,
							"thumbnail":r.id
						}, function(r2){
							console.log(r2);
							if(r2.error === false){
								$(".image-preview-publish").attr("src",FormaT.options.api_url_large+"/pictures.php?accesstoken="+FormaT.AccessToken()+"&id="+r.id)
								
								$.notify("Guardado...", "success");
							}else{
								$.notify("Error modificando la imagen de la publicacion...", "error");
							}
						});
						
					}else{
						$.notify("Error subiendo la imagen...", "error");
					}
				});
			}
			reader.readAsDataURL(this.files[0]);
		}
	});	
	
	
	$( "#search-bar-input" ).keypress(function(e) { searchIntro(); });
	
});
/** ready document ****/
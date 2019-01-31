<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="<?php echo url_site; ?>/api/plugins/bootstrap/3.3.7/css/bootstrap.min.css">
<!-- Fontawesome Icons 5.0.6 Version Free -->
<link rel="stylesheet" href="<?php echo url_site; ?>/api/plugins/glyphicons/css/bootstrap.icon-large.min.css">
<link rel="stylesheet" href="<?php echo url_site; ?>/api/plugins/fontawesome/5.0.6/css/fontawesome-all.min.css">
<!-- Our Style CSS -->
<link rel="stylesheet" href="<?php echo url_site; ?>/css/sidebar.css">

<link rel="stylesheet" href="<?php echo url_site; ?>/css/animate.css">
<link href="<?php echo url_site; ?>/api/plugins/jQuery.Gantt/css/style.css" type="text/css" rel="stylesheet">

<!-- jQuery Custom Scroller 3.1.5 -->
<link rel="stylesheet" href="<?php echo url_site; ?>/api/plugins/malihu-custom-scrollbar-plugin/3.1.5/css/jquery.mCustomScrollbar.min.css">



<!-- Scripts -->
<!-- jQuery 3.2.1 -->
<script src="<?php echo url_site; ?>/api/plugins/jquery/3.2.1/jquery-3.2.1.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo url_site; ?>/api/plugins/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo url_site; ?>/api/plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo url_site; ?>/api/plugins/notify/notify.js"></script>

<!-- PLUGINS -->
<script src="<?php echo url_site; ?>/api/plugins/pinterest_grid/pinterest_grid.js"></script>
<script src="<?php echo url_site; ?>/api/plugins/jQuery.Gantt/js/jquery.fn.gantt.min.js"></script>
<script src="<?php echo url_site; ?>/api/plugins/tinymce/tinymce.js"></script>

<script src="js/main.js"></script>

<!-- jQuery Custom Scroller 3.1.5 -->
<script src="<?php echo url_site; ?>/api/plugins/malihu-custom-scrollbar-plugin/3.1.5/js/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close" onclick='javascript:$("#myModal").modal("hide")();'>&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<!-- Modal edit categories -->
<div class="modal fade" id="modal-edit-categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<!-- <form method="post"> -->
				<div class="modal-header">
					<h5 class="modal-title" id="">Editar Categoria</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-name">Nombre</span>
						<input name="name" type="text" class="form-control" placeholder="Nombre / Titulo" aria-describedby="sizing-name" required="true">
					</div>						
					<div class="input-group">
						<span class="input-group-addon" id="sizing-message">Raiz</span>
						<select name="raiz" class="form-control" placeholder="Categoria Principal" required="true" aria-describedby="sizing-raiz" >
							<option value="">Seleccione...</option>
							<option value="0">Ninguna</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<input name="id" type="hidden" required="true">
					<input name="type" type="hidden" required="true">
					<a href="javascript:editCategoryFast();" class="btn btn-primary">Modificar</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			<!-- </form> -->
		</div>
	</div>
</div>

<!-- Modal Create categories -->
<div class="modal fade" id="modal-create-categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<!-- <form method="post"> -->
				<div class="modal-header">
					<h5 class="modal-title" id="">Crear Categoria</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-name">Nombre</span>
						<input name="name" type="text" class="form-control" placeholder="Nombre / Titulo" aria-describedby="sizing-name" required="true">
					</div>						
					<div class="input-group">
						<span class="input-group-addon" id="sizing-message">Raiz</span>
						<select name="raiz" class="form-control" placeholder="Categoria Principal" required="true" aria-describedby="sizing-raiz" >
							<option value="">Seleccione...</option>
							<option value="0">Ninguna</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<input name="type" type="hidden" required="true">
					<a href="javascript:createCategoryFast();" class="btn btn-primary">Crear</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			<!-- </form> -->
		</div>
	</div>
</div>

<!-- Modal Create alerts -->
<div class="modal fade" id="modal-create-alerts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="">Crear Alerta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-title">Titulo</span>
						<input name="title" type="text" class="form-control" placeholder="Titulo" aria-describedby="sizing-title" required="true">
					</div>						
					<div class="input-group">
						<span class="input-group-addon" id="sizing-message">Mensaje</span>
						<textarea name="message" type="text" class="form-control" placeholder="Mensaje" aria-describedby="sizing-message" required="true" rows="3"></textarea>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="sizing-ticket">Ticket</span>
						<input name="ticket" type="text" class="form-control" placeholder="Ticket" aria-describedby="sizing-ticket" required="true">
					</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:createAlert();" class="btn btn-primary">Publicar</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal Edit alerts -->
<div class="modal fade" id="modal-edit-alerts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<form method="post">
				<div class="modal-header">
					<h5 class="modal-title" id="">Modificar Alerta</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon" id="sizing-title">Titulo</span>
						<input name="title" type="text" class="form-control" placeholder="Titulo" aria-describedby="sizing-title" required="true">
					</div>						
					<div class="input-group">
						<span class="input-group-addon" id="sizing-message">Mensaje</span>
						<textarea name="message" type="text" class="form-control" placeholder="Mensaje" aria-describedby="sizing-message" required="true" rows="3"></textarea>
					</div>
					<div class="input-group">
						<span class="input-group-addon" id="sizing-ticket">Ticket</span>
						<input name="ticket" type="text" class="form-control" placeholder="Ticket" aria-describedby="sizing-ticket" required="true">
						<input name="id" type="hidden" required="true">
					</div>
				</div>
				<div class="modal-footer">
					<a href="javascript:editAlert();" class="btn btn-primary">Guardar</a>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal View Current Quiz -->
<div class="modal fade" id="modal-view-quiz-current" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			
			<div class="container">
				<div class="row"><h1 class="text-center">F5 FormaT</h1>
					<p class="text-center">Reliza el Quiz y continua navegando en FormaT!..</p>
				</div>

				
				<div class="row col-md-12" id="blue">
					<div class="col-md-10 col-md-offset-1">
						<div class="panel panel-primary coupon" role="form" method="POST">
							<div class="panel-heading" id="head">
								<div class="panel-title" id="title">
									<i class="fas fa-pencil-alt fa-2x"></i>
									<span class="hidden-xs quiz-current-title">  </span>
									<span class="visible-xs quiz-current-title">  </span>
								</div>
							</div>
							<div class="panel-body">
								<img src="images/logos/logo-350.gif" class="coupon-img img-rounded" width="100%">
								<hr>
								<div class="col-md-9">
									<div class="items quiz-current-items">
											
										<input name="response_quiz" value="" type="hidden" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="offer text-primary">
										<span class="usd"><sup>Total</sup></span>
										<span class="number quiz-current-total-querys"></span>
										<span class="cents"><sup>Preguntas</sup></span>
									</div>
								</div>
								<div class="col-md-12">
									<p class="disclosure">
										Bienvenido a la comunidad FormaT. Descubra el valor de un esfuerzo abierto colaborativo por uno de los equipos más grandes del mundo.
									</p>
									
									<a class="btn btn-sm btn-warning hideRun quiz-current-btn-edit" href="#"><i class="fas fa-edit"></i> Modificar</a>
								</div>
							</div>
							<div class="panel-footer">
								<div class="coupon-code">
									Quiz ID: F5-<span class="quiz-current-id"></span>
									<span class="print">
										
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
												<div class="pull-right">
													<button type="button" class="btn btn-primary" onclick="javascript:submitQuizCurrent();">Terminar</button>
												</div>
											</div>
										</div>
										
										<!-- <a href="#" class="btn btn-link"><i class="fa fa-lg fa-print"></i> Print Coupon</a> -->
									</span>
								</div>
								<div class="exp">Creado: <span class="quiz-current-fecha_creation"></span></div>
							</div>
						</div>
					</div>
				</div>
			</div>
				
		</div>
	</div>
</div>

<!-- Btns Float -->
<div id="inbox" class="hideRun">
	<div class="fab btn-group show-on-hover dropup">
		<div data-toggle="tooltip" data-placement="left" title="Publicar" style="margin-left: 42px;">
			<button type="button" class="btn btn-danger btn-io dropdown-toggle" data-toggle="dropdown">
				<span class="fa-stack fa-2x">
					<i class="fa-circle fa-stack-2x fab-backdrop"></i>
					<i class="fa fa-plus fa-stack-1x fa-inverse fab-primary"></i>
					<i class="fa fa-plus fa-stack-1x fa-inverse fab-secondary"></i>
				</span>
			</button>
		</div>
		<ul class="dropdown-menu dropdown-menu-right menu-btnfloat" role="menu" id="">
		</ul>
	</div>
</div>

<script>
	function statusChangeCallback(response) {
		console.log('statusChangeCallback');
		console.log(response);

		console.log(FormaT.options.site_url);
		console.log(FormaT.options.login_url);

		if(response.status === 'connected' || response === 'connected') {
			console.log('Session exite.');
			testAPI();
		} else {
			if(window.location.href != FormaT.options.login_url){ window.location.href = FormaT.options.login_url; };
			console.log('Session no exite.');
			document.getElementById('status').innerHTML = 'Por favor inicie sesión en esta aplicación.';
			FormaT.LogIn(function(response) {
				if (response.authResponse) {
					console.log('¡Bienvenido! Obteniendo tu información ...');
					console.log(response.authResponse.signedRequest.nombre);
					statusChangeCallback(response);
				} else {
					console.log('El usuario canceló el inicio de sesión o no autorizó completamente..');
					statusChangeCallback(response);
				}
			});
		}
	};

	function recargarSessionActiva(){
		FormaT.sessionRefresh(function(response) {
			if (response.authResponse) {
				console.log('Aun iniciada...');
				statusChangeCallback(response);
			} else {
				console.log('El usuario canceló el inicio de sesión o no autorizó completamente..');
				statusChangeCallback(response);
			}
		});
	}

	function checkLoginState() {
		FormaT.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	}

	window.f2AsyncInit = function() {
		FormaT.init({
			appName					:	'appName: FormaT',
			version					:	'v1.0',
			site_url				:	'<?php echo url_site; ?>/',
			login_url				:	'<?php echo url_site; ?>/login.php',
			api_url					:	'<?php echo url_site; ?>/api/',
			intervalAlerts			:	5, // Intervalo de ejecucion para Refresh Alerts
			timeRefresh				:	15000, // Intervalo de Refresh para Detectar alerts nuevas
			timeRefreshChatPage		:	30000, // Intervalo de Refresh para detectar los chats nuevos o no leidos (En notificacion)
			timeNotificationsClose	:	3000 // Tiempo de vida de las notificaciones

		});
		
		FormaT.getLoginStatus(function(response) {
			statusChangeCallback(response);
		});
	};

	// Cargue el SDK de forma asíncrona
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "<?php echo url_site; ?>/api/sdk/es_LA.js";
		console.log(js.src);
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'format-jssdk'));

	function testAPI() {
		if(window.location.href == FormaT.options.login_url){ window.location.href = FormaT.options.site_url; };
		console.log('¡Bienvenido! Obteniendo tu información ...');
		/** INICIO **/
			cargarDatosPerfil(); // Cargar Informacion del perfil actual
			$(".spinFormaT").hide(); // OCULTAR TODOS LOS SPINNERS
			$(".hideRun").hide(); // OCULTAR TODOS LOS SPINNERS
			cargarQuizCurrent(); // Cargar Último Quiz
			cargarSideBar(); // Cargar SideBar
			cargarNavBarTop(); // Cargar Navbar Top
			cargarBtnFloat(); // Cargar Btn Float
			if($(".notifications-page-view").length > 0){ cargarNotificacionPage(); }; // Detectar si existe una notificacion
			if($(".banner-carousel").length > 0){ cargarUltimasEcards(); }; // Detectar si existe un carousel y comenzar a cargarlo.
			if($(".pinBoot").length > 0){ cargarUltimasPublicaciones(); }; // Detectar si existe elemento pinBoot y comenzar la carga.
			if($(".pinBoot-devices").length > 0){ cargarDispositivosExplode(); }; // Detectar si existe elemento pinBoot de dispositivos y comenzar la carga.
			if($(".publish-page-view").length > 0){ cargarPublicacionPagina(); }; // Detectar si se está viendo las publicaciones
			if($(".device-topics-page-view").length > 0){ cargarTopicsDevicesPagina(); }; // Detectar si se está viendo los temas de los dispositivos
			if($(".device-manuals-page-view").length > 0){ cargarManualsDevicesPagina(); }; // Detectar si se está viendo un manual de los dispositivos
			if($(".forum-questions-view").length > 0){ cargarForumQuestionsPagina(); }; // Detectar si se está viendo el foro
			if($(".history-alerts-page-view").length > 0){ cargarHistoryAlertsPagina(); }; // Detectar si se está viendo los historicos de alertas
			if($(".history-publish-page-view").length > 0){ cargarHistoryPublishPagina(); }; // Detectar si se está viendo los historicos de publicaciones
			if($(".history-quiz-page-view").length > 0){ cargarHistoryQuizPagina(); }; // Detectar si se está viendo los historicos de Quiz
			if($(".draft-publish-page-view").length > 0){ cargarDraftsPublishPagina(); }; // Detectar si se está viendo los historicos de borradores de publicaciones
			if($(".draft-quiz-page-view").length > 0){ cargarDraftsQuizPagina(); }; // Detectar si se está viendo los historicos de borradores de Quiz
			if($(".current-quiz-page-view").length > 0){ cargarQuizCurrentPageResult(); }; // Detectar si se está viendo el resultado del ultimo Quiz
			if($(".quiz-page-edit").length > 0){ cargarQuizEditPage(); }; // Detectar si se está viendo el editor de Quiz
			if($(".messenger-chat-page-view").length > 0){ cargarMessegerPage(); 
				var refreshConversacionMessengerPage = setInterval(function() {
					cargarConversacionMessengerPageForId();
				}, 30000); //Tiempo de Refresh para chat activo
			}; // Detectar si se está viendo la pagina de Messenger Chat
			
			if($(".stadist-quiz-page-export").length > 0){ stadistQuizExportPage(); };
			if($(".import-page-view").length > 0){ cargarImportPage(); }; // Detectar si está en la página de importacion
		/** FIN **/
	};
</script>
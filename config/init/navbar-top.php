

<nav class="navbar navbar-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<ul class="nav navbar-nav navbar-right ">
			
				<!-- BTN DE MUNDIAL -->
				<li title="Mundial" data-toggle="tooltip" data-placement="bottom" class="hideRun item-mundial-navbartop"><a href="#" target="_blank" class="format-mundial-link animated rubberBand"><i class="fas fa-futbol"></i></a></li> 
				
				<!-- BTN DE FORO [Preguntas pendientes] -->
				<li title="Preguntas pendientes" data-toggle="tooltip" data-placement="bottom" class="dropdown note hideRun item-questions-response-navbartop">
					<a href="#" class="dropdown-toggle animated rubberBand" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-question-circle"></i> 
						<span class="badge total-questions-response-navbartop">0</span>
					</a>
					<ul class="dropdown-menu two question-pending-navbar">
						<li>
							<div class="notification_header">
								<h3 class="">Preguntas Pdtes Foro</h3>
							</div>
						</li>
						
						<li class="spin-questions-response-navbar"><a><i class="fas fa-spinner fa-spin" style="color:#000;"></i> Cargando</a></li>
						<div id="menu-questions-response-top"></div>
						
						
						<li>
							<div class="notification_bottom">
								<a href="#">Visitar Foro</a>
							</div>
						</li>
					</ul>
				</li>
				
				<li title="Mensajes Pendientes Por Leer" data-toggle="tooltip" data-placement="bottom" class="dropdown note hideRun item-messenger-pending-navbartop">
					<a href="#" class="dropdown-toggle animated rubberBand" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-envelope"></i> 
						<span class="badge total-messenger-pending-navbartop">0</span>
					</a>					
					<ul class="dropdown-menu two">
						<li>
							<div class="notification_header">
								<h3 class="">Conversaciones Pdtes</h3>
							</div>
						</li>
					
						<li class="spin-chat-messenger-navbar"><a><i class="fas fa-spinner fa-spin" style="color:#000;"></i> Cargando</a></li>
						<div id="menu-chat-top"></div>
						
						<li>
							<div class="notification_bottom">
								<a href="index.php?pageActive=messenger">Ver Todos los Chats</a>
							</div>
						</li>
					</ul>
				</li>
				
				
				<li title="Mis Indicadores" data-toggle="tooltip" data-placement="bottom" class="dropdown note hideRun item-indicators-navbartop">
					<a href="#" class="dropdown-toggle animated rubberBand" data-toggle="dropdown" aria-expanded="false">
						<i class="fas fa-tasks"></i>
						<span class="badge blue1 total-indicators-navbartop"></span>
					</a>	
					<ul class="dropdown-menu two">
						<li>
							<div class="notification_header">
								<h3 class="">Mis Indicadores</h3>
							</div>
						</li>
						<li class="spin-kpis-navbar"><a><i class="fas fa-spinner fa-spin" style="color:#000;"></i> Cargando</a></li>
						<div id="menu-kpis-top" style="padding:  0.5em;"></div>
						
						<li>
							<div class="notification_bottom">
								<a href="#"></a>
							</div>
						</li>
					</ul>
				</li>
			
				<li class="note hideRun item-stopwatch-navbartop">
					<div class="numbers-cronometro2">
						<font class="cronometro-minutos">00</font><font>:</font><font class="cronometro-segundos">00</font>
					</div>
				</li>
			
				<li title="Cronometro" data-toggle="tooltip" data-placement="bottom" class="dropdown note hideRun item-stopwatch-navbartop">
					<a href="#" class="dropdown-toggle animated rubberBand" data-toggle="dropdown" aria-expanded="false">
						<i class="fas fa-stopwatch"></i>
					</a>
					<ul class="dropdown-menu ">
						<li>
							<div class="notification_header">
								<h3>Cronometro</h3>
							</div>
						</li>
						<li>
							<a href="#">
								<div class="task-info">
									<div class="numbers-cronometro">
										<font class="cronometro-minutos">00</font>
										<font>:</font>
										<font class="cronometro-segundos">00</font>
									</div>
									<div class="clearfix"></div>
								</div>
							</a>
						</li>
						
						<li>
							<div class="notification_header">
								<a class="btn btn-xs btn-info" href="javascript:detenerCronometro();"><i class="fas fa-stop"></i> Parar </a>
								<a class="btn btn-xs btn-info" href="javascript:PlayCronometro(1);"><i class="fas fa-play"></i> 1 M </a>
								<a class="btn btn-xs btn-info" href="javascript:PlayCronometro(3);"><i class="fas fa-play"></i> 3 M </a>								
								<a class="btn btn-xs btn-info" href="javascript:PlayCronometro(5);"><i class="fas fa-play"></i> 5 M </a>
							</div>
						</li>
					</ul>
				</li>
			
				<li title="Alertas" data-toggle="tooltip" data-placement="bottom" class="dropdown note hideRun item-alerts-navbartop">
					<a href="#" class="dropdown-toggle animated rubberBand" data-toggle="dropdown" aria-expanded="false">
						<i class="far fa-bell"></i> 
						<span class="badge total-alerts-navbartop">0</span>
					</a>
					<ul class="dropdown-menu two">
						<li>
							<div class="notification_header">
								<h3 class="alerts-message-global">Fallas</h3>
							</div>
						</li>
						<li class="spin-alerts-navbar"><a><i class="fas fa-spinner fa-spin" style="color:#000;"></i> Cargando</a></li>
						<div id="menu-alerts-top">
							<?php
								/**
							if(isset($alerts[0])){
								foreach($alerts As $alert){
								?>
								<li id="alert-navbar-id-<?php echo $alert['id']; ?>">
									<a href="javascript:cargarAlertIndv(<?php echo $alert['id']; ?>)">
										<div class="user_img"><img src="images/icons/alarm.png" alt=""></div>
										<div class="notification_desc">
											<p><?php echo $alert['title']; ?></p>
											<p><span>Fecha: <?php echo $alert['fecha_apertura']; ?></span></p>
											<p><span>Ticket: <?php echo $alert['ticket']; ?></span></p>
										</div>
										<div class="clearfix"></div>
									</a>
								</li>
								<?php
								}
							}else{
								echo "<li>No hay novedades.</li>";
							}
							**/
							?>
						</div>
						<li>
							<div class="notification_bottom">
								<a href="#"></a>
							</div>
						</li>
					</ul>
				</li>
				
				
				<li title="Cerrar Sesion" data-toggle="tooltip" data-placement="bottom"><a href="javascript:FormaT.LogOut();	"><i class="glyphicon glyphicon-off"></i></a></li>
				<li id="sidebarCollapse" title="Esconder Menu" data-toggle="tooltip" data-placement="bottom">
					<a href="#"><i class="glyphicon glyphicon-option-vertical"></i></a>
				</li>
			</ul>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<div class="navbar-left">
				<div class="note form-inline animated rubberBand" style="width:225px;list-style:none;float:left;">
					<form action="javascript:searchIntro();" method="search" style="width:100%;float:left;" onSubmit="javascript: searchIntro();">
					  <div class="form-group" style="">
						<a class="input-group" style="padding:5px;width:7%;float:left;">
							<button onclick="javascript:searchIntro();" type="submit" class="btn btn-sm btn-info"  style="border: none;position: absolute;float:right;width:55px;border-radius:50%;margin:0;margin:calc(-5px);height:55px;/*background-color:rgba(34, 63, 153, 0.8);*/margin-left: 0;z-index:99;border: solid 3px #FFF;"><i class="fas fa-search"></i></button>
						</a>
						<div class="input-group" style="padding:5px;width:93%;float:right;left:16%;">
							<input id="search-bar-input" list="search-bar-results" autocomplete="off" required="" type="text" class="form-control search-input" placeholder="¿Que estas buscando?" name="q" style="border: none;float:left;width:100%;border-radius:30px;margin:0;padding:0px;padding-left:25px;background-color:rgba(34, 63, 153,1);height:calc(40px);color:#FFF;margin-left: -10px;">
						</div>
					  </div>
					</form>
				</div>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<!-- <li><a href="#">Page</a></li> -->
			</ul>
		</div>
	</div>
</nav>

<ul id="search-bar-results" class="main-search"style="display:none"></ul>

<script type="text/javascript">
	$('.main-search').hide();
	
	$('div').click(function(){
		if($('.main-search').is(":visible") == true){
			$('.main-search').hide();
		}
	});
</script>
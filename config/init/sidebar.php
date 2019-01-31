<nav id="sidebar">
	<div class="sidebar-header">
		<!-- .: <?php echo site_name_md; ?> :. -->
		<h3 style="text-align:center;">
			<a href="<?php echo url_site; ?>"><img class="" src="images/logos/logo.png" style="width:100%;" /></a>
		</h3>
	</div>
	<div class="sidebar-profile-box">
		<div class="container-beta">
			<div class="container-beta">
				<div class="format-micuenta-avatar-url image-beta-1"></div>
			</div> 
			<div class="overlay-beta">
				<div class="text-beta">					
					<span class="btn-file-image" data-toggle="tooltip" title="Cambiar Imagen">
						<i class="fa fa-camera"></i> <input type="file" accept="image/*" class="imgInp change-my-avatar">
					</span>
				</div>
			</div>
		</div>		
		<a class="format-micuenta-link-profile" href="#" title="Visitar mi perfil" data-toggle="tooltip" data-placement="top">
			<span class="format-micuenta-nombre">nombre</span>
		</a>
		<br><span class="format-micuenta-cargo-name" title="Cargo" data-toggle="tooltip"></span>
		<br><span class="format-micuenta-rol-name" title="Rol" data-toggle="tooltip"></span>
		<!-- -->
		<ul class="list-unstyled">
			<li><a class="format-micuenta-link-profile" href="#"><span>Mi Perfil</span><i class="lnr lnr-user"></i></a></li>
			<li><a class="tooltips" href="#"><span>Optiones</span><i class="lnr lnr-cog"></i></a></li>
			<li><a class="tooltips" href="javascript:FormaT.LogOut();"><span>Salir</span><i class="lnr lnr-power-switch"></i></a></li>
		</ul>
	</div>
	<ul class="list-unstyled components" id="">
		<p class="format-micuenta-piloto-name" title="Piloto" data-toggle="tooltip"></p>
		<div id="menu-sidebar">
		</div>
		<?php 	
		
			/**
			if(ecardsEnable() == true){
				?>
				<li>
					<a href="#bannerSubmenu" data-toggle="collapse" aria-expanded="false">
						<i class="glyphicon glyphicon-blackboard"></i>
						Info Banner
					</a>
					<ul class="collapse list-unstyled" id="bannerSubmenu">
						<?php
							$cats_ecards = cargarCategorias($_SESSION['piloto'],"ecards",0);
							if(isset($cats_ecards[0]['name'])){
								foreach($cats_ecards As $elCat0){
									echo parseCatsSidebarLI($elCat0);
								}
							}
							if(ecardsCategoriesCreateEnable() == true){
								?>
								<li>
									<a href="javascript:dialogCreateCategoryFast('ecards');">
										<i class="glyphicon glyphicon-plus"></i>
										Nueva Categoria
									</a>
								</li>
								<?php
							};
						?>
					</ul>
				</li>
				<?php
			};
			
			if(articlesEnable() == true){ ?>
				<li>
					<a href="#articlesSubmenu" data-toggle="collapse" aria-expanded="false">
						<i class="glyphicon glyphicon-book"></i>
						Top Semanal
					</a>
					<ul class="collapse list-unstyled" id="articlesSubmenu">
						<?php
							$cats_articles = cargarCategorias($_SESSION['piloto'],"articles",0);
							if(isset($cats_articles[0]['name'])){
								foreach($cats_articles As $elCat0){
									echo parseCatsSidebarLI($elCat0);
								}
							}
							if(articlesCategoriesCreateEnable() == true){
								?>
								<li>
									<a href="javascript:dialogCreateCategoryFast('articles');">
										<i class="glyphicon glyphicon-plus"></i>
										Nueva Categoria
									</a>
								</li>
								<?php
							};
						?>
					</ul>
				</li>
				<?php
			}; 
			if(forumEnable() == true){
				?>
				<li>
					<a href="#forumSubmenu" data-toggle="collapse" aria-expanded="false">
						<i class="fas fa-question-circle"></i>
						Foro
					</a>
					<ul class="collapse list-unstyled" id="forumSubmenu">
						<?php
							$cats_articles = cargarCategorias($_SESSION['piloto'],"forum",0);
							if(isset($cats_articles[0]['name'])){
								foreach($cats_articles As $elCat0){
									echo parseCatsSidebarLI($elCat0);
								}
							};
							if(forumCategoriesCreateEnable() == true){
								?>
								<li>
									<a href="javascript:dialogCreateCategoryFast('forum');">
										<i class="glyphicon glyphicon-plus"></i>
										Nueva Categoria
									</a>
								</li>
								<?php
							};
							?>
						</ul>
					</li>
					<?php
			}; 
		?>
		<?php 
			if(devicesEnable() == true){
				$plataformas = cargarPlataformas();
				?>
				<li>
					<a href="#simulatorsSubmenu" data-toggle="collapse" aria-expanded="false">
						<i class="fas fa-laptop"></i>
						Simuladores
					</a>
					<ul class="collapse list-unstyled" id="simulatorsSubmenu">
						<?php				
						if(isset($plataformas[0])){
							foreach($plataformas as $plat){
								?>
							<li>
								<a href="#pageSubmenu-<?php echo $plat['id']; ?>" data-toggle="collapse" aria-expanded="false">
									<i class="<?php echo $plat['icon']; ?>"></i>
									<?php echo $plat['name']; ?>
								</a>
								<ul class="collapse list-unstyled" id="pageSubmenu-<?php echo $plat['id']; ?>">
									<?php 
									$marcas = cargarMarcasForPlataformaId($plat['id']);
									//echo json_encode($marcas);
									if(isset($marcas[0])){ ?>
										<?php foreach($marcas as $mrc){ ?>											
											<li>
												<a href="index.php?pageActive=devices&device_type=<?php echo $plat['id']; ?>&device_manufacturer=<?php echo $mrc['id']; ?>">
													<i class="<?php echo $plat['icon']; ?>"></i>
													<?php echo $mrc['name']; ?>
												</a>
											</li>
										<?php } ?>
									<?php }else{
										?>									
										<li>
											<a href="#">
												<i class="glyphicon glyphicon-pencil"></i>
												Ninguna
											</a>
										</li>
										<?php 
										} ?>
								</ul>
							</li>
							
								
								
								<?php 
							}
						}else{
							?>									
							<li>
								<a href="#">
									<i class="glyphicon glyphicon-pencil"></i>
									Ninguna
								</a>
							</li>
							<?php 
							}
						?>
					</ul>
				</li>
				<?php
			};
			
			if(calendaryEnable() == true){ ?>
				<li>
					<a href="#caledarCapas" data-toggle="collapse" aria-expanded="false">
						<i class="fas fa-calendar"></i>
						Capacitaciones
					</a>
					<ul class="collapse list-unstyled" id="caledarCapas">
						<?php
						$cats_capas = cargarCategorias($_SESSION['piloto'],"capas",0);
						if(isset($cats_capas[0]['name'])){
							foreach($cats_capas As $elCat0){
								echo parseCatsSidebarLI($elCat0);
							}
						}
						
						if(calendaryEditEnable() == true){
							?>
							<li>
								<a href="javascript:dialogCreateCategoryFast('capas');">
									<i class="glyphicon glyphicon-plus"></i>
									Nueva Categoria
								</a>
							</li>
							<?php
						};

						?>
					</ul>
				</li>
				<?php
			}; 
			
			if(alertsEditEnable() == true || alertsDeleteEnable() == true || alertsHistoryEnable() == true){
				?>
				<li>
					<a href="#manage-alerts" data-toggle="collapse" aria-expanded="false">
						<i class="fas fa-bell"></i>
						Gestionar Alertas
					</a>
					<ul class="collapse list-unstyled" id="manage-alerts">
						<?php if(ecardsCreateEnable() == true){ ?>
							<li><a href="javascript:$('#modal-create-alerts').modal('show');"><i class="fas fa-plus"></i> Crear</a></li>
						<?php } ?>
						<?php if(alertsEditEnable() == true || alertsDeleteEnable() == true){ 
							$alerts = cargarAlertsActivas($_SESSION['piloto']);
							if(isset($alerts[0])){
								foreach($alerts As $alert){
								?>
								<li>
									<a>
										<span onclick="javascript:deleteAlert(<?php echo $alert['id']; ?>);"><i class="fas fa-ban"></i></span> 
										
										<span onclick="javascript:openEditAlert(<?php echo $alert['id']; ?>);"><i class="fas fa-pencil-alt"></i></span>
										<span onclick="javascript:cargarAlertIndv(<?php echo $alert['id']; ?>);"><?php echo $alert['title']; ?> [<?php echo $alert['ticket']; ?>]</span>
									</a>
								</li>
								<?php
								}
							}
							?>
							
						<?php } ?>
					</ul>
				</li>
		
				<?php
			}; 
		?>
		
		<?php if(alertsHistoryEnable() == true || ecardsHistoryEnable() == true || articlesHistoryEnable() == true){ ?>
			<li>
				<a href="#manage-historys" data-toggle="collapse" aria-expanded="false">
					<i class="fas fa-history"></i>
					Historicos
				</a>
				<ul class="collapse list-unstyled" id="manage-historys">
					<?php if(alertsHistoryEnable() == true){ ?>
						<li><a href="index.php?pageActive=history-alerts"><i class="fas fa-bell"></i> Historial de alertas</a></li>
					<?php } ?>

					<?php if(ecardsHistoryEnable() == true){ ?>
						<li><a href="index.php?pageActive=history-publish&type=ecards"><i class="glyphicon glyphicon-blackboard"></i> Historial de Banner</a></li>
					<?php } ?>
					<?php if(articlesHistoryEnable() == true){ ?>
						<li><a href="index.php?pageActive=history-publish&type=articles"><i class="glyphicon glyphicon-book"></i> Historial de Top Semanal</a></li>
					<?php } ?>
					<?php if(articlesHistoryEnable() == true){ ?>
						<li><a href="index.php?pageActive=history-quiz"><i class="fas fa-graduation-cap"></i> Historial de Evaluaciones</a></li>
					<?php } ?>
				</ul>
			</li>
		<?php }; ?>
		<?php if(importPeopleEnable() == true){ ?>
			<li class="">
				<a href="#importSubmenu" data-toggle="collapse" aria-expanded="false">
					<i class="fas fa-upload"></i>
					Importar
				</a>
				<ul class="collapse list-unstyled" id="importSubmenu">
					<?php if(importPeopleEnable() == true){ ?>
						<li><a href="index.php?pageActive=import-people"> <i class="fas fa-users"></i> Personal</a></li>
					<?php }; ?>
				</ul>
			</li>
		<?php 
			};
			if(exportQuizEnable() == true){ ?>
			<li class="">
				<a href="#exporttSubmenu" data-toggle="collapse" aria-expanded="false">
					<i class="fas fa-upload"></i>
					Exportar
				</a>
				<ul class="collapse list-unstyled" id="exporttSubmenu">
					<?php if(exportQuizEnable() == true){ ?>
						<li><a href="index.php?pageActive=export-quiz"> <i class="fas fa-users"></i> Último Quiz</a></li>
					<?php }; ?>
				</ul>
			</li>
		<?php }; 
			**/
		?>
		
		
		<!-- 
		<li class="active">
			<a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">
				<i class="glyphicon glyphicon-home"></i>
				Home
			</a>
			<ul class="collapse list-unstyled" id="homeSubmenu">
				<li><a href="#">Home 1</a></li>
				<li><a href="#">Home 2</a></li>
				<li><a href="#">Home 3</a></li>
			</ul>
		</li>
		<li>
			<a href="#">
				<i class="glyphicon glyphicon-briefcase"></i>
				About
			</a>
		</li>
		<li>
			<a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">
				<i class="glyphicon glyphicon-duplicate"></i>
				Pages
			</a>
			<ul class="collapse list-unstyled" id="pageSubmenu">
				<li><a href="#">Page 1</a></li>
				<li><a href="#">Page 2</a></li>
				<li><a href="#">Page 3</a></li>
			</ul>
		</li>
		<li>
			<a href="#">
				<i class="glyphicon glyphicon-link"></i>
				Portfolio
			</a>
		</li>
		<li>
			<a href="#">
				<i class="glyphicon glyphicon-paperclip"></i>
				FAQ
			</a>
		</li>
		<li>
			<a href="#">
				<i class="glyphicon glyphicon-send"></i>
				Contact
			</a>
		</li>
		-->
	</ul>
	

	<ul class="list-unstyled CTAs">
		<li>
			<a href="https://web.emtelco.co/ZonaE/" class="download" target="_blank">
				<img width="100%" src="images/logos/logo-emtelco-_cxbpo.png" />
			</a>
		</li>
	</ul>
</nav>

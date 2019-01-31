<?php require_once("config/autoload.php"); ?>
<!Doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<?php include('config/init/head-top.php'); ?>
		<?php include('config/init/head-bottom.php'); ?>
    </head>
    <body>
		<?php 
			
				include('config/init/sidebar.php');
				include('config/init/navbar-top.php');
				?>
				<div class="wrapper">			
					<div id="content">
						<div id="page" class="">
							<?php 
							
								if(pageActive() == false || pageActive() == 'index'){
									include('config/docs/site/pages/feeds.php');
								}else{
									
									$file = pageActive();
									$file = "config/docs/site/pages/{$file}.php";
									
									if(file_exists($file)){
										include($file);
									}else{
										include('config/docs/site/errors/404.php');
									}
								}
							?>
							<div class="clearfix"></div>
						</div>
						<?php include('config/init/footer.php'); ?>
					</div>
				</div>
				<?php include('config/init/scripts.php'); ?>
				
				<?php 
		?>
		
    </body>
</html>

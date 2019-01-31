<?php
	$device_id = $_GET['device_id'];
	$virtualsteps_id = $_GET['ref_id'];
	
	$sql_virtualsteps_general = datosSQL("Select * from ".TBL_DV_VIRTUALSTEPS." where trash='0' and id IN ({$virtualsteps_id}) ");
	if(isset($sql_virtualsteps_general->error) && $sql_virtualsteps_general->error == false){
		$virtualsteps_general = $sql_virtualsteps_general->data[0];
	}else{ $virtualsteps_general = array(); }
	$virtualsteps_name = $virtualsteps_general['name'];
	
	
	$sql_devices_general = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where trash='0' and id IN ({$device_id})  ");
	if(isset($sql_devices_general->error) && $sql_devices_general->error == false){
		$devices_general = $sql_devices_general->data[0];
	}else{ $devices_general = array(); }
	$devices_name = $devices_general['name'];
	$manufacturer_id = $devices_general['manufacturer'];
	$devices_general['size'] = json_decode($devices_general['size']);
	
	$sql_manufacturer_general = datosSQL("Select * from ".TBL_DV_MARCAS." where trash='0' and id IN ({$manufacturer_id}) ");
	if(isset($sql_manufacturer_general->error) && $sql_manufacturer_general->error == false){
		$manufacturer_general = $sql_manufacturer_general->data[0];
	}else{ $manufacturer_general = array(); }
	$manufacturer_name = $manufacturer_general['name'];
	$plataforma_id = $manufacturer_general['type'];
	$plataforma_name = namePlataformaById($plataforma_id);
	$plataforma_image = picturePlataformaById($plataforma_id);
	
	if(!isset($devices_general['size']->maxWidth)){ $devices_general['size']->maxWidth = $devices_general['size']->width; }
	if(!isset($devices_general['size']->maxHeight)){ $devices_general['size']->maxHeight = $devices_general['size']->height; }
?>

<link rel="stylesheet" href="<?php echo url_site; ?>/dist/virtualsteps/css/styles.min.css">
<script src="<?php echo url_site; ?>/dist/virtualsteps/js/jquery.min.js"></script>
<script>
    FormaTManuals = {
        Resources: {
            title_prefix: '',
            title_suffix: ' - FormaT'
        },
        HighlightOptions: {
            maxWidth: <?php echo $devices_general['size']->maxWidth; ?>,
            maxHeight: <?php echo $devices_general['size']->maxHeight; ?>,
            //cdn: '<?php echo url_api; ?>v1/pictures.php?accesstoken=',
            cdn: 'api/v1/pictures.php?accesstoken=',
            api_url: '<?php echo url_api; ?>',
			masterImage: {
				name: <?php echo $devices_general['image_icon']; ?>,
				width: '<?php echo $devices_general['size']->width; ?>',
				height: '<?php echo $devices_general['size']->height; ?>',
				<?php if(isset($devices_general['size']->screenPositionLeft)){ echo "screenPositionLeft: '".$devices_general['size']->screenPositionLeft."',"; } ?>
				<?php if(isset($devices_general['size']->screenPositionTop)){ echo "screenPositionTop: '".$devices_general['size']->screenPositionTop."',"; } ?>
				<?php if(isset($devices_general['size']->screenHeight)){ echo "screenHeight: '".$devices_general['size']->screenHeight."',"; } ?>
				<?php if(isset($devices_general['size']->screenWidth)){ echo "screenWidth: '".$devices_general['size']->screenWidth."'"; } ?>
			}
			
			//+'ss'+'&out_type=jpg&id='
        },
        //ApplicationType: '0',
        //DeviceId: 'F-8231',
        //DeviceSlug: 'samsung-galaxy-s8-android-7-0'
    }
	/**
	{
		width: '602',
		height: '1200',
		screenPositionLeft: '38',
		screenPositionTop: '137',
		screenHeight: '924',
		screenWidth: '519'
	}
	**/
</script>


<div class="sub-heard-part">
	<ol class="breadcrumb m-b-0">
		<li><a href="<?php echo url_site; ?>/index.php?pageActive=devices&device_type=<?php echo $plataforma_id; ?>&device_manufacturer=<?php echo $manufacturer_id; ?>"><?php echo $manufacturer_name; ?></a></li>
		<li><a href="<?php echo url_site; ?>/index.php?pageActive=devices&device_id=<?php echo $device_id; ?>"><?php echo $devices_name; ?></a></li>
		<li class="active"><?php echo $virtualsteps_general['name']; ?></li>
	</ol>
</div>
<div class="">
	<div class="container">
		<div class="row">
			
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

				<div class="row row-eq-height" style="padding-right:15px">
					<div class="col-xs-12 text">
						<div class="worldmanuals" id="worldmanuals">
							<div class="instruction-box box">
								<div class="hidden-mobile">
									<div class="view">
										<div class="row-fluid">
											<div class="col-sm-12 col-md-8 col-lg-8">
												<div class="device-image animated fadeInDown">
													<div class="hidden-mobile">
														<div class="highlights" id="highlights-device" style="zoom: 90%;/** max-height:450px;max-width:100%; **/">
															
														</div>
													</div>						
													<ul class="pagination" style="display: none;">
														<li class="previous">
															<a href="#" rel="pagination" class="wm-btn black"></a>
														</li>
														<li class="next">
															<a href="#" rel="pagination" class="wm-btn black"></a>
														</li>
													</ul>
													
												</div>
											</div>
											<div class="right_ot col-sm-12 col-md-4 col-lg-4 animated fadeInRight">
												<header>
													<h2>¿Como se hace?</h2>

												</header>
												<p>
													<!--
													<strong>
														La función de Wi-Fi se puede utilizar como alternativa a la red móvil para conectarse a internet. Al activar la función de Wi-Fi, el móvil no utiliza datos móviles.
													</strong> -->
												</p>
												<div class="border">
													<div class="instruction-progress">
														<ul class="pagination">
															<li class="previous">
																<a href="#" rel="pagination" class="wm-btn black">
																	Anterior
																</a>
															</li>
															<li class="steps"></li>
															<li class="next">
																<a href="#" rel="pagination" class="wm-btn black">
																	Siguiente
																</a>
															</li>
														</ul>
														<div class="progress">
															<div class="progress-bar" role="progressbar" style="width: 0%;"></div>
														</div>
													</div>
													<?php #echo $virtualsteps_general['instructions']; ?>
													<div class="scroller-wrap">
														<div class="scroller">
															<article class="instruction">
																<?php 
																	$virtualsteps_general['instructions'] = json_decode($virtualsteps_general['instructions']);
																	foreach($virtualsteps_general['instructions'] As $lement){
																		?>
																		<div class="blocks">
																			<h3><?php echo $lement->title; ?></h3>
																			<?php foreach($lement->steps As $steps){ ?>
																			<div class="block" id="<?php echo $steps->id; ?>">
																				<?php foreach($steps->points As $points){ ?>
																				
																				<span 
																					class="<?php echo $points->class; ?>"  
																					data-display="<?php echo $points->display; ?>" 
																					data-display-width="<?php echo $points->displayWidth; ?>" 
																					data-display-height="<?php echo $points->displayHeight; ?>" 
																					data-pointer-speed="<?php echo $points->pointerSpeed; ?>" 
																					data-pointer-frames="<?php echo $points->pointerFrames; ?>" 
																					data-pointer-width="<?php echo $points->pointerWidth; ?>" 
																					data-pointer-height="<?php echo $points->pointerHeight; ?>" 
																					data-top="<?php echo $points->top; ?>" 
																					data-left="<?php echo $points->left; ?>" 
																					data-orientation="<?php echo $points->orientation; ?>" 
																					data-pointer-type="<?php echo $points->pointerType; ?>" 
																					data-pointer="<?php echo $points->pointer; ?>" 
																					data-pointer-top="<?php echo $points->pointerTop; ?>" 
																					data-pointer-left="<?php echo $points->pointerLeft; ?>"
																				><?php echo $steps->text; ?></span>
																				<?php } ?>
																			</div>
																			<?php } ?>
																		</div>
																		<?php
																	}
																?>
															</article>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
								<div class="hidden-desktop">
									<h2>
										Cómo conectarse a una red Wi-Fi
									</h2>
									<div id="instruction-slider">
										<div class="swiper-container">
											<div class="swiper-wrapper">
												<div class="swiper-slide"></div>
											</div>
											<div class="swiper-button-prev"></div>
											<div class="swiper-button-next"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<style>

.header{position:relative;overflow:hidden;max-height:400px;display:flex;align-items:center;justify-content:center}
.text{
    /*-webkit-column-count: 2; 
    -moz-column-count: 2; 
    column-count: 2; */  
    margin-top:15px;        
}
.statistics > p{margin-bottom:2px;}
.statistics > p > span.label{background-color:white;color:gray;}
.side{background:#fafafa;padding-top:15px}
.side > img { margin-bottom:15px;}
.semi-title{font-weight: bold;margin-top:30px;}
.title{    
    position: absolute;
    bottom: 45px;
    padding: 7px;
    right: 25px;
    padding-left: 25px;
    padding-right: 30px;
    color: white;
    background: rgba(0,0,0,0.5);
}
.sub-title{    
    position: absolute;
    bottom: 94px;
    padding: 7px;
    right: 25px;
    padding-left: 12px;
    padding-right: 12px;
    color: orange;
    background: rgba(0,0,0,0.7);
}        
.name-author{
    position: absolute;
    bottom: 35px;
    left: 100px;
    font-size: 11px;
    color: white;
    background: black;
    padding: 2px;
    padding-right: 10px;
    padding-left: 22px;
    margin-left: -21px;
    z-index: 1;
    font-weight: 500;            
}
.photo-author{
    max-height: 70px;
    padding: 2px;
    position: absolute;
    left: 25px;
    bottom: 25px;
    background: white;
    z-index: 3;            
}
.triangulo{
    position:absolute;
    bottom:0px;
    left:0px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 60px 0 0 1200px;
    border-color: transparent transparent transparent #ffffff;
}
.row-eq-height {
display: -webkit-box;
display: -webkit-flex;
display: -ms-flexbox;
display: flex;
}   

@media (max-width: 426px) {
    .header{
            margin-left: -15px;
            margin-top: -15px;
            margin-right: -15px;
    }
    .title{
        font-size:15px;
        bottom:-12px;
        right:0px;
        padding-left:10px;
        padding-right:10px;
    }
    .photo-author{
        max-height:45px;
        left:5px;
        bottom:40px;
    }
    .name-author{
        font-size:9px;
        margin-left:-63px;
        bottom:44px;
    }
    .sub-title{
        right:0px;
        bottom:18px;
        padding:5px;
        font-size:10px;
    }
}
</style>

<script src="<?php echo url_site; ?>/dist/virtualsteps/js/actions-0.0.2.js"></script>
<script type="text/javascript">var wmjQuery = jQuery.noConflict(true);</script>
<script type="text/javascript">
	$(document).ready( function() {
		//$("#highlights-device").focus()
		//window.location.hash = '#highlights-device';
		location.hash = '#highlights-device';
	});
</script>	
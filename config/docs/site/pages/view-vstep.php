<link rel="stylesheet" href="<?php echo url_site; ?>/api/plugins/virtualsteps/css/styles.min.css">

<div class="sub-heard-part">
	<ol class="breadcrumb m-b-0">
		<li><a href="#" class="manufacturer-name manufacturer-link">manufacturer</a></li>
		<li><a href="index.php?pageActive=devices&device_id={device_id}" class="device-name device-link">devices_name</a></li>
		<li class="active vsteps-title">Titulo Manual</li>
	</ol>
</div>

<div class="device-manuals-page-view">
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
														<div class="highlights" id="highlights-device" style="zoom: 90%;/** max-height:450px;max-width:100%; **/"></div>
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
														Wi-Fi, el móvil no utiliza datos móviles.
													</strong> 
												-->
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
													
													<div class="scroller-wrap">
														<div class="scroller">
															<article class="instruction">
																
															</article>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
								<div class="hidden-desktop">
									<h2 class="vsteps-title"></h2>
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

	

<script src="<?php echo url_site; ?>/api/plugins/virtualsteps/js/jquery.min.js"></script>
<script>
</script>

<script src="<?php echo url_site; ?>/api/plugins/virtualsteps/js/actions-0.0.2.js"></script>
<script type="text/javascript">var wmjQuery = jQuery.noConflict(true);</script>
<script type="text/javascript">
	$(document).ready( function() {
		//$("#highlights-device").focus()
		//window.location.hash = '#highlights-device';
		location.hash = '#highlights-device';
	});
</script>
	

<div class="publish-page-view">
	<div class="container">
		<div class="row">
			 <!-- HEADER -->
			<div class="header">
				<img class="zoomImage picture-publish image-preview-publish" style="width:100%" src="images/wallpapers/default.png" />
				<div class="triangulo"></div>
				<div class="profile">
					<img class="photo-author img-circle zoomImage" src="images/avatars/default.jpg" />
					<span class="name-author">por Autor del contenido</span>
				</div>
				<h5 class="sub-title">
					<font class="plublish-category">Categoria</font>
					<a class="btn-edit-publish btn-edit-category" href="#"><i class="fas fa-pen-square"></i></a>
				</h5>
				
				<h2 class="title">
					<font class="title-edit" data-toggle="tooltip" title="Cambiar Titulo">Titulo</font>
					<span class="btn btn-sm btn-file-image" data-toggle="tooltip" title="Subir Imagen">
						<i class="far fa-image fa-2x" aria-hidden="true"></i> <input type="file" accept="image/*" class="change-image-publish" data-id_ref="<?php echo $_GET['id_ref']; ?>">
					</span>
				</h2>
				
				
				<div class="icons-right">
					<a class="btn icon-btn btn-info btn-like-<?php echo $_GET['id_ref']; ?>" href="javascript:likePublish(<?php echo $_GET['id_ref']; ?>,'<?php echo $_GET['type']; ?>');">
						<span class="fas fa-plus"> </span> 
						<span class="far fa-thumbs-up img-circle"> </span> 
						<span class="label"><font class="total-likes-<?php echo $_GET['id_ref']; ?>">0</font> Me gusta</span>
					</a>
					
					<a class="btn icon-btn btn-info">
						<span class="fas fa-eye img-circle"> </span> 
						<span class="label"><font class="total-views-<?php echo $_GET['id_ref']; ?>">0</font> Visitas</span>
					</a>
					
					<a class="btn btn-sm btn-edit-publish btn-success btn-public-publish" href="#"></a>
					<a class="btn btn-sm btn-delete-publish btn-danger btn-trash-publish" href="#"></a>
					
				</div>
					
			</div>
			
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="col-sm-12">
					<h4 class="publish-tags" style="line-height: 25px;">Etiquetas</h4>
					
				</div>
				
				<div class="col-sm-12 text">
					<div class="publish-data-page">
						<li class="spin-publish-page"><a><i class="fas fa-spinner fa-spin" style="color:#000;"></i> Cargando</a></li>
						<?php 
							#echo '<img style="width:100%;" src="'.($post['thumbnail_url']).'" class="zoomImage" />';
						 ?>
					</div>
				</div>
			
				<div class="col-sm-12 text">	
					<ul class="list-group">
						<?php /*$i=1; ?>
						<?php foreach($post['related'] As $rel){ ?>
							<li class="list-group-item row">
								<a href="index.php?pageActive=single&type=<?php echo $rel["type"]; ?>&id_ref=<?php echo $rel["id"]; ?>">
									<div class="col-sm-1">
										<img src="<?php echo urlImageById($rel["thumbnail"]); ?>" width="100%" class="zoomImage_not" />
									</div>
									<div class="col-sm-11">
										<big><b><?php echo $rel["title"]; ?></b></big> 
										<p><?php echo $rel['short_description']; ?></p>
									</div>
								</a>
							</li>
							<?php $i++; ?>
						<?php }*/ ?>
					</ul>
					
					
	  
				</div>
			</div>
					
		</div>
	</div>
</div>

<script>

</script>



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
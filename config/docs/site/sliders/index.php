
<div id="myCarousel" class="banner-carousel carousel slide" data-type="ecards" data-limit="10" data-page="1" data-ride="carousel" data-pause="hover" data-interval="10000" data-wrap="true">
	<div class="carousel-inner"></div>
	<a class="carousel-control left" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
	<a class="carousel-control right" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>

	<ul class="nav nav-pills nav-justified carousel-info"></ul>
</div>


<style>
#myCarousel {
    /* margin-left: -50px; */
    width: calc(100%);
    /* top: -25px; */
}
#myCarousel .carousel-inner{
	height: calc(100% - 200px) !important;	
}

#myCarousel .nav a small {
    display:block;
	font-size: 0.6em;
}
#myCarousel .nav {
	background:#eee;
}
#myCarousel .nav a {
    border-radius:0px;
}
.carousel-inner>.item>a>img, .carousel-inner>.item>img{
	width: 100%;
}

#myCarousel .nav>li>a{
	padding: 5px 10px;
}
</style>
<script>
$(document).ready( function() {
	/**
    $('#myCarousel').carousel({
		interval:   4000
	});
	**/
	
	var clickEvent = false;
	$('#myCarousel').on('click', '.nav a', function() {
			clickEvent = true;
			$('.nav li').removeClass('active');
			$(this).parent().addClass('active');		
	}).on('slid.bs.carousel', function(e) {
		if(!clickEvent) {
			var count = $('.nav').children().length -1;
			var current = $('.nav li.active');
			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			if(count == id) {
				$('.nav li').first().addClass('active');	
			}
		}
		clickEvent = false;
	});
});
</script>
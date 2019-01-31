<style>
body{
	background-image:url(images/wallpapers/intro-opt.gif) !important;
}

body {
	color: #fff;
	text-align: center;
	text-shadow: 0 1px 3px rgba(0,0,0,.5);
	background-color: #000;
	background-image: url('images/wallpapers/intro-opt.gif');
	background-repeat: no-repeat;
	background-size: 100% 100%;
	height: calc(100vh);
	width: calc(100vw);
	overflow: hidden;
}

.modal-header {
    padding: 5px 15px;
	background-color: steelblue;
}

.panel-footer {
	padding: 1px 15px;
	color: #A0A0A0;
}

.profile-img {
	width: 96px;
	height: 96px;
	margin: 0 auto 10px;
	display: block;
	-moz-border-radius: 50%;
	-webkit-border-radius: 50%;
	border-radius: 50%;
}

.btn-danger {
	color: #fff;
	background-color: #c8d529;
    border-color: #f1b413;
}
.btn-danger:hover {
	background-color: #f1b413;
    border-color: #c8d529;
}
.btn-danger:before{
	background-color: #f1b413;
    border-color: #c8d529;
}
.btn-danger.active.focus, .btn-danger.active:focus, .btn-danger.active:hover, .btn-danger:active.focus, .btn-danger:active:focus, .btn-danger:active:hover, .open>.dropdown-toggle.btn-danger.focus, .open>.dropdown-toggle.btn-danger:focus, .open>.dropdown-toggle.btn-danger:hover{
	background-color: #f1b413;
    border-color: #c8d529;
}

.btn-primary {
	color: #fff;
	background-color: #f1b413;
    border-color: #c8d529;
}
.btn-primary:hover {
	background-color: #c8d529;
    border-color: #f1b413;
}
.btn-primary:before{
	background-color: #c8d529;
    border-color: #f1b413;
}
.btn-primary.active.focus, .btn-danger.active:focus, .btn-danger.active:hover, .btn-danger:active.focus, .btn-danger:active:focus, .btn-danger:active:hover, .open>.dropdown-toggle.btn-danger.focus, .open>.dropdown-toggle.btn-danger:focus, .open>.dropdown-toggle.btn-danger:hover{
	background-color: #c8d529;
    border-color: #f1b413;
}

</style>


<div class="site-wrapper">
	<div class="site-wrapper-inner">
		<div class="cover-container">
			<div class="col-sm-12">
				<div class="row text-center">
					<a href="javascript:checkLoginState();" class="btn btn-lg btn-warning" >Descubre</a>
					
					
					
					<div id="status"></div>
					<a href="javascript:FormaT.LogOut();">Salir</a>

				</div>
			</div>
			<div class="mastfoot">
				<div class="inner">
					<p>Developed by <a href="http://feliphegomez.info">FelipheGomez</a>.</p>
				</div>
			</div>
		</div>
	</div>
</div>


<style>	
/*
 * Globals
 */

/* Links */
a,
a:focus,
a:hover {
  color: #fff;
}

/* Custom default button */
.btn-default,
.btn-default:hover,
.btn-default:focus {
  color: #333;
  text-shadow: none; /* Prevent inheritance from `body` */
  background-color: #fff;
  border: 1px solid #fff;
}


/*
 * Base structure
 */

/* Extra markup and styles for table-esque vertical and horizontal centering */
.site-wrapper {    position: absolute;
    display: table;
    width: 80%;
    height: auto;
    top: 50%;
    right: 10%;
    z-index: 9999;
}
.site-wrapper-inner {
  display: table-cell;
  vertical-align: top;
}
.cover-container {
  margin-right: auto;
  margin-left: auto;
}

/* Padding for spacing */
.inner {
  padding: 30px;
}

@media (min-width: 768px) {
  .masthead-brand {
    float: left;
  }
  .masthead-nav {
    float: right;
  }
}

.cover {
  padding: 0 20px;
}
.cover .btn-lg {
  padding: 10px 20px;
  font-weight: bold;
}

.mastfoot {
  color: #999; /* IE8 proofing */
  color: rgba(255,255,255,.5);
}

@media (min-width: 768px) {
  /* Pull out the header and footer */
  .masthead {
    position: fixed;
    top: 0;
  }
  .mastfoot {
    position: fixed;
    bottom: 0;
  }
  /* Start the vertical centering */
  .site-wrapper-inner {
    vertical-align: middle;
  }
  /* Handle the widths */
  .masthead,
  .mastfoot,
  .cover-container {
    width: 100%; /* Must be percentage or pixels for horizontal alignment */
  }
}

@media (min-width: 992px) {
  .masthead,
  .mastfoot,
  .cover-container {
    width: 700px;
  }
}

#particles-js{
  	width: 100%;
  	height: 100%;
  	background-size: cover;
  	background-position: 50% 50%;
  	position: fixed;
  	top: 0px;
  	z-index:1;
}
</style>


<div id="particles-js"></div>
<script type="text/javascript" src="api/plugins/particles.js/2.0.0/particles.min.js"></script>


<script>		
	$('#smallModal').on('shown.bs.modal', function () {
		$('input[name="userOrCedula"]').focus();
	});

    particlesJS('particles-js',
      {
        "particles": {
          "number": {
            "value": 80,
            "density": {
              "enable": true,
              "value_area": 800
            }
          },
          "color": {
            "value": "#ffffff"
          },
          "shape": {
            "type": "circle",
            "stroke": {
              "width": 0,
              "color": "#000000"
            },
            "polygon": {
              "nb_sides": 5
            },
            "image": {
              "width": 100,
              "height": 100
            }
          },
          "opacity": {
            "value": 0.5,
            "random": false,
            "anim": {
              "enable": false,
              "speed": 1,
              "opacity_min": 0.1,
              "sync": false
            }
          },
          "size": {
            "value": 5,
            "random": true,
            "anim": {
              "enable": false,
              "speed": 40,
              "size_min": 0.1,
              "sync": false
            }
          },
          "line_linked": {
            "enable": true,
            "distance": 150,
            "color": "#ffffff",
            "opacity": 0.4,
            "width": 1
          },
          "move": {
            "enable": true,
            "speed": 6,
            "direction": "none",
            "random": false,
            "straight": false,
            "out_mode": "out",
            "attract": {
              "enable": false,
              "rotateX": 600,
              "rotateY": 1200
            }
          }
        },
        "interactivity": {
          "detect_on": "canvas",
          "events": {
            "onhover": {
              "enable": true,
              "mode": "none"
            },
            "onclick": {
              "enable": true,
              "mode": "push"
            },
            "resize": true
          },
          "modes": {
            "grab": {
              "distance": 400,
              "line_linked": {
                "opacity": 1
              }
            },
            "bubble": {
              "distance": 400,
              "size": 40,
              "duration": 2,
              "opacity": 8,
              "speed": 3
            },
            "repulse": {
              "distance": 200
            },
            "push": {
              "particles_nb": 4
            },
            "remove": {
              "particles_nb": 2
            }
          }
        },
        "retina_detect": true,
        "config_demo": {
          "hide_card": false,
          "background_color": "",
          "background_image": "images/wallpapers/intro-opt.gif",
          "background_position": "50% 50%",
          "background_repeat": "no-repeat",
          "background_size": "cover"
        }
      }
    );

</script>
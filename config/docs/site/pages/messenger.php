<div class="col-sm-12 messenger-chat-page-view" id="messenger">
<div class="container app">
	<div class="row app-one">
		<div class="col-sm-5 side">
			<div class="side-one">
				<div class="row heading">
					<div class="col-sm-2 col-xs-2 heading-compose  pull-right">
						<i class="fa fa-comments fa-2x  pull-right" aria-hidden="true" onClick="javascript:cargarPeopleList();"></i>
					</div>
				</div>

				<div class="row sideBar last-chats-messenger">
				</div>
			</div>

			<div class="side-two">
				<div class="row newMessage-heading">
					<div class="row newMessage-main">
						<div class="col-sm-2 col-xs-2 newMessage-back">
							<i class="fa fa-arrow-left" aria-hidden="true"></i>
						</div>
						<div class="col-sm-10 col-xs-10 newMessage-title groups-list-messenger">
							Nuevo Mensaje
						</div>
					</div>
				</div>

				<div class="row composeBox">
					<div class="col-sm-12 composeBox-inner">
						<div class="form-group has-feedback">
							<input id="contact-list-search" type="text" class="form-control" name="searchText" placeholder="¿A Quien Buscas?">
							<span class="glyphicon glyphicon-search form-control-feedback"></span>
						</div>
					</div>
				</div>

				<div class="row compose-sideBar people-chat-list" id="contact-list"></div>
			</div>
		</div>

		<div class="col-sm-7 conversation">
			<div class="row heading">
				<div class="col-sm-8 col-xs-7 heading-name">
					<a class="heading-name-meta chat-to-page">Integrantes</a>
				</div>
				<div class="col-sm-1 col-xs-1  heading-dot pull-right">
					<!-- <i class="fa fa-ellipsis-v fa-2x  pull-right" aria-hidden="true"></i> -->
				</div>
			</div>
			
			<div class="row message" id="conversation">
				<div class="row message-previous">
					<div class="col-sm-12 previous">
						<a onclick="javascript:cargarMasChatsPage();" data-page="2" data-limit="10" id="ankitjain28">Mas mensajes</a>
					</div>
				</div>
				<div class="chat-active-page">
					<!--
					<div class="row message-body">
					  <div class="col-sm-12 message-main-receiver">
						<div class="receiver">
						  <div class="message-text">
						   Hi, what are you doing?!
						  </div>
						  <span class="message-time pull-right">
							Sun
						  </span>
						</div>
					  </div>
					</div>

					<div class="row message-body">
					  <div class="col-sm-12 message-main-sender">
						<div class="sender">
						  <div class="message-text">
							I am doing nothing man!
						  </div>
						  <span class="message-time pull-right">
							Sun
						  </span>
						</div>
					  </div>
					</div>
						-->
					
				</div>
			</div>
			
			<div class="row reply">
				<div data-toggle="tooltip" title="Invitar a alguien" class="col-sm-1 col-xs-1 reply-emojis" onclick="javascript:agregarUserConversacion();"><i class="fas fa-user-plus fa-2x"></i></div>
				<div class="col-sm-9 col-xs-9 reply-main">
				  <textarea class="form-control" rows="2" id="text-response-send" data-sendid="<?php echo $_GET['chatActive']; ?>"></textarea>
				  <input type="hidden" class="form-control" id="chat-conversacion-id" value="<?php echo $_GET['chatActive']; ?>"></textarea>
				</div>
				<div class="col-sm-1 col-xs-1 reply-recording">
					<!-- <i class="far fa-image fa-2x" aria-hidden="true"></i> -->
					<span class="btn btn-sm btn-file-image" data-toggle="tooltip" title="Enviar Imagen">
						<i class="far fa-image fa-2x" aria-hidden="true"></i> <input type="file" accept="image/*" class="imgInp send-image-conversation">
					</span>
				</div>
				<div data-toggle="tooltip" title="Enviar Mensaje" class="col-sm-1 col-xs-1 reply-send" onclick="javascript:enviarMensajePageChat();"><i class="fas fa-share-square fa-2x" aria-hidden="true"></i></div>
			</div>
			<!--
			-->
		</div>
	</div>
</div>


</div>

<link rel="stylesheet" href="<?php echo url_site; ?>/css/chat.css">
<script>
$(function(){
    $(".heading-compose").click(function() {
      $(".side-two").css({
        "left": "0"
      });
    });

    $(".newMessage-back").click(function() {
      $(".side-two").css({
        "left": "-100%"
      });
    });
})
</script>

<script>
	/**
	$('#send-response-page-chat').jemoji({
	  icons:        ["admire2", "admire", "ahaaah", "angel1", "angel2", "bad_atmosphere", "beaten", "beg", "big_eye", "bike", "bird", "bled1", "bled2", "bleeding", "blocked", "bsod", "bye1", "bye2", "cheer1", "cheer2", "cheer3", "confused", "congrats", "cool", "cruch", "crying1", "crying2", "crying3", "cute2", "dead", "depressed1", "depressed2", "desperate1", "desperate2", "dong", "dreaming", "dying", "eaten_alive", "eating_me", "embarrassed1", "embarrassed2", "embarrassed3", "embarrassed4", "evil_smile", "expulsion", "falling_asleep", "freezing", "frozen", "full", "ghost", "good_job", "good_luck", "happy", "hate", "hehe", "hell_yes", "help", "hi", "hot1", "hot2", "hypnosis", "ill", "info", "innocent", "kicked1", "kicked2", "kick", "lie", "lol1", "lol2", "lonely", "love", "meh", "nonono", "noooo", "not_listening", "objection", "oh", "onionspayup", "pff1", "pff2", "pointing", "pretty", "punch", "push_up", "relax1", "relax2", "rice_ball_smiley_10", "robot", "running", "scared", "scary", "serenade", "shock1", "shock2", "shy", "sigh", "silence", "sleeping", "smoking1", "smoking2", "spa", "starving", "stoned", "stress", "studying", "super", "super_sayan", "sweating", "sweetdrop", "tar", "uhuhuh", "victory", "vomiting", "wait", "waiting", "warning", "washing", "wet", "wew", "whaaat1", "whaaat2", "whaaat3", "what", "whip", "whistling", "white_cloud_emoticon6", "woa", "work", "wow1", "wow2", "yawn"],
	  extension:    'gif',
	  folder:       'gifs/onions/'
	});
	
	// Set jEmoji
	$('#send-response-page-chat').jemoji({
	  menuBtn:    $('.insertEmojin'),
	  container:  $('#send-response-page-chat').parent().parent()
	});
	$('.chat-active-page').jemoji();
	
	
	function abrirEmojins(){
		$('#send-response-page-chat').jemoji('open');
	}**/
	
	function searchPeopleChat(e){
		if(e.key=='Enter'){ e.key = ''; };
		
		val = $( "#contact-list-search" ).val();
		val += e.key;
		val = val.toLowerCase();
		$( "#contact-list" ).find('.list-group-item').each(function(i) {
			if(val == ''){
				$(this).show();
			}else{
				if($(this).data("name") !== undefined && $(this).data("name") !== ""){
					name = $(this).data("name");
					nameRes = name.search(val);
					if(nameRes >= 0){ $(this).show(); }else{ $(this).hide(); };
				}
				/*
				if($(this).data("user") !== undefined && $(this).data("user") !== ""){
					user = $(this).data("user");
					userRes = user.search(val);
					if(userRes >= 0){ $(this).show(); }else{ $(this).hide(); };
				}*/
			}
		});
	};

	$(function(){
		$( "#contact-list-search" ).keypress(function(e) { searchPeopleChat(e); });
		$('[data-toggle="tooltip"]').tooltip();		
		$('[data-command="toggle-search"]').on('click', function(event) {
			event.preventDefault();
			$(this).toggleClass('hide-search');			
			if ($(this).hasClass('hide-search')) { $('.c-search').closest('.row').slideUp(100); }else{ $('.c-search').closest('.row').slideDown(100); $("#contact-list-search").focus(); };
		});
	});
</script>
<?php 
	if(isset($_GET['id']) && $_GET['id'] > 0 && isset($_GET['view']) && $_GET['view'] == 'single'){
		include('widgets/notifications-view.php');
	}else if(isset($_GET['action']) && $_GET['action'] == 'end' && isset($_GET['view']) && $_GET['view'] == 'cronometro'){
		include('widgets/notifications-cronometro.php');
	}else if(isset($_GET['action']) && $_GET['action'] == 'timeAlerts' && isset($_GET['value']) && $_GET['value'] > 0){
		include('widgets/notifications-cronometro.php');
	};
?>
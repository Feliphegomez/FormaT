<?php 
	if(isset($_GET['id_ref']) && $_GET['id_ref'] > 0 && isset($_GET['type']) && isset($_GET['pageActive']) && $_GET['pageActive'] == 'single'){
		include("view-articles.php");
	};
?>
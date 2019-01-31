<?php

if(!isset($_GET['of'])){ $_GET['of'] = 0; };

if(isset($_GET['type']) && $_GET['type'] == 'articles' || isset($_GET['type']) && $_GET['type'] == 'ecards'){
	include('explore-articles.php');
}else if(isset($_GET['type']) && $_GET['type'] == 'calendary'){
	include('view-calendary.php');
}else if(isset($_GET['type']) && $_GET['type'] == 'devices' && isset($_GET['device_id']) && $_GET['device_id'] > 0){
	include("view-devices-topics.php");
}elseif(isset($_GET['type']) && $_GET['type'] == 'devices' && isset($_GET['device_manufacturer']) && $_GET['device_manufacturer'] > 0 && isset($_GET['device_type']) && $_GET['device_type'] > 0){
	include("view-devices-filter.php");
}elseif(isset($_GET['type']) && $_GET['type'] == 'forum'){
	include("view-forum.php");
};
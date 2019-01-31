<?php
#  --------------------------------------------------------------- #
#  author: FelipheGomez
#  author URL: http://demedallo.com
#  License: Creative Commons Attribution 3.0 Unported
#  License URL: http://creativecommons.org/licenses/by/3.0/
#  --------------------------------------------------------------- #
require_once("autoload.php");


if(isset($data['user']) && $data['user'] !== "" && isset($data['action']) && $data['action'] == "login"){
	$consulta = datosSQL("Select * from ".TBL_PERSONAL." where cedula='{$data['user']}' OR user='{$data['user']}'");
	if(isset($consulta->data[0]) && isset($consulta->error) && $consulta->error == false){
		$jsonFinal = $success_API->{'30'}; # Success 30 - Sesion creada con exito.
		$jsonFinal->session = crearSessionForUserOrCedula($consulta->data[0]['user']);
	}
	else{
		$jsonFinal = $errores_API->{'700'}; # Error 700 - Usuario no encontrado, compruebe sus datos e intente nuevamente.
	}
}
else if(isset($data['accesstoken']) && $data['accesstoken'] !== "" && isset($data['action']) && $data['action'] == "refresh"){
	$checkToken = chechear_AccessToken_CCyUser($data['accesstoken']);
	if($checkToken!==false){
		$jsonFinal = $success_API->{'32'}; # Success 32 - Sesion conectada con exito.
		$jsonFinal->session = crearSessionForUserOrCedula($checkToken['user']);
	}
	else{
		$jsonFinal = $errores_API->{'100'}; # Error 100 - El token de acceso es invalido, prueba con otro o genera uno nuevamente
	}
}
else if(isset($data['check']) && $data['check'] !== ""){
	$checkToken = chechear_AccessToken_CCyUser($data['check']);
	if($checkToken!==false){
		$jsonFinal = $success_API->{'34'}; # Success 34 - Sesion encontrada con exito.
		$jsonFinal->session = ($checkToken);
	}
	else{
		$jsonFinal = $errores_API->{'100'}; # Error 100 - El token de acceso es invalido, prueba con otro o genera uno nuevamente
	}
}

if(isset($data['action'])){ unset($data['action']); };	
if(isset($data['accesstoken'])){ unset($data['accesstoken']); };	
if(isset($data)){ $jsonFinal->fields = $data; };	
if(isset($permisos_user)){ $jsonFinal->permisos = $permisos_user; };

#FINAL
header('Content-Type: application/json');
echo json_encode($jsonFinal,JSON_PRETTY_PRINT);
return json_encode($jsonFinal,JSON_PRETTY_PRINT);


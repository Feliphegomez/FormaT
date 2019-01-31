<?php
#  --------------------------------------------------------------- #
#  author: FelipheGomez
#  author URL: http://demedallo.com
#  License: Creative Commons Attribution 3.0 Unported
#  License URL: http://creativecommons.org/licenses/by/3.0/
#  --------------------------------------------------------------- #
require_once("autoload.php");

if(isset($data['accesstoken'])){
	$checkToken = chechear_AccessToken_CCyUser($data['accesstoken']);
	if($checkToken==false){
		$jsonFinal = $errores_API->{'100'}; # Error 100 - El token de acceso es invalido, prueba con otro o genera uno nuevamente
	}else{
		$permisos_user = $checkToken['permisos_cargos']->indicators; # Permisos para la pagina
		
		# Leyendo
		if(isset($data['action']) && $data['action'] == 'view'){
			if(isset($permisos_user->view) && $permisos_user->view == true){
				if(isset($data['my']) && $data['my'] == true){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = new stdClass();
					$indicadores = cargarIndicadores($checkToken['id']);
					$jsonFinal->data = organizarKPIs($indicadores,$checkToken);
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
				
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		else{
			$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
		}
	}
}else{
	$jsonFinal = $errores_API->{'110'}; # Error 110 - Falta el token de acceso
};

if(isset($data['action'])){ unset($data['action']); };	
if(isset($data['accesstoken'])){ unset($data['accesstoken']); };	
if(isset($data)){ $jsonFinal->fields = $data; };	
if(isset($permisos_user)){ $jsonFinal->permisos = $permisos_user; };

#FINAL
header('Content-Type: application/json');
echo json_encode($jsonFinal,JSON_PRETTY_PRINT);
return json_encode($jsonFinal,JSON_PRETTY_PRINT);

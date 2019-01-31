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
		$permisos_user = $checkToken['permisos_cargos']; # Permisos para la pagina
		
		
		# Creando
		if(isset($data['action']) && $data['action'] == 'create'){
			
			if(isset($data['like_add']) && $data['like_add'] == true && isset($data['type']) && isset($data['id_ref']) && $data['id_ref'] > 0){
				$jsonFinal->stadistics = cargarLikesAndViewsPublish($data['id_ref'],$data['type']);
				$jsonFinal->stadistics->likes = $jsonFinal->stadistics->likes+1;
				
				$command = "UPDATE ".TBL_VIEWS_AND_LIKES." SET likes=? WHERE id_ref='{$data['id_ref']}' and type='{$data['type']}'";
				$create = crearSQL($command,array($jsonFinal->stadistics->likes));
				if(isset($create->error) && $create->error == false){
					$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
				$jsonFinal->stadistics = cargarLikesAndViewsPublish($data['id_ref'],$data['type']);
				$jsonFinal->stadistics->likes = $jsonFinal->stadistics->likes+1;
				}
				else{
					$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
				}
			}
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		# Leyendo
		else if(isset($data['action']) && $data['action'] == 'view'){
			# Ver mis indicadores
			if(isset($data['indicators']) && $data['indicators'] == true){
				if(isset($permisos_user->indicators->view) && $permisos_user->indicators->view == true){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = new stdClass();
					$indicadores = cargarIndicadores($checkToken['id']);
					$jsonFinal->data = organizarKPIs($indicadores,$checkToken);
				}else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				}
			}
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		# Modificando
		else if(isset($data['action']) && $data['action'] == 'change'){			
			if(isset($data['avatar']) && $data['avatar'] == true && isset($data['data'])){
				$cons = crearSQL("INSERT INTO ".TBL_IMAGENES_GLOBAL." ( data ) VALUES (?)",array($data['data']));
				if(isset($cons->error) && $cons->error == false){
					$cons2 = crearSQL("UPDATE ".TBL_PERSONAL." SET avatar=? WHERE id='{$checkToken['id']}'",array($cons->last_id));
					if(isset($cons2->error) && $cons2->error == false){
						$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
						$jsonFinal->id = $cons->last_id;
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					}
				}
				else{
					$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
				}
			}
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		# Historial
		elseif(isset($data['action']) && $data['action'] == "history"){
			if(isset($permisos_user->history) && $permisos_user->history == true){
				
				$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			};
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

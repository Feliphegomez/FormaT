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
		$permisos_user = $checkToken['permisos_cargos']->alerts; # Permisos para la pagina
		# Eliminando
		if(isset($data['action']) && $data['action'] == 'delete'){
			# Validar si hay permisos para la accion
			if(isset($permisos_user->delete) && $permisos_user->delete == true){
				if(isset($data['id']) && $data['id'] > 0){
					$fecha_cierre = date("Y-m-d H:i:s",time()); # Fecha de Cierre
					$trash = 1; # Activar papelera
					$command = "UPDATE ".TBL_ALERTS." SET fecha_cierre=?,trash=? WHERE id='{$data['id']}'"; # Consulta SQL
					$command_array = array($fecha_cierre,$trash); # Array con datos para la consulta SQL
					$cons = crearSQL($command,$command_array);
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'5'}; # Success 5 - Contenido eliminado con exito.
						$jsonFinal->id = $data['id'];
					}
					else{
						$jsonFinal = $errores_API->{'300'}; # Error 300 - Ocurrio un problema eliminando la información, Intente nuevamente. 
					}
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		
		# Creando 
		else if(isset($data['action']) && $data['action'] == 'create'){
			if(isset($permisos_user->create) && $permisos_user->create == true){
				# nueva
				if(isset($data['title']) && isset($data['message']) && isset($data['ticket'])){
					$fecha_apertura = date("Y-m-d H:i:s",time());
					
					$command = "INSERT INTO ".TBL_ALERTS." ( title,message,ticket,piloto,fecha_apertura,trash ) VALUES (?,?,?,?,?,?)"; # Consulta SQL
					$command_array = array($data['title'],$data['message'],$data['ticket'],$checkToken['piloto'],$fecha_apertura,0); # Array con datos para la consulta SQL
					$cons = crearSQL($command,$command_array);
					
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
						$jsonFinal->id = $cons->last_id;
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					}
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		
		# Modificando
		else if(isset($data['action']) && $data['action'] == 'change'){
			if(isset($permisos_user->edit) && $permisos_user->edit == true){
				# modificar
				if(isset($data['id']) && isset($data['title']) && isset($data['message']) && isset($data['ticket'])){
					$command = "UPDATE ".TBL_ALERTS." SET title=?,message=?,ticket=?,piloto=? WHERE id='{$data['id']}'"; # Consulta SQL
					$command_array = array($data['title'],$data['message'],$data['ticket'],$checkToken['piloto']); # Array con datos para la consulta SQL
					$cons = crearSQL($command,$command_array);
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					}
				}
				
				# activar una especifica
				else if(isset($data['id']) && isset($data['active']) && $data['active'] == true && $data['id'] > 0){
					$command = "UPDATE ".TBL_ALERTS." SET trash=? WHERE id='{$data['id']}'"; # Consulta SQL
					$command_array = array(0); # Array con datos para la consulta SQL
					$cons = crearSQL($command,$command_array);
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'20'}; # Success 20 - Contenido activado con exito.
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					}
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
			}
			else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		
		# Leyendo
		else if(isset($data['action']) && $data['action'] == 'view'){
			if(isset($permisos_user->view) && $permisos_user->view == true){
				
				# Ver alertas especifica
				if(isset($data['id']) && $data['id'] > 0){
					$cons = datosSQL("Select * from ".TBL_ALERTS."  where id='{$data['id']}' and piloto IN ('{$checkToken['piloto']}') ");
					if(isset($cons->error) && $cons->error == false && $cons->data[0]){
						
						if($cons->data[0]['trash'] == 0){
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							$jsonFinal->data = $cons->data[0];
						}
						else{
							if(isset($checkToken['permisos_cargos']->alerts->history) && $checkToken['permisos_cargos']->alerts->history == true){
								$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
								$jsonFinal->data = $cons->data[0];
							}
							else{
								$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
							}
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				# ver todo lo activo
				else{
					$data = validarPagiacion($data);
					$cons = datosSQL("Select * from ".TBL_ALERTS." where piloto IN ('{$checkToken['piloto']}') and trash='0' ORDER BY fecha_apertura {$data['order']} LIMIT {$data['offset']}, {$data['limit']} ");
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = $cons->data;
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
			
		}
		
		# Historial
		elseif(isset($data['action']) && $data['action'] == "history"){
			if(isset($checkToken['permisos_cargos']->alerts->history) && $checkToken['permisos_cargos']->alerts->history == true){
				$data = validarPagiacion($data);
				$cons = datosSQL("Select * from ".TBL_ALERTS." where piloto IN ('{$checkToken['piloto']}') and trash='1' ORDER BY fecha_apertura {$data['order']} LIMIT {$data['offset']}, {$data['limit']} ");
				if(isset($cons->error) && $cons->error == false){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = $cons->data;
				}
				else{
					$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
				}
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			};
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
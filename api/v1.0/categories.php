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
		if(!isset($data['type'])){ $data['type'] = 'articles'; }; # Valor predeterminado para Type
		if(isset($checkToken['permisos_cargos']->{$data['type']}->{'categories'})){
			$permisos_user = $checkToken['permisos_cargos']->{$data['type']}->{'categories'}; # Permisos para la pagina
		}else{
			$permisos_user = false; # Permisos para la pagina
		}
		
		# Eliminando
		if(isset($data['action']) && $data['action'] == 'delete'){
			if(isset($permisos_user->delete) && $permisos_user->delete == true){
				$jsonFinal = $success_API->{'5'}; # Success 5 - Contenido eliminado con exito.
				if(isset($data['id']) && $data['id'] > 0){
					$data['id'] = (int) $data['id'];
					$delete = eliminarSQL("DELETE FROM ".TBL_CATEGORIES." WHERE id='{$data['id']}' and piloto IN ('{$checkToken["piloto"]}')  ");
					if($delete->error == false){
						$jsonFinal = $success_API->{'5'}; # Success 5 - Contenido eliminado con exito.
					}
					else{
						$jsonFinal = $errores_API->{'300'}; # Error 300 - Ocurrio un problema eliminando la información, Intente nuevamente. 
					};
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
			}
			else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		# Creando
		else if(isset($data['action']) && $data['action'] == 'create'){
			if(isset($permisos_user->create) && $permisos_user->create == true){				
				if(
					isset($data['name']) && $data['name'] !== '' 
					&& isset($data['type']) && $data['type'] !== '' 
					&& isset($data['raiz']) && $data['raiz'] !== ''
				){
					$result = crearSQL("INSERT INTO ".TBL_CATEGORIES." ( name,raiz,type,piloto,view ) VALUES (?,?,?,?,?)",array($data['name'],$data['raiz'],$data['type'],$checkToken['piloto'],1));
					
					if($result->error == false){
						$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
						$jsonFinal->id = $result->last_id;
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					};
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
				
				if(isset($data['option_list']) && $data['option_list'] == true && isset($data['type']) && $data['type'] !== ''){
					$data['type'] = (string) $data['type'];
					
					$sql = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ({$checkToken['piloto']}) and view IN (1) and type IN ('{$data['type']}') ");
					if($sql->error === false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
						foreach($sql->data As $c){
							$s = new stdClass();
							$s->text = $c['name'];
							$s->value = $c['id'];
							
							$jsonFinal->data[] = $s;
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				else if(isset($data['id']) && $data['id'] > 0){
					$data['id'] = (int) $data['id'];
					
					$sql = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ({$checkToken['piloto']}) and view IN (1) and id IN ('{$data['id']}') ");
					if($sql->error === false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = $sql->data[0];
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				else if(isset($data['type']) && $data['type'] !== '' && isset($data['list']) && $data['list'] == true){
					$data['type'] = (string) $data['type'];
					
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = cargarCategorias($checkToken['piloto'],$data['type'],0);
					$jsonFinal->type = $data['type'];
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
				# Modificar specifico
				if(
					isset($data['id']) 
					&& isset($data['name']) && $data['name'] !== '' 
					&& isset($data['type']) && $data['type'] !== '' 
					&& isset($data['raiz']) && $data['raiz'] !== '' 
					&& $data['id'] > 0
				){
					$result = crearSQL("UPDATE ".TBL_CATEGORIES." SET name=?,raiz=?,type=? WHERE id='{$data['id']}' ",array($data['name'],$data['raiz'],$data['type']));
					
					if($result->error==false){
						$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamen
					}
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
}
else{
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
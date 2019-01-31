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
		if(isset($checkToken['permisos_cargos']->{$data['type']})){
			$permisos_user = $checkToken['permisos_cargos']->{$data['type']}; # Permisos para la pagina
		}else{
			$permisos_user = false; # Permisos para la pagina
		}
		
		# BORRAR Y OCULTAR PUBLICACIONES
		if(isset($data['action']) && $data['action'] == 'delete'){
			/*
			if(isset($permisos_user->delete) && $permisos_user->delete == true){}
			else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
			*/
			
			if(isset($permisos_user->delete) && $permisos_user->delete == true){
				if(isset($data['id']) && $data['id'] > 0){
					$data['id'] = (int) $data['id'];
					$data['fchange'] = date("Y-m-d H:i:s",time());
					$change = crearSQL("UPDATE ".TBL_CONTENIDO." SET fchange=?,trash=? WHERE id='{$data['id']}'",array($data['fchange'],1));
					if($change->error == false){
						$jsonFinal = $success_API->{'5'}; # Success 5 - Contenido eliminado con exito.
					}
					else{
						$jsonFinal = $errores_API->{'300'}; # Error 300 - Ocurrio un problema eliminando la información, Intente nuevamente. 
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
		# CREAR PUBLICACIONES
		else if(isset($data['action']) && $data['action'] == 'create'){
			if(isset($permisos_user->create) && $permisos_user->create == true){
				if(isset($data['draft']) && $data['draft'] == true && isset($data['type']) && $data['type'] !== '' ){
					$command = "INSERT INTO ".TBL_CONTENIDO." ( type,piloto,author ) VALUES (?,?,?)";
					$create = crearSQL($command,array($data['type'],$checkToken['piloto'],$checkToken['id']));
					if(isset($create->error) && $create->error == false){
						$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
						$jsonFinal->id = $create->last_id;
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
		# VER PUBLICACIONES
		else if(isset($data['action']) && $data['action'] == 'view'){
			
			
			# Cargar Ultimas Publicaciones
			if(isset($data['type']) && $data['type'] !== "" && !isset($data['id']) && !isset($data['draft']) || isset($data['type']) && $data['type'] !== "" && !isset($data['id']) && isset($data['draft']) == false){
				$data = validarPagiacion($data);
				# Validar si hay permisos
				if(isset($permisos_user->view) && $permisos_user->view == true){
					if(!isset($data['of'])){ $data['of'] = 0; }else{ $data['of'] = (int) $data['of']; };
					$sql = explorarRaizPublicaciones($checkToken['piloto'],$data['type'],$data['limit'],$data['offset'],$data['of']);
					
					if($sql->error === false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
						
						foreach($sql->data As $feed){
							$feed = parseArticles($feed,$data['accesstoken']);
							$feed['create'] = $checkToken['permisos_cargos']->{$data['type']}->create;
							$feed['delete'] = $checkToken['permisos_cargos']->{$data['type']}->delete;
							$feed['edit'] = $checkToken['permisos_cargos']->{$data['type']}->edit;
							$feed['categories'] = $checkToken['permisos_cargos']->{$data['type']}->categories;
							
							$jsonFinal->data[] = $feed;
						}
					}
					else{
						$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
					};
				}
				else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				}
			
			}
			
			
			# Cargar Publicacion ID And Type
			else if(isset($data['type']) && $data['type'] !== "" && isset($data['id']) && $data['id'] !== "" && !isset($data['draft']) ){
				$data['type'] = (string) $data['type'];
				$data['id'] = (int) $data['id'];
				$sql_trash = array();
				$sql_trash[] = 0;
				$sql_public = array();
				$sql_public[] = 1;
				
				if((isset($permisos_user->edit) && $permisos_user->edit == true )){ $sql_public[] = 0; }
				if((isset($permisos_user->history) && $permisos_user->history == true)){ $sql_trash[] = 1; }
				
				$sql_trash = implode(',',$sql_trash); $sql_public = implode(',',$sql_public);
				$sql_add  = " and public IN ({$sql_public}) and trash IN ({$sql_trash}) ";
				$sql = datosSQL("Select * from ".TBL_CONTENIDO." where id='{$data['id']}' and piloto='{$checkToken['piloto']}' and type='{$data['type']}' {$sql_add} ");
				if($sql->error === false && isset($sql->data[0])){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					if(isset($permisos_user->create)){ $sql->data[0]['create'] = $permisos_user->create; }else{ $sql->data[0]['create'] = false; };
					if(isset($permisos_user->delete)){ $sql->data[0]['delete'] = $permisos_user->delete; }else{ $sql->data[0]['delete'] = false; };
					if(isset($permisos_user->edit)){   $sql->data[0]['edit'] =   $permisos_user->edit; }else{ $sql->data[0]['edit'] = false; };
					if(isset($permisos_user->categories)){   $sql->data[0]['categories'] =   $permisos_user->categories; }else{ $sql->data[0]['categories'] = false; };
					
					$jsonFinal->data = parseArticles($sql->data[0],$data['accesstoken']);
				}
				else{
					$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					$jsonFinal->data = $sql_add;
				}
			}
			
			
			# VER BORRADORES
			else if(isset($data['draft']) && $data['draft'] == true){
				$jsonFinal->permisos = new stdClass();
				$jsonFinal->permisos->ecards = $checkToken['permisos_cargos']->{"ecards"};
				$jsonFinal->permisos->articles = $checkToken['permisos_cargos']->{"articles"};
				
				$drafts = datosSQL("Select * from ".TBL_CONTENIDO." where piloto IN ({$checkToken['piloto']}) and public='0' and trash='0'");
				if(isset($drafts->error) && $drafts->error == false){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						
					$borradores = array();
					foreach($drafts->data As $element){
						$borradores[] = parseArticles($element,$data['accesstoken']);
					};
					$jsonFinal->data = $borradores;
				}
				else{
					$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
				}
			};
		}
		# MODIFICAR PUBLICACIONES
		else if(isset($data['action']) && $data['action'] == 'change'){
			
			# modificar pulicacion especifica
			if($data['id'] !== '' && isset($data['id']) && $data['id'] > 0 && !isset($data['active'])){
				$dataid = (int) $data['id'];
				unset($data['id']);
				unset($data['action']);
				unset($data['accesstoken']);
				if(isset($data['type']) && $data['type'] !== '')
				{
					unset($data['type']);
				}
					
				$jsonFinal->data = $data;
				
				$fields = array();
				$fields_q = array();
				$fields_array = array();
				foreach($data As $k_f=>$v_f){
					$v_f = ($v_f); //htmlspecialchars_decode and htmlspecialchars en caso de ser necesario
					$v_f = htmlspecialchars_decode($v_f);
					$fields[] = (string) $k_f;
					$fields_q[] = $k_f."='".$v_f."'";
					$fields_array[$k_f] = $v_f;
				}
				$data = $fields;
				$fields = implode(',',$fields);
				$fields_q = implode(',',$fields_q);
				
				$jsonFinal->fields = $fields;
				$jsonFinal->fields_q = $fields_q;
				$jsonFinal->fields_array = $fields_array;
				
				$change = crearSQL("UPDATE ".TBL_CONTENIDO." SET {$fields_q} WHERE id='{$dataid}' ",$fields_array);
				if($change->error == false){
					$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
				}
				else{
					$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamen
				}		
			}
			
			# Reactivar publicacion -> Sacar de papelera o historial
			else if(isset($data['id']) && $data['id'] > 0 && isset($data['active']) && $data['active'] == true){
				$data['id'] = (int) $data['id'];
				$data['fchange'] = date("Y-m-d H:i:s",time());
				
				$delete = crearSQL("UPDATE ".TBL_CONTENIDO." SET trash=?,fchange=? WHERE id='{$data['id']}'",array(0,$data['fchange']));
				if($delete->error == false){
					$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
				}
				else{
					$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamen
				}
			}
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		# HITORIAL PUBLICACIONES
		elseif(isset($data['action']) && $data['action'] == "history"){
			if(isset($data['type']) && $data['type'] !== ''){
				if(isset($checkToken['permisos_cargos']->{$data['type']}->history) && $checkToken['permisos_cargos']->{$data['type']}->history == true){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = array();
					$jsonFinal->permisos = $checkToken['permisos_cargos']->{$data['type']};
					
					$history = datosSQL("Select * from ".TBL_CONTENIDO." where piloto IN ({$checkToken['piloto']}) and type IN ('{$data['type']}') and trash='1'");
					if(isset($history->error) && $history->error == false && $history->data[0]){
						foreach($history->data As $feed){
							$feed = parseArticles($feed,$data['accesstoken']);
							$jsonFinal->data[] = $feed;
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				};
			}
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
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
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
		$permisos_user = $checkToken['permisos_cargos']->{'comments'}; # Permisos para la pagina
		
		# Eliminando
		if(isset($data['action']) && $data['action'] == 'delete'){
			if(isset($permisos_user->delete) && $permisos_user->delete == true){
				if(isset($data['query']) && $data['query'] > 0){
					$data['query'] = (int) $data['query'];
					$delete = eliminarSQL("DELETE FROM ".TBL_COMENTARIOS." WHERE id='{$data['query']}' and piloto IN ('{$checkToken["piloto"]}')  ");
					if($delete->error == false){
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
		# Creando
		else if(isset($data['action']) && $data['action'] == 'create'){
			if(isset($permisos_user->create) && $permisos_user->create == true){
				# crear nuevo comentario o pregunta
				if(isset($data['query']) && isset($data['type']) && isset($data['raiz'])){
					if(!isset($data['comment_raiz'])){ $data['comment_raiz'] = 0; }
					$data['reply'] = 0;
					$data['trash'] = 0;
					$data['author'] = $checkToken['id'];
							
					$result = crearSQL("INSERT INTO ".TBL_COMENTARIOS." ( query,raiz,type,comment_raiz,reply,trash,author,piloto ) VALUES (?,?,?,?,?,?,?,?)",array($data['query'],$data['raiz'],$data['type'],$data['comment_raiz'],$data['reply'],$data['trash'],$data['author'],$checkToken['piloto']));
					
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
				# Ver todos los Comentarios/Preguntas
				if(isset($data['type']) && isset($data['category']) && $data['category'] >= 0){
					$data = validarPagiacion($data);
					
					$cats1_sql = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ('{$checkToken['piloto']}') and view='1' and id='{$data['category']}' and type='{$data['type']}'");
					if(isset($cats1_sql->error) && $cats1_sql->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
						$jsonFinal->forum = array();
						
						if(isset($cats1_sql->data[0])){
							$jsonFinal->forum = $cats1_sql->data[0];
						}
						$jsonFinal->data = cargarPreguntasForo($checkToken['piloto'],$data['type'],$data['offset'],$data['limit'],$data['order'],$data['category'],0,$data['accesstoken']);
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}				
				}
				# ver Comentarios/Preguntas Pendientes por responder
				elseif(isset($data['pending']) && $data['pending'] == true && isset($data['type']) && $data['type'] !== ""){
					$data = validarPagiacion($data);
					
					$users_actives = datosSQL("Select * from ".TBL_COMENTARIOS." where type IN ('{$data['type']}') and reply IN ('0') and trash IN ('0') and piloto IN ({$checkToken["piloto"]}) order by f_query {$data['order']} LIMIT {$data['offset']}, {$data['limit']} ");
					if(isset($users_actives->error) && $users_actives->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
						foreach($users_actives->data As $elm){
							$elm['raiz_name'] = nameCategoryById($elm['raiz'],'forum');
							$elm['author'] = cargarNamePeopleForUserid($elm['author']);
							$jsonFinal->data[] = $elm;
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				# ver Comentarios/Preguntas especifica
				elseif(isset($data['id']) && $data['id'] > 0){
					$jsonFinal->data = array();
					$jsonFinal->error = false;
					$users_actives = datosSQL("Select * from ".TBL_COMENTARIOS." where id IN ('{$data['id']}') and trash IN ('0') and piloto IN ({$checkToken["piloto"]}) ");
					if(isset($users_actives->error) && $users_actives->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
						foreach($users_actives->data As $elm){
							$elm['raiz_name'] = nameCategoryById($elm['raiz'],'forum');
							$elm['author'] = cargarNamePeopleForUserid($elm['author']);
							$jsonFinal->data[] = $elm;
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				else{
					$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
				}
			}else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		# Respondiendo
		else if(isset($data['action']) && $data['action'] == 'response'){
			if(isset($permisos_user->response) && $permisos_user->response == true){
				# Un comentario especifico
				if(isset($data['id']) && isset($data['response'])){
					$data['id'] = (int) $data['id'];
					$data['f_comment'] = date("Y-m-d H:i:s",time());
					
					$result = crearSQL("UPDATE ".TBL_COMENTARIOS." SET comment=?,reply=?,f_comment=? WHERE id='{$data['id']}'",array($data['response'],1,$data['f_comment']));
					
					if($result->error == false){
						$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
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
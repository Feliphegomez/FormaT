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
		$permisos_user = $checkToken['permisos_cargos']->quiz; # Permisos para la pagina
		
		if(!isset($data['page']))
		{
			$data['page'] = 'quiz';
		}
		
		#pagina de quiz
		if(isset($data['page']) && $data['page'] == "quiz"){
			# Eliminando
			if(isset($data['action']) && $data['action'] == 'delete'){
				if(isset($permisos_user->delete) && $permisos_user->delete == true){
					# Eliminar quiz activo
					if(isset($data['type']) && $data['type'] == 'current'){
						$cosulta = datosSQL("Select * from ".TBL_F5_TEMAS." where piloto IN ({$checkToken['piloto']}) and view='1' and trash='0' ORDER BY fecha_creation DESC LIMIT 1");
						if(isset($cosulta->error) && $cosulta->error == false && isset($cosulta->data[0])){
							$change = crearSQL("UPDATE ".TBL_F5_TEMAS." SET trash=?,view=? WHERE id='{$cosulta->data[0]["id"]}'",array(1,0));
							if($change->error == false){
								$jsonFinal = $success_API->{'5'}; # Success 5 - Contenido eliminado con exito.
							}
							else{
								$jsonFinal = $errores_API->{'300'}; # Error 300 - Ocurrio un problema eliminando la información, Intente nuevamente. 
							};
						}
						else{
							$jsonFinal = $success_API->{'6'}; # Success 6 - No hay quiz activo para eliminar.
						}
					}
					# Eliminar quiz Especifico
					else if(isset($data['id']) && $data['id'] > 0){
						$change = crearSQL("UPDATE ".TBL_F5_TEMAS." SET trash=?,view=? WHERE id='{$data["id"]}'",array(1,0));
						if($change->error == false){
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
					if(isset($data['draft']) && $data['draft'] == true){
						$command = "INSERT INTO ".TBL_F5_TEMAS." ( title,piloto,view,trash ) VALUES (?,?,?,?)";
						$create = crearSQL($command,array("Nuevo Quiz.",$checkToken['piloto'],0,0));
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
			# Leyendo
			else if(isset($data['action']) && $data['action'] == 'view'){
				if(isset($permisos_user->view) && $permisos_user->view == true){
					
					# Ver borradores
					if(isset($data['draft']) && $data['draft'] == true){
						$cosulta = datosSQL("Select * from ".TBL_F5_TEMAS." where piloto IN ({$checkToken['piloto']}) and view='0' and trash='0' ");
						if(isset($cosulta->error) && $cosulta->error == false){
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							$jsonFinal->data = $cosulta->data;
						}
						else{
							$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
						};
					}
					# Ver quiz activo
					else if(isset($data['current']) && $data['current'] == true){
						$tma = datosSQL("Select * from ".TBL_F5_TEMAS." where piloto IN ({$checkToken['piloto']}) and view='1' and trash='0' ORDER BY fecha_creation DESC LIMIT 1");
						if(isset($tma->error) && $tma->error == false){
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							if(isset($tma->data[0])){ $jsonFinal->quiz = json_decode(json_encode($tma->data[0])); }else{ $jsonFinal->quiz = new stdClass(); }
							$jsonFinal->quiz->querys = array();
							$jsonFinal->quiz->presencia = false;
							$jsonFinal->quiz->result = new stdClass();
							
							// DETECTAR PRESENCIA EN QUIZ
							if(isset($jsonFinal->quiz->id)){
								$respuesta = datosSQL("Select * from ".TBL_F5_RESPUESTAS." where topic='{$jsonFinal->quiz->id}' and user='{$checkToken['id']}'");
								if(isset($respuesta->error) && $respuesta->error == false && isset($respuesta->data[0])){
									$jsonFinal->quiz->presencia = true;
											
									$responses = datosSQL("Select * from ".TBL_F5_RESPUESTAS." where topic='{$jsonFinal->quiz->id}' and user IN ({$checkToken['id']}) ORDER BY fecha_presenta ASC");
									if(isset($responses->error) && $responses->error == false && isset($responses->data[0])){
										$responses->data[0]['result'] = json_decode($responses->data[0]['result']);
										$jsonFinal->quiz->result = ($responses->data[0]);
									}
								}
							}
							
							// DETECTAR SI EXITEN PREGUNTAS
							if(isset($jsonFinal->quiz->id)){
								$querys = datosSQL("Select * from ".TBL_F5_PREGUNTAS." where topic='{$tma->data[0]['id']}'");
								if(isset($querys->error) && $querys->error == false){
									$querys = datosSQL("Select * from ".TBL_F5_PREGUNTAS." where topic='{$tma->data[0]['id']}'");
									if(isset($querys->error) && $querys->error == false && isset($querys->data[0])){
										foreach($querys->data As $query){
											$query['response'] = json_decode($query['response']);
											$jsonFinal->quiz->querys[] = $query;
										}
									}
									else{
										$jsonFinal->message = "Quiz no tiene preguntas.";
									}
								}
							}
						}
						else{
							$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
						};
						
					}
					# Ver quiz especifico
					else if(isset($data['id']) && $data['id'] > 0){
						$tma = datosSQL("Select * from ".TBL_F5_TEMAS." where id='{$data['id']}' and piloto IN ({$checkToken['piloto']}) ");
						if(isset($tma->error) && $tma->error == false && isset($tma->data[0])){
							
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							if(isset($tma->data[0])){ $jsonFinal->quiz = json_decode(json_encode($tma->data[0])); }else{ $jsonFinal->quiz = new stdClass(); }
							$jsonFinal->quiz->querys = array();
							$jsonFinal->quiz->presencia = false;
							$jsonFinal->quiz->result = new stdClass();
						
								
							$respuesta = datosSQL("Select * from ".TBL_F5_RESPUESTAS." where topic='{$tma->data[0]['id']}' and user='{$checkToken['id']}'");
							if(isset($respuesta->error) && $respuesta->error == false && isset($respuesta->data[0])){
								$jsonFinal->quiz->presencia = true;
										
								$responses = datosSQL("Select * from ".TBL_F5_RESPUESTAS." where topic='{$tma->data[0]['id']}' and user IN ({$checkToken['id']}) ORDER BY fecha_presenta ASC");
								if(isset($responses->error) && $responses->error == false && isset($responses->data[0])){
									$responses = $responses->data[0];
									$responses['result'] = json_decode($responses['result']);
									$jsonFinal->quiz->result = ($responses);
								}
							}
							// DETECTAR PRESENCIA EN QUIZ
							$querys = datosSQL("Select * from ".TBL_F5_PREGUNTAS." where topic='{$tma->data[0]['id']}'");
							if(isset($querys->error) && $querys->error == false){
								foreach($querys->data As $query){
									$query['response'] = json_decode($query['response']);
									
									$jsonFinal->quiz->querys[] = $query;
								}
								
								if(isset($checkToken['permisos_cargos']->export->quiz) && $checkToken['permisos_cargos']->export->quiz == true && isset($data['export']) && $data['export'] == true){
									$checkToken['permisos_cargos']->export = new stdClass();
									$checkToken['permisos_cargos']->export->quiz = true;
									$permisos_user->export = $checkToken['permisos_cargos']->export;
							
									$sql = datosSQL("Select * from ".TBL_F5_RESPUESTAS." where topic IN ({$tma->data[0]['id']}) ");
									
									$results = array();
									if($sql->error === false){
										foreach($sql->data As $result){
											$result['result'] = json_decode($result['result']);
											$result['user'] = cargarNamePeopleForUserid($result['user']);
											$results[] = $result;
										}
									}
									$jsonFinal->export = $results;
								}
							}
							else{
								$jsonFinal->message = "Quiz no tiene preguntas.";
							}
						}
						else{
							$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
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
			# Modificando
			else if(isset($data['action']) && $data['action'] == 'change'){
				if(isset($permisos_user->edit) && $permisos_user->edit == true){
					# Activar especifico
					if(isset($data['id']) && isset($data['id']) > 0 && isset($data['active']) && isset($data['active']) == true){
						$change = crearSQL("UPDATE ".TBL_F5_TEMAS." SET view=?,trash=? WHERE id='{$data['id']}' ",array(1,0));
					
						if($change->error==false){
							$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
						}
						else{
							$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente
						}
					}
					# Modificar especifico solo campo view
					else if(isset($data['id']) && isset($data['id']) > 0 && isset($data['view']) && isset($data['view']) !== ""){
						$change = crearSQL("UPDATE ".TBL_F5_TEMAS." SET view=? WHERE id='{$data['id']}' ",array((boolean) $data['view']));
					
						if($change->error==false){
							$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
						}
						else{
							$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente
						}
					}
					else if(isset($data['id']) && isset($data['id']) > 0 && isset($data['title']) && isset($data['title']) !== ""){
						$change = crearSQL("UPDATE ".TBL_F5_TEMAS." SET title=? WHERE id='{$data['id']}' ",array($data['title']));
					
						if($change->error==false){
							$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
						}
						else{
							$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente
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
			# Historial
			elseif(isset($data['action']) && $data['action'] == "history"){
				if(isset($permisos_user->history) && $permisos_user->history == true){
					if(isset($checkToken['permisos_cargos']->quiz->history) && $checkToken['permisos_cargos']->quiz->history == true){
						$history = datosSQL("Select * from ".TBL_F5_TEMAS." where piloto IN ({$checkToken['piloto']}) and view='0' and trash='1'");
						if(isset($history->error) && $history->error == false){
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							$jsonFinal->data = $history->data;
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
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				};
			} 
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		#pagina de Questions
		else if(isset($data['page']) && $data['page'] == "questions"){
			# Eliminando
			if(isset($data['action']) && $data['action'] == 'delete'){
				if(isset($permisos_user->delete) && $permisos_user->delete == true){
					if(isset($data['id']) && $data['id'] > 0){
						$data['id'] = (int) $data['id'];
						$delete = eliminarSQL("DELETE FROM ".TBL_F5_PREGUNTAS." WHERE id='{$data['id']}' ");
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
					if(
						isset($data['topic']) && $data['topic'] !== '' 
						&& isset($data['query']) && $data['query'] !== '' 
						&& isset($data['response']) && $data['response'] !== ''
					){
						if($data['response'] == json_encode(json_decode($data['response']))){
							$result = crearSQL("INSERT INTO ".TBL_F5_PREGUNTAS." ( query,response,topic ) VALUES (?,?,?)",
							array($data['query'],$data['response'],$data['topic']));
							if($result->error==false){
								$data['response'] = json_decode($data['response']);
								$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
								$jsonFinal->id = $result->last_id;
							}else{
								$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
							}
						}else{
							$jsonFinal = $errores_API->{'500'}; # Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
							$jsonFinal->message = "Respuestas invalidas";
						}
					}
				}
				else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				}
			}
			# Leyendo
			else if(isset($data['action']) && $data['action'] == 'view'){
				if(isset($permisos_user->view) && $permisos_user->view == true){
					if(isset($data['id']) && $data['id'] > 0){
						$data['id'] = (int) $data['id'];
						
						$sql = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ({$checkToken['piloto']}) and view IN (1) and id IN ('{$data['id']}') ");
						if($sql->error === false){
							$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
							$jsonFinal->data = $sql->data[0];
						}
						else{
							$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
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
			# Modificando
			else if(isset($data['action']) && $data['action'] == 'change'){
				if(isset($permisos_user->edit) && $permisos_user->edit == true){
					$jsonFinal = $success_API->{'15'}; # Success 15 - Contenido modificado con exito.
				}
				else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				}
			}
			# Historial
			elseif(isset($data['action']) && $data['action'] == "history"){
				if(isset($permisos_user->history) && $permisos_user->history == true){
					
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
				}
				else{
					$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
				};
			} 
			else{
				$jsonFinal = $errores_API->{'400'}; # Error 400 - Los campos son invalidos o estan incompletos.
			}
		}
		# Respuestas
		else if(isset($data['page']) && $data['page'] == "response" && isset($data['action']) && $data['action'] == "create"){
			if(isset($data['quiz']) && $data['quiz'] > 0 && isset($data['result']) && $data['result'] !== '' && json_decode($data['result']) == true){
				$data['result'] = json_decode($data['result']);
				$total = 0;
				foreach($data['result'] As $rsp){ if(isset($rsp->response->value) && $rsp->response->value == 'true'){ $total++; }; };
				
				$result = crearSQL("INSERT INTO ".TBL_F5_RESPUESTAS." ( topic,user,result,result_note ) VALUES (?,?,?,?)",
				array($data['quiz'],$checkToken['id'],json_encode($data['result']),$total));
				if($result->error==false){
					$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
					$jsonFinal->id = $result->last_id;
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

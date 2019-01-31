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
		$permisos_user = $checkToken['permisos_cargos']->calendary; # Permisos para la pagina
		
		# Eliminando
		if(isset($data['action']) && $data['action'] == 'delete'){
			if(isset($permisos_user->delete) && $permisos_user->delete == true){
				# Eliminando calendario especifico
				if(isset($data['id']) && $data['id'] > 0){
					$data['id'] = (int) $data['id'];
					$change = crearSQL("UPDATE ".TBL_CALENDARIO." SET trash=? WHERE id='{$data['id']}' ",array(1));
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
				
				### Nuevo Calendario
				if(
					isset($data['fecha']) && $data['fecha'] !== ''
					&& isset($data['hora_inicio']) && $data['hora_inicio'] !== ''
					&& isset($data['hora_fin']) && $data['hora_fin'] !== ''
					&& isset($data['lugar']) && $data['lugar'] !== ''
					&& isset($data['encargado']) && $data['encargado'] !== ''
					&& isset($data['category']) && $data['category'] !== ''
				){
					
					$command = "INSERT INTO ".TBL_CALENDARIO." ( fecha,hora_inicio,hora_fin,lugar,encargado,trash,piloto,category ) VALUES (?,?,?,?,?,?,?,?)";
					$cons = crearSQL($command,array($data['fecha'],$data['fecha'],$data['hora_fin'],$data['lugar'],$data['encargado'],0,$checkToken['piloto'],$data['category']));
					if(isset($cons->error) && $cons->error == false){
						$jsonFinal = $success_API->{'10'}; # Success 10 - Contenido creado con exito.
						$jsonFinal->id = $cons->last_id;
					}else{
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
				
				# Estilo Grantt
				if(isset($data['style']) && $data['style'] == 'gantt'){
					$addsql = '';
					if(isset($data['category']) && $data['category'] > 0){ $addsql .= "and category IN ({$data['category']}) "; };
					
					$response = datosSQL("Select * from ".TBL_CALENDARIO."  where piloto='{$checkToken['piloto']}' and trash='0' {$addsql} ORDER BY category ASC,fecha DESC");
					if(isset($response->error) && $response->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal_data = array();
						
						$arreglo = new stdClass();
						foreach($response->data as $Calendar){
							$Calendar['category_name'] = categoryNameById($Calendar['category']);
							$Calendar['piloto_name'] = pilotoNameById($Calendar['piloto']);
							$element = new stdClass();
							$id = ($Calendar['id']);
							$category = ($Calendar['category']);
							$category_name = $Calendar['category_name'];
							$from = $Calendar['fecha'].' '.$Calendar['hora_inicio'];
							$to = $Calendar['fecha'].' '.$Calendar['hora_fin'];
							$encargado = $Calendar['encargado'];
							$trash = $Calendar['trash'];
							$lugar = $Calendar['lugar'];
							$fActual = strtotime(date("Y-m-d H:i:s",time()));
							
							$color = 'ganttGray'; //"ganttBlue", "ganttGreen", "ganttRed", "ganttOrange", "ganttGray", "MyClass"
								
							if($trash == 1){ $color = 'ganttRed'; }
							else{
								if(strtotime($from) < $fActual && $fActual > strtotime($to)){ $color = "ganttOrange"; }
								elseif(strtotime($from) <= $fActual && $fActual <= strtotime($to)){ $color = "ganttGreen"; }
								elseif(strtotime($from) > $fActual && $fActual > strtotime($to)){ $color = "ganttRed"; }
								else{ $color = "ganttBlue"; };
							}
							
							$values = array(
								"from"=>"".($from)."",
								"to"=>"".($to)."",
								"label"=>$category_name,
								"desc"=>"<b>Capa:</b> {$category_name}<br><b>Encargado</b>: {$encargado}<br><b>Lugar:</b> {$lugar}<br><b>Inicio:</b> {$from}<br><b>Fin:</b> {$to}",
								"customClass"=>$color,
								"dataObj"=>$Calendar,
							);
							
							$element->id = $category;
							
							if(isset($data['order']) && $data['order'] == 'formador'){
								$element->name = $encargado;
								$element->desc = $category_name;
							}
							else if(isset($data['order']) && $data['order'] == 'tema'){
								$element->name = $category_name;
								$element->desc = '';
							}
							else{
								$element->name = $category_name;
								$element->desc = $encargado;
							}
							
							$element->values = array();
							$element->values[] = $values;
							
							if(isset($data['order']) && $data['order'] == 'formador'){
								$label = $encargado;
							}
							else if(isset($data['order']) && $data['order'] == 'tema'){ $label = $category; }
							else{ $label = $id; }
							
							if(isset($arreglo->{$label})){ $arreglo->{$label}->values[] = $values; }
							else{ $arreglo->{$label} = $element; }
						}
						foreach($arreglo As $elmOK){ $jsonFinal_data[] = $elmOK; }
						$jsonFinal->data = $jsonFinal_data;
					}
				}
				
				# Ver calendario especifico
				else if(isset($data['id']) && $data['id'] > 0){
					$response = datosSQL("Select * from ".TBL_CALENDARIO."  where piloto='{$checkToken['piloto']}' and trash='0' and id IN ({$data['id']})");
					if(isset($response->error) && $response->error == false && $response->data[0]){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = $response->data[0];
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
			}
			else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
		}
		
		# Modificando
		else if(isset($data['action']) && $data['action'] == 'change'){
			if(isset($permisos_user->edit) && $permisos_user->edit == true){
				# modificar
				if(
					isset($data['id']) && $data['id'] !== ''
					&& isset($data['fecha']) && $data['fecha'] !== ''
					&& isset($data['hora_inicio']) && $data['hora_inicio'] !== ''
					&& isset($data['hora_fin']) && $data['hora_fin'] !== ''
					&& isset($data['lugar']) && $data['lugar'] !== ''
					&& isset($data['encargado']) && $data['encargado'] !== ''
					&& isset($data['category']) && $data['category'] !== ''
				){
					
					$command = "UPDATE ".TBL_CALENDARIO." SET fecha=?,hora_inicio=?,hora_fin=?,lugar=?,encargado=?,category=? WHERE id='{$_POST['id']}'";
					$create = crearSQL($command,array($data['fecha'],$data['hora_inicio'],$data['hora_fin'],$data['lugar'],$data['encargado'],$data['category']));
					if(isset($create->error) && $create->error == false){
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
			else{
				$jsonFinal = $errores_API->{'200'}; # Error 200 - Permisos insuficientes para realizar está accion.
			}
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

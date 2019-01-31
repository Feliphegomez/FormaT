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
		$permisos_user = $checkToken['permisos_cargos']->{'devices'}; # Permisos para la pagina
		
		# Leyendo
		if(isset($data['action']) && $data['action'] == 'view'){
			if(isset($permisos_user->view) && $permisos_user->view == true){				
				# ver dispositivo especifico
				if(isset($data['id']) && $data['id'] > 0){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$device_id = $data['id'];
					$sql_devices_general = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where trash='0' and id IN ({$device_id}) ");
					if(isset($sql_devices_general->error) && $sql_devices_general->error == false){
						$devices_general = $sql_devices_general->data[0];
						$devices_general['size'] = json_decode($devices_general['size']);
						$devices_general['manufacturer_name'] = nameManufacturerById($devices_general['manufacturer']);
						$devices_general['type_name'] = namePlataformaById($devices_general['type']);
						$devices_general['image_icon_url'] = urlImageByIdAndAccessToken($devices_general['image_icon'],$data["accesstoken"]);
						
						$sql_topics_virtualsteps_general = datosSQL("Select * from ".TBL_DV_TOPICS." where trash='0' and device IN ({$devices_general['id']}) ");
						if(isset($sql_topics_virtualsteps_general->error) && $sql_topics_virtualsteps_general->error == false){
							
							foreach($sql_topics_virtualsteps_general->data As $topic){
								
								$sql_topics_virtualsteps_general = datosSQL("Select * from ".TBL_DV_VIRTUALSTEPS." where trash='0' and device IN ({$devices_general['id']}) and topic IN ('{$topic['id']}')");
								if(isset($sql_topics_virtualsteps_general->error) && $sql_topics_virtualsteps_general->error == false){
									foreach($sql_topics_virtualsteps_general->data As $item){
										$item['instructions'] = json_decode($item['instructions']);
										$topic['items'][] = $item;
									}
								}
								
								$devices_general['topics'][] = $topic;
							}
						}
						
					}else{ $devices_general = array(); }
					
					$jsonFinal->error = false;
					$jsonFinal->data = $devices_general;
				}
				# ver manual especifico
				else if(isset($data['vsteps_id']) && $data['vsteps_id'] > 0 && isset($data['device_id']) && $data['device_id'] > 0){
					
					$sql_devices_general = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where trash='0' and id IN ({$data['device_id']})");
					if(isset($sql_devices_general->error) && $sql_devices_general->error == false && isset($sql_devices_general->data[0])){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = new stdClass();
					
						$sql_devices_general->data[0]['size'] = json_decode($sql_devices_general->data[0]['size']);
						if(!isset($sql_devices_general->data[0]['size']->maxWidth)){ $sql_devices_general->data[0]['size']->maxWidth = $sql_devices_general->data[0]['size']->width; }
						if(!isset($sql_devices_general->data[0]['size']->maxHeight)){ $sql_devices_general->data[0]['size']->maxHeight = $sql_devices_general->data[0]['size']->height; }
						
						$jsonFinal->data->device = $sql_devices_general->data[0];
						$jsonFinal->data->manufacturer = new stdClass();
						$jsonFinal->data->manual = new stdClass();
						
						$sql_manufacturer_general = datosSQL("Select * from ".TBL_DV_MARCAS." where trash='0' and id IN ({$sql_devices_general->data[0]['manufacturer']}) ");
						if(isset($sql_manufacturer_general->error) && $sql_manufacturer_general->error == false){								
							$sql_manufacturer_general->data[0]['plataforma_name'] = namePlataformaById($sql_manufacturer_general->data[0]['type']);
							$sql_manufacturer_general->data[0]['plataforma_image'] = picturePlataformaById($sql_manufacturer_general->data[0]['type']);
															
							$jsonFinal->data->manufacturer = $sql_manufacturer_general->data[0];
						}
						
						$sql_virtualsteps_general = datosSQL("Select * from ".TBL_DV_VIRTUALSTEPS." where trash='0' and id IN ({$data['vsteps_id']}) and device IN ('{$sql_devices_general->data[0]['id']}')");
						if(isset($sql_virtualsteps_general->error) && $sql_virtualsteps_general->error == false && isset($sql_virtualsteps_general->data[0])){
							$sql_virtualsteps_general->data[0]['instructions'] = json_decode($sql_virtualsteps_general->data[0]['instructions']);
							$sql_virtualsteps_general->data[0]['instructions'] = ($sql_virtualsteps_general->data[0]['instructions']);
							
							$jsonFinal->data->manual = $sql_virtualsteps_general->data[0];
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				# listar dispositivos para sidear
				elseif(isset($data['list']) && $data['list'] == true && isset($data['type']) && $data['type'] == "sidebar"){
					$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
					$jsonFinal->data = array();
					
					$plataformas = cargarPlataformas();
					if(isset($plataformas[0])){
						foreach($plataformas as $plat){
							$plat['tree'] = cargarMarcasForPlataformaId($plat['id']);
							$jsonFinal->data[] = $plat;
						}
					}
					else{
						$jsonFinal = $errores_API->{'600'}; # Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
					}
				}
				# ver dispositivos segun faricante y tipo de dispositivo
				elseif(isset($data['view_devices']) && $data['view_devices'] == true && isset($data['type']) && $data['type'] > 0 && isset($data['manufacturer']) && $data['manufacturer'] > 0){
					$data = validarPagiacion($data);				
					
					$sql_devices_general = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where trash='0' and type IN ({$data['type']}) and manufacturer IN ({$data['manufacturer']})  ORDER BY name {$data['order']} LIMIT {$data['offset']}, {$data['limit']} ");
					if(isset($sql_devices_general->error) && $sql_devices_general->error == false){
						$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
						$jsonFinal->data = array();
					
						foreach($sql_devices_general->data As $device){
							$device['size'] = json_decode($device['size']);
							$device['image_icon_url'] = urlImageByIdAndAccessToken($device['image_icon'],$data["accesstoken"]);
							
							$jsonFinal->data[] = $device;
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
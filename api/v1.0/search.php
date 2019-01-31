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
		
		if(!isset($data['limit'])){ $data['limit'] = 50; };
		$data = validarPagiacion($data);
		
		if(isset($data['type']) && $data['type'] == 'list' && isset($data['q']) && $data['q'] !== ''){
			$jsonFinal = $success_API->{'25'}; # Success 25 - Contenido cargado con exito.
			$jsonFinal->total = 0;
			$jsonFinal->data = array();
			
			
			$qq = explode(' ',$data['q']);
			
			## AGREGAR ARTICLES Y ECARDS A LOS RESULTADOS DE BUSQUEDA
			if(isset($permisos_user->articles->view) && $permisos_user->articles->view == true || isset($permisos_user->ecards->view) && $permisos_user->ecards->view == true){
				$sql_types = array();
				if(isset($permisos_user->articles->view) && $permisos_user->articles->view == true){ $sql_types[] = 'articles'; }
				if(isset($permisos_user->ecards->view) && $permisos_user->ecards->view == true){ $sql_types[] = 'ecards'; }
				$sql_types = "'".implode("','",$sql_types)."'";
				$data['sql_types'] = $sql_types;
				
				
				$sql_general = " and trash='0' and public='1' and piloto='{$checkToken['piloto']}' and type IN ({$sql_types}) ";
				
				$qq2 = " OR title LIKE '%".implode("%' {$sql_general} OR title LIKE '%",$qq)."%' {$sql_general}";
				$qq2 .= " OR tags LIKE '%".implode("%' {$sql_general} OR tags LIKE '%",$qq)."%' {$sql_general}";
				
				$consulta = datosSQL("Select * from ".TBL_CONTENIDO." where title LIKE '%{$data['q']}%' {$sql_general} {$qq2}  LIMIT {$data['offset']}, {$data['limit']}");
				if(isset($consulta->data[0]) && isset($consulta->error) && $consulta->error == false){
					foreach($consulta->data As $element){
						$feed = parseArticles($element,$data['accesstoken']);
							$arreglo = new stdClass();
							$arreglo->id = $feed['id'];
							$arreglo->title = $feed['title'];
							$arreglo->type = $feed['type'];
							$arreglo->thumbnail = $feed['thumbnail'];
							$arreglo->thumbnail_url = $feed['thumbnail_url'];
							$arreglo->category = $feed['category'];
							$arreglo->category_name = ($feed['category_name']);
							$arreglo->direct_url = '#';
							if($element['type'] == 'articles' || $element['type'] == 'ecards'){ $arreglo->direct_url = url_site."/index.php?pageActive=single&type={$element['type']}&id_ref=".$element['id']; };
							
							$jsonFinal->total++;
							$jsonFinal->data[] = $arreglo;
					}
				}
			}
			
			if(isset($permisos_user->devices->view) && $permisos_user->devices->view == true){
				
				## AGREGAR MANUALES A LOS RESULTADOS DE BUSQUEDA
				$sql_general = " and trash='0' ";
				$qq3 = " OR name LIKE '%".implode("%' {$sql_general} OR instructions LIKE '%",$qq)."%' {$sql_general}";
				#$jsonFinal->q3 = $qq3;
				
				$consulta = datosSQL("Select * from ".TBL_DV_VIRTUALSTEPS." where name LIKE '%{$data['q']}%' {$sql_general} {$qq3} ");
				if(isset($consulta->data[0]) && isset($consulta->error) && $consulta->error == false){
					foreach($consulta->data As $element){
						$arreglo = new stdClass();
						$arreglo->id = $element['id'];
						$arreglo->title = nameDeviceById($element['device'])." - ".$element['name'];
						$arreglo->type = 'simulators';
						$arreglo->thumbnail = pictureDeviceById($element['device']);
						$arreglo->category = $element['topic'];
						$arreglo->category_name = nameTopicDevicesById($element['topic']);
						$arreglo->thumbnail_url = url_api."/pictures.php?accesstoken={$data['accesstoken']}&id={$arreglo->thumbnail}&thumb=true&zoom=0.2";
						
						$arreglo->direct_url = '#';
						$arreglo->direct_url = url_site."/index.php?pageActive=view-vstep&ref_id={$element['id']}&device_id={$element['device']}&topic={$element['topic']}";
						
						$jsonFinal->total++;
						$jsonFinal->data[] = $arreglo;
					} 
				}
				
				## AGREGAR DISPOSITIVOS A LOS RESULTADOS DE BUSQUEDA
				$sql_general = " and trash='0' ";
				$qq4 = " OR name LIKE '%".implode("%' {$sql_general} OR name LIKE '%",$qq)."%' {$sql_general}";
				#$jsonFinal->q4 = $qq4;
				
				$consulta = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where name LIKE '%{$data['q']}%' {$sql_general} {$qq4} ");
				if(isset($consulta->data[0]) && isset($consulta->error) && $consulta->error == false){
					foreach($consulta->data As $element){
						$arreglo = new stdClass();
						$arreglo->id = $element['id'];
						$arreglo->title = nameManufacturerById($element['manufacturer'])." - ".$element['name'];
						$arreglo->type = 'devices';
						$arreglo->thumbnail = ($element['image_icon']);
						$arreglo->category = $element['type'];
						$arreglo->category_name = namePlataformaById($element['type']);
						$arreglo->thumbnail_url = url_api."/pictures.php?accesstoken={$data['accesstoken']}&id={$arreglo->thumbnail}&thumb=true&zoom=0.2";
						
						$arreglo->direct_url = '#';
						$arreglo->direct_url = url_site."/index.php?pageActive=explore-category&type=devices&device_type={$arreglo->category}&device_id={$element['id']}";
						
						
						
						$jsonFinal->total++;
						$jsonFinal->data[] = $arreglo;
					} 
				}
				
				## AGREGAR MARCAS A LOS RESULTADOS DE BUSQUEDA
				$sql_general = " and trash='0' ";
				$qq5 = " OR name LIKE '%".implode("%' {$sql_general} OR instructions LIKE '%",$qq)."%' {$sql_general}";
				$jsonFinal->q5 = $qq5;
				$consulta = datosSQL("Select * from ".TBL_DV_MARCAS." where name LIKE '%{$data['q']}%' {$sql_general} {$qq5} ");
				if(isset($consulta->data[0]) && isset($consulta->error) && $consulta->error == false){
					foreach($consulta->data As $element){
						$arreglo = new stdClass();
						$arreglo->id = $element['id'];
						$arreglo->category = $element['type'];
						$arreglo->category_name = namePlataformaById($element['type']);
						$arreglo->title = $arreglo->category_name." - ".$element['name'];
						$arreglo->type = 'manufacturer';
						$arreglo->thumbnail = ($element['image_icon']);
						$arreglo->thumbnail_url = url_api."/pictures.php?accesstoken={$data['accesstoken']}&id={$arreglo->thumbnail}&thumb=true&zoom=0.2";
						
						$arreglo->direct_url = '#';
						$arreglo->direct_url = url_site."/index.php?pageActive=explore-category&type=devices&device_type={$element['type']}&device_manufacturer={$element['id']}";
						 
						$jsonFinal->total++;
						$jsonFinal->data[] = $arreglo;
					}
				}
			}
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
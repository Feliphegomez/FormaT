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
		
		if(isset($checkToken['permisos_cargos']->export->quiz) && $checkToken['permisos_cargos']->export->quiz == true){
			
			if(isset($data['type']) && $data['type'] == "people" && isset($data['action']) && $data['action'] == "export" && isset($data['id']) && $data['id'] > 0){
				$sqlConsulta = datosSQL("Select * from ".TBL_UPLOADS_TEMP." where id='{$data['id']}'");
				if(isset($sqlConsulta->error) && $sqlConsulta->error == false && $sqlConsulta->data[0]){
					$hoja = $sqlConsulta->data[0];
					$hoja['columnas'] = json_decode($hoja['columnas']);
					$hoja['datos'] = json_decode($hoja['datos']);
					
					$json_final = array();
					
					$jsonFinal->update = false;
					if(isset($data['update']) && $data['update'] > 0){
						$jsonFinal->update = true;
						$data['update'] = ((int) $data['update'])-1;
					}
					
					if($jsonFinal->update == true){
						$it = $data['update'];
						$it_break = $data['update']+1;
					}else{
						$it = 0;
						$it_break = (count($hoja['datos']));
					}
					
					for ($i = $it; ; $i++) {
						if ($i >= $it_break) {
							break;
						}
						if(
							isset($hoja['datos'][$i]->cedula) && $hoja['datos'][$i]->cedula !== ''
							&& isset($hoja['datos'][$i]->nombre) && $hoja['datos'][$i]->nombre !== ''
							&& isset($hoja['datos'][$i]->cod_cargo) && $hoja['datos'][$i]->cod_cargo !== ''
							&& isset($hoja['datos'][$i]->cargo) && $hoja['datos'][$i]->cargo !== ''
							&& isset($hoja['datos'][$i]->cliente) && $hoja['datos'][$i]->cliente !== ''
							&& isset($hoja['datos'][$i]->estado) && $hoja['datos'][$i]->estado !== ''
							&& isset($hoja['datos'][$i]->jefe_inmediato) && $hoja['datos'][$i]->jefe_inmediato !== ''
						){
							
						}
						if(!isset($hoja['datos'][$i]->login)){ $hoja['datos'][$i]->login = $hoja['datos'][$i]->cedula; }
						$arreglo = new stdClass();
						$arreglo->login = $hoja['datos'][$i]->login;
						$arreglo->cedula = $hoja['datos'][$i]->cedula;
						$arreglo->nombre = $hoja['datos'][$i]->nombre;
						$arreglo->user = strtolower($hoja['datos'][$i]->login);
						
						$arreglo->cod_grado = (int) ($hoja['datos'][$i]->cod_grado);
						$arreglo->grado = (int) grado_createAlt_newPeopleBD($hoja['datos'][$i]->cod_grado,$hoja['datos'][$i]->grado_del_cargo);
						$arreglo->grado_name = $hoja['datos'][$i]->cargo;
						$arreglo->rol = $arreglo->grado;
						
						$arreglo->cod_cargo = (int) ($hoja['datos'][$i]->cod_cargo);
						$arreglo->cargo = (int) cargo_createAlt_newPeopleBD($hoja['datos'][$i]->cod_cargo,$hoja['datos'][$i]->cargo);
						$arreglo->cargo_name = $hoja['datos'][$i]->cargo;
						
						$arreglo->piloto = (int) cliente_createAlt_newPeopleBD($hoja['datos'][$i]->cliente);
						$arreglo->piloto_name = ($hoja['datos'][$i]->cliente);
						
						$arreglo->estado = (int) estado_createAlt_newPeopleBD($hoja['datos'][$i]->estado);
						$arreglo->estado_name = ($hoja['datos'][$i]->estado);
						
						$arreglo->supervisor = (int) jefe_createAlt_newPeopleBD($hoja['datos'][$i]->ced_jefe_inmediato,$hoja['datos'][$i]->jefe_inmediato,$hoja['datos'][$i]->cargo_jefe_inmediato);

						$date = str_replace('/', '-', $hoja['datos'][$i]->fecha_ingreso);
						$arreglo->fecha_ingreso = date('Y-m-d', strtotime($date));
						
						$arreglo->genero = $hoja['datos'][$i]->genero;
						
						$arreglo->more = json_encode($hoja['datos'][$i]);
						
						
						if($jsonFinal->update == true){
							$arreglo->create = newPeopleImport($arreglo);
						}else{
							$arreglo->create = false;
						}
						
						$json_final[] = $arreglo;
					}
					$jsonFinal->error = false;
					$jsonFinal->message = "Datos Cargados";
					$jsonFinal->data = $json_final;
				}else{
					$jsonFinal->message = "No encontrado";
				}
			}
			else if(isset($data['type']) && $data['type'] == "people" && isset($data['action']) && $data['action'] == "update" && isset($data['info']) && $data['info'] > 0){
				if(json_decode($data['info']) == true){
					$data['info'] = json_decode($data['info']);
					
					
					/*
					foreach($hoja['datos'] As $k){
						if(!isset($k->login)){ $k->login = $k->cedula; }
						$arreglo = new stdClass();
						$arreglo->login = $k->login;
						$arreglo->cedula = $k->cedula;
						$arreglo->nombre = $k->nombre;
						$arreglo->user = strtolower($k->login);
						
						$arreglo->cod_cargo = (int) ($k->cod_cargo);
						$arreglo->cargo = (int) cargo_createAlt_newPeopleBD($k->cod_cargo,$k->cargo);
						$arreglo->cargo_name = $k->cargo;
						
						$arreglo->piloto = (int) cliente_createAlt_newPeopleBD($k->cliente);
						$arreglo->piloto_name = ($k->cliente);
						
						$arreglo->estado = (int) estado_createAlt_newPeopleBD($k->estado);
						$arreglo->estado_name = ($k->estado);
						
						$arreglo->supervisor = (int) jefe_createAlt_newPeopleBD($k->ced_jefe_inmediato,$k->jefe_inmediato,$k->cargo_jefe_inmediato);

						$date = str_replace('/', '-', $k->fecha_ingreso);
						$arreglo->fecha_ingreso = date('Y-m-d', strtotime($date));
						#$arreglo->create = newPeopleImport($arreglo);						
						
						$json_final[] = $arreglo;
					}*/
					
				}
			}
		} else{
			$jsonFinal->message = "No tienes permisos para esta accion.";
		};
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

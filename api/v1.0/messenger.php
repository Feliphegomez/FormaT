<?php
#  --------------------------------------------------------------- #
#  author: FelipheGomez
#  author URL: http://demedallo.com
#  License: Creative Commons Attribution 3.0 Unported
#  License URL: http://creativecommons.org/licenses/by/3.0/
#  --------------------------------------------------------------- #
require_once("autoload.php");
	
$jsonFinal = new stdClass();
$jsonFinal->error = true;

if(isset($_POST) && count($_POST)>0){ $data = ($_POST); }
elseif(isset($_GET) && count($_GET)>0){ $data = ($_GET); }
elseif(isset($_DELETE) && count($_DELETE)>0){ $data = $_DELETE; }
else{ $data = array(); };

if(isset($data['accesstoken'])){
	$checkToken = chechear_AccessToken_CCyUser($data['accesstoken']);
	if($checkToken==false){
		$jsonFinal->error_message = "Accesstoken Invalido";
	}else{
		if(isset($data['update_connection']) && $data['update_connection'] == true){
			$fecha = new DateTime();
			$last_connection = ($fecha->getTimestamp());
			$userid = $checkToken['id'];
			$user = $checkToken['user'];
			
			$jsonFinal->data = new stdClass();
			$users_actives = datosSQL("Select * from ".TBL_USERS_ACTIVES." where userid IN ('{$userid}') and user IN ('{$user}')");
			if(isset($users_actives->error) && $users_actives->error == false && $users_actives->data[0]){
				try {
					$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sentencia = $conn->prepare("UPDATE ".TBL_USERS_ACTIVES." SET last_connection=FROM_UNIXTIME(?) WHERE userid='{$userid}' and user='{$user}'");
					$stmt = $sentencia->execute(array($last_connection));
					$jsonFinal->error = false;
					$jsonFinal->last_connection = $last_connection;
				}
				catch(PDOException $e)
				{
					$jsonFinal->error_message = $sql . "<br>" . $e->getMessage();
				}

			}else{
				try {
					$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$comando = "INSERT INTO ".TBL_USERS_ACTIVES." ( userid,user,last_connection ) VALUES (?,?,FROM_UNIXTIME(?))";
					$sentencia = $conn->prepare($comando);
					$insert = $sentencia->execute(array($userid,$user,$last_connection));
					$last_id = $conn->lastInsertId();
					if($insert==true){
						$jsonFinal->error = false;
						$jsonFinal->last_connection = $last_connection;
					}else{
						$jsonFinal->error_message = "Intenta nuevamente";
					}
				}
				catch(PDOException $e)
				{
					$jsonFinal->error_message = $e->getMessage();
				}
				$conn = null;
			}
			
			
		}
		
		else if(isset($data['list_people']) && $data['list_people'] == true){
			unset($checkToken['more']);
			$addSql = '';
			$jsonFinal->my = $checkToken;
			$jsonFinal->connection = date("Y-m-d H:i:s",time());
			$jsonFinal->viewGroups = array();
			
			#PARA TODOS LOS SQL
			$sqlParaTodo = " and id NOT IN ('{$checkToken['id']}')";
			
			#FUNCIONES
			function cargarTimeStamp($userid){
				$temp = datosSQL("Select * from ".TBL_USERS_ACTIVES." where userid IN ('{$userid}') ");
				if(isset($temp->error) && $temp->error == false && $temp->data[0]){
					return $temp->data[0]['last_connection'];
				}else{
					return 0;
				}
			};
			
			#VALIDAR SI ES JEFE
			$jsonFinal->boss = false;
			$jefeTemp = datosSQL("Select * from ".TBL_JEFES_PERSONAL." where name IN ('{$checkToken['nombre']}') ");
			if(isset($jefeTemp->error) && $jefeTemp->error == false && $jefeTemp->data[0]){
				$jsonFinal->boss = true;
				$jsonFinal->viewGroups[] = array('name'=>'Personal a cargo');
			}
			#SQL PARA JEFES
			if($jsonFinal->boss == true){
				$addSql .= (" OR supervisor IN ('{$jefeTemp->data[0]['id']}')");
			}
			
			#VALIDAR MI JEFE SI EXISTE Y SI NO ES EN BLANCO
			$jsonFinal->myBoss = false;
			$jsonFinal->myGroup = false;
			$miJefeTemp = datosSQL("Select * from ".TBL_JEFES_PERSONAL." where id IN ('{$checkToken['supervisor']}') ");
			if(isset($miJefeTemp->error) && $miJefeTemp->error == false && isset($miJefeTemp->data[0]) && $miJefeTemp->data[0]['name'] !== ''){
				$miJefeTemp2 = datosSQL("Select * from ".TBL_PERSONAL." where nombre IN ('{$miJefeTemp->data[0]['name']}') ");
				
				if(isset($miJefeTemp2->error) && $miJefeTemp2->error == false && $miJefeTemp2->data[0]){
					unset($miJefeTemp2->data[0]["more"]);
					$addSql .= " OR id IN ('{$miJefeTemp2->data[0]['id']}') ";
					
					$jsonFinal->myBoss = true;
					#AGREGAR COMPAÑEROS CON EL MISMO JEFE
					$addSql .= " OR supervisor IN ('{$checkToken["supervisor"]}') ";	
					$jsonFinal->myGroup = true;
					$jsonFinal->viewGroups[] = array('name'=>'Mi Jefe');
					$jsonFinal->viewGroups[] = array('name'=>'Mis Compañeros de grupo');
				}
			}
			
			#VALIDAR PERMISOS DE CARGOS
			$miCargoTemp = datosSQL("Select * from ".TBL_CARGOS." where id IN ('{$checkToken['cargo']}') ");
			if(isset($miCargoTemp->error) && $miCargoTemp->error == false && isset($miCargoTemp->data[0])){
				if(isset($miCargoTemp->data[0]["permisos"])){
					$miCargoTemp->data[0]["permisos"] = json_decode($miCargoTemp->data[0]["permisos"]);
					$rsp = array();
					foreach($miCargoTemp->data[0]["permisos"]->chat->group As $x=>$y){
						if($y == true){
							$rsp[] = $x;
							$r = nameCargoById($x);
							$jsonFinal->groups[] = array($x=>$r);
							
							$jsonFinal->viewGroups[] = array('name'=>$r);
						}
					}
							
					$addSql .= " OR cargo IN (".implode(',',$rsp).") ";	
					$jsonFinal->permisosSql = " OR cargo IN (".implode(',',$rsp).") ";
				}
				$jsonFinal->cargo  =$miCargoTemp->data[0]["permisos"]->chat;
			}
						
			#CARGAR PERSONAL
			$jsonFinal->friends = array();
			$temp = ("Select * from ".TBL_PERSONAL." where id IN ('{$checkToken['id']}') {$addSql}");
			$jsonFinal->sql = $temp;
			$temp = datosSQL("Select * from ".TBL_PERSONAL." where id IN ('{$checkToken['id']}') {$addSql}");
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				foreach($temp->data As $List1){
					unset($List1['more']);
					$List1["last_connection"] = cargarTimeStamp($List1['id']);
					$jsonFinal->friends[] = $List1;
				}
			}
			
			$jsonFinal->error = false;
			
		}
		
		else if(isset($data['chats_pending']) && $data['chats_pending'] == true){
			$aregloFinal = array();
			$temp = datosSQL("Select * from ".TBL_MSG_TALKS." where group_ids LIKE '%{$checkToken['id']}%' ORDER BY last_activity DESC LIMIT 10");
			
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				foreach($temp->data As $elem){
					$tempChats = datosSQL("Select * from ".TBL_MSG_CHATS." where enviado_para IN ({$elem['id']}) and enviado_por NOT IN ({$checkToken['id']}) and ids_reads NOT LIKE '%{$checkToken['id']}%' group by enviado_para ");
					
					$elem['message'] = array();
					if(isset($tempChats->error) && $tempChats->error == false && $tempChats->data[0]){							
						$i=-1;
						foreach($tempChats->data As $elem2){
							$i++;
							
							$tempChats->data[$i]['enviado_por'] = cargarNamePeopleForUserid($tempChats->data[$i]['enviado_por']);
							
							if(isset($tempChats->data[$i]['enviado_por']['avatar'])){
								
							}else{
								unset($tempChats->data[$i]);
							}
						}
						$elem['message'] = $tempChats->data[0];
							
						$elem['group_ids'] = explode(',',$elem['group_ids']);
						$elem['related_people'] = array();
						
						foreach($elem['group_ids'] As $el){
							$elem['related_people'][] = cargarNamePeopleForUserid($el);
						}
						
						$aregloFinal[] = $elem;
						
					}						
					
				}
			}
			$jsonFinal->data = $aregloFinal;
			$jsonFinal->error = false;
			
		}
		
		else if(isset($data['last_chats']) && $data['last_chats'] == true){
			$aregloFinal = array();
			$temp = datosSQL("Select * from ".TBL_MSG_TALKS." where group_ids LIKE '%{$checkToken['id']}%' ORDER BY last_activity DESC LIMIT 10");
			
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				foreach($temp->data As $elem){
					$elem['group_ids'] = explode(',',$elem['group_ids']);
					$elem['profiles'] = array();
					
					foreach($elem['group_ids'] As $el){
						$elem['profiles'][] = cargarNamePeopleForUserid($el);
					}
					
					$aregloFinal[] = $elem;
				}
			}
			$jsonFinal->data = $aregloFinal;
			$jsonFinal->error = false;
			
		}
		
		else if(isset($data['list_chats']) && $data['list_chats'] == true && isset($data['conversation']) && $data['conversation'] > 0){
			if(!isset($data['page'])){ $data['page'] = 1; };
			if(!isset($data['limit'])){ $data['limit'] = 10; };
			
			$data['page'] = (int) $data['page'];
			$data['limit'] = (int) $data['limit'];
			$data['offset'] = (($data['limit'] * $data['page']) - $data['limit']);
			$data['page_next'] = (($data['page']) + 1);
			
			$temp = datosSQL("Select * from ".TBL_MSG_CHATS." where enviado_para IN ('{$data['conversation'] }') ORDER BY fcreate DESC LIMIT {$data['offset']}, {$data['limit']} ");
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				foreach($temp->data As $elem){
					$elem['enviado_por'] = cargarNamePeopleForUserid($elem['enviado_por']);
					$elem['ids_reads'] = explode(',',$elem['ids_reads']);
					$elem['leerChat'] = false;
					if($elem['enviado_para'] <> $checkToken['id'] && isset($data['read_chat']) && $data['read_chat'] == true){
						if(!in_array($checkToken['id'],$elem['ids_reads'])){
							$elem['leerChat'] = leerChat($checkToken['id'],$elem['id']);
						}
					}
					
					$jsonFinal->data[] = $elem;
				}
				 
				
				$jsonFinal->data = array_reverse($jsonFinal->data);
			}else{
				$jsonFinal->data = array();
			}
			
			$jsonFinal->enviado_por = cargarNamePeopleForUserid($checkToken['id']);
			$sqk = "Select * from ".TBL_MSG_TALKS." where id IN ({$data['conversation']}) limit 1";
			$sqlConv = datosSQL($sqk);
			if(isset($sqlConv->error) && $sqlConv->error == false && $sqlConv->data[0]){
				$data['group_ids'] = (explode(',',$sqlConv->data[0]['group_ids']));
				
				foreach($data['group_ids'] As $per){
					$jsonFinal->enviado_para[] = cargarNamePeopleForUserid($per);
				}
				$jsonFinal->error = false;
			}
			
		}
		
		else if(isset($data['return']) && $data['return'] == "id_conversacion" && isset($data['list']) && $data['list'] !== ''){
			$data['list'] = (explode(',',$data['list']));
			$data['list'][] = $checkToken['id'];
			sort($data['list']);
			$arrlength = count($data['list']);
			$b = array();
			for($x = 0; $x < $arrlength; $x++) {
				$b[] = $data['list'][$x];
			}

			$data['list'] = implode(',',$b);
			
			$sql = "Select * from ".TBL_MSG_TALKS." where group_ids IN ('{$data['list']}') limit 1";
			$temp = datosSQL($sql);
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				$jsonFinal->error = false;
				$jsonFinal->id = $temp->data[0]['id'];
			}else{
				
				try {
					$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$comando = "INSERT INTO ".TBL_MSG_TALKS." ( group_ids ) VALUES (?)";
					$sentencia = $conn->prepare($comando);
					$insert = $sentencia->execute(array($data['list']));
					$last_id = $conn->lastInsertId();
					if($insert==true){
						$jsonFinal->error = false;
						$jsonFinal->id = str_pad($last_id, 32, "0", STR_PAD_LEFT);
					}else{
						$jsonFinal->error_message = "Intenta nuevamente";
					}
				}
				catch(PDOException $e)
				{
					$jsonFinal->error_message = $e->getMessage();
				}
				$conn = null;
			}
		}
		
		else if(isset($data['add_member']) && $data['add_member'] > 0 && isset($data['conversation']) && $data['conversation'] > 0){
			$sql = "Select * from ".TBL_MSG_TALKS." where id IN ('{$data['conversation']}') limit 1";
			$temp = datosSQL($sql);
			if(isset($temp->error) && $temp->error == false && $temp->data[0]){
				$data['list'] = (explode(',',$temp->data[0]['group_ids']));
				$data['list'][] = $data['add_member'];
				
				sort($data['list']);
				$arrlength = count($data['list']);
				$b = array();
				for($x = 0; $x < $arrlength; $x++) {
					$b[] = $data['list'][$x];
				}

				$data['list'] = array_unique($data['list']);
				$data['list'] = implode(',',$data['list']);
					
				try {
					$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
					$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$sentencia = $conn->prepare("UPDATE ".TBL_MSG_TALKS." SET group_ids=? WHERE id='{$temp->data[0]['id']}' ");
					$stmt = $sentencia->execute(array($data['list']));
					$jsonFinal->error = false;
				}
				catch(PDOException $e)
				{
					$jsonFinal->error_message = $sql . "<br>" . $e->getMessage();
				}
				
			}
		}
		
		else if(isset($data['chat_send']) && $data['chat_send'] == true && isset($data['to']) && $data['to'] > 0 && isset($data['message']) && $data['message'] !== ''){
			$fecha = new DateTime();
			$fechaActual = ($fecha->getTimestamp());
			$status = 1;
			#$data['message'] = strip_tags($data['message']); Sin ninguna etiqueta HTML
			$data['message'] = strip_tags($data['message'],'<img><s><b><i><span><a>'); // SELECCION de etiquetas HTML 
			$data['message'] = Censurar($data['message']); // SELECCION de etiquetas HTML 
			
			try {
				$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$comando = "INSERT INTO ".TBL_MSG_CHATS." ( enviado_por,enviado_para,message,fcreate,status ) VALUES (?,?,?,FROM_UNIXTIME(?),?)";
				$sentencia = $conn->prepare($comando);
				$insert = $sentencia->execute(array($checkToken['id'],$data['to'],$data['message'],$fechaActual,$status));
				$last_id = $conn->lastInsertId();
				if($insert==true){
					$jsonFinal->error = false;
					$jsonFinal->last_id = $last_id;
					$jsonFinal->message = "Mensaje Enviado con exito.";
					
					try {
						$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$sentencia = $conn->prepare("UPDATE ".TBL_MSG_TALKS." SET last_activity=FROM_UNIXTIME(?) WHERE id='{$data['to']}' ");
						$stmt = $sentencia->execute(array($fechaActual));
					}
					catch(PDOException $e)
					{
						$jsonFinal->error_message = $sql . "<br>" . $e->getMessage();
					}
					
				}else{
					$jsonFinal->error_message = "Intenta nuevamente";
				}
			}
			catch(PDOException $e)
			{
				$jsonFinal->error_message = $e->getMessage();
			}
			$conn = null;
		}
			
		else if(isset($data['action']) && $data['action'] == "view"){
			
			
		}
		elseif(isset($data['action']) && $data['action'] == "change"){
			
		}
		elseif(isset($data['action']) && $data['action'] == "delete"){
			
		}
		elseif(isset($data['action']) && $data['action'] == "pending"){
			$aregloFinal = array();
			$temp = datosSQL("Select * from ".TBL_MSG_TALKS." where group_ids LIKE '%{$checkToken['id']}%' ORDER BY last_activity DESC LIMIT 10");
			
			if(isset($temp->error) && $temp->error == false){
				foreach($temp->data As $elem){
					$tempChats = datosSQL("Select * from ".TBL_MSG_CHATS." where enviado_para IN ({$elem['id']}) and enviado_por NOT IN ({$checkToken['id']}) and ids_reads NOT LIKE '%{$checkToken['id']}%' group by enviado_para ");
					
					$elem['message'] = array();
					if(isset($tempChats->error) && $tempChats->error == false && $tempChats->data[0]){							
						$i=-1;
						foreach($tempChats->data As $elem2){
							$i++;
							
							$tempChats->data[$i]['enviado_por'] = cargarNamePeopleForUserid($tempChats->data[$i]['enviado_por']);
							
							if(isset($tempChats->data[$i]['enviado_por']['avatar'])){
								
							}else{
								unset($tempChats->data[$i]);
							}
						}
						$elem['message'] = $tempChats->data[0];
							
						$elem['group_ids'] = explode(',',$elem['group_ids']);
						$elem['related_people'] = array();
						
						foreach($elem['group_ids'] As $el){
							$elem['related_people'][] = cargarNamePeopleForUserid($el);
						}
						
						$aregloFinal[] = $elem;
						
					}						
					
				}
			}
			$jsonFinal->data = $aregloFinal;
			$jsonFinal->error = false;
			
		}
		
	}
}else{
	$jsonFinal->error_message = "Falta el accesstoken";
};

#FINAL
header('Content-Type: application/json');
echo json_encode($jsonFinal,JSON_PRETTY_PRINT);
return json_encode($jsonFinal,JSON_PRETTY_PRINT);
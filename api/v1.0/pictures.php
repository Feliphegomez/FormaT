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
		if((isset($data['id']) && $data['id'] > 0 && isset($data['action']) && $data['action'] == 'preview') || (isset($data['id']) && $data['id'] > 0 && !isset($data['action']))){
			$sql = datosSQL("Select * from ".TBL_IMAGENES_GLOBAL." where id='{$data['id']}' ");
			if($sql->error === false && isset($sql->data[0]['data'])){
				$Base64Img = $sql->data[0]['data'];
			}
			else{
				$path = '_docs/images/sorry-image-not-available.jpg'; # Imagen default
				$type = @pathinfo($path, PATHINFO_EXTENSION);
				$dataimage = @file_get_contents($path);
				$Base64Img = 'data:image/' . $type . ';base64,' . base64_encode($dataimage);
			}
			$Base64Img = @explode('data:image/',$Base64Img);
			$Base64Img = @explode(';base64,',$Base64Img[1]);
			$TypeImg = ($Base64Img[0]);
			$Base64Img = ($Base64Img[1]);
			
			if(!isset($Base64Img[0]) || !isset($Base64Img[1])){
				$path = '_docs/images/sorry-image-not-available.jpg'; # Imagen default
				$type = @pathinfo($path, PATHINFO_EXTENSION);
				$dataimage = @file_get_contents($path);
				$TypeImg = ($type);
				$Base64Img = base64_encode($dataimage);
			}
			
			
			if(!isset($data['out_type'])){ $data['out_type'] = $TypeImg; }
			elseif(isset($data['out_type']) && $data['out_type'] !== $TypeImg){ $data['out_type'] = $TypeImg; };
			
			$imageData = base64_decode($Base64Img);
			$source = imagecreatefromstring($imageData);
			
			
			if($data['out_type'] == 'gif'){
				header("Content-type: image/gif");
				//$source = imagecreatefromgif("data://image/gif;base64,".$Base64Img);
				$source = imagegif($source);
			}
			else if($data['out_type'] == 'png'){
				header("Content-type: image/png");
				$source = imagecreatefrompng("data://image/".$TypeImg.";base64,".$Base64Img);
				
				imageAlphaBlending($source, true);
				imageSaveAlpha($source, true);
				$source = imagepng($source);
			}
			else if($data['out_type'] == 'jpg' || $data['out_type'] == 'jpeg'){
				#$source = imagecreatefromjpeg("data://image/jpeg;base64:".$Base64Img);
				header("Content-type: image/jpeg");
				
				if(isset($data['thumb']) && $data['thumb'] == true){
				$source = imagecreatefromjpeg("data://image/".$TypeImg.";base64,".$Base64Img);
					
					if(isset($data['zoom']) && $data['zoom'] > 0){
						$porcentaje = $data['zoom'];
					}else{
						$porcentaje = 0.5;
					}
					
					$alto = ImageSY($source);
					$ancho = ImageSX($source);
												
					$nuevo_ancho = $ancho * $porcentaje;
					$nuevo_alto = $alto * $porcentaje;

					// Cargar
					$source = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
					$origen = imagecreatefromjpeg("data://image/".$TypeImg.";base64,".$Base64Img);

					// Cambiar el tamaño
					imagecopyresized($source, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
				}
				
				$source = imagejpeg($source);
			}
			imagedestroy($source);
			
		}
		
		
		else if(isset($data['action']) && $data['action'] == 'create'){
			if(isset($data['data'])){
				$result = crearSQL("INSERT INTO ".TBL_IMAGENES_GLOBAL." ( data ) VALUES (?)",array($data['data']));
				
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
<?php
	#  --------------------------------------------------------------- #
	#  author: FelipheGomez
	#  author URL: http://demedallo.com
	#  License: Creative Commons Attribution 3.0 Unported
	#  License URL: http://creativecommons.org/licenses/by/3.0/
	#  --------------------------------------------------------------- #
	
require_once('config/defined.php');
require_once('config/functions-global.php');


/* --------------------
 ERRORES DE LA API
-------------------- */ 
$errores_API = new stdClass();

# Error 50 - Parametros invalidos consulte la documentacion de la API
$errores_API->{'50'} = new stdClass();
$errores_API->{'50'}->error = true;
$errores_API->{'50'}->code_error = 50;
$errores_API->{'50'}->message = "Parametros invalidos consulte la documentacion de la API.";

# Error 100 - El token de acceso es invalido, prueba con otro o genera uno nuevamente
$errores_API->{'100'} = new stdClass();
$errores_API->{'100'}->error = true;
$errores_API->{'100'}->code_error = 100;
$errores_API->{'100'}->message = "El token de acceso es invalido, prueba con otro o genera uno nuevamete.";


# Error 110 - Falta el token de acceso
$errores_API->{'110'} = new stdClass();
$errores_API->{'110'}->error = true;
$errores_API->{'110'}->code_error = 110;
$errores_API->{'110'}->message = "Falta el token de acceso.";

# Error 200 - Permisos insuficientes para realizar está accion.
$errores_API->{'200'} = new stdClass();
$errores_API->{'200'}->error = true;
$errores_API->{'200'}->code_error = 200;
$errores_API->{'200'}->message = "Permisos insuficientes para realizar está accion.";

# Error 300 - Ocurrio un problema eliminando la información, Intente nuevamente.
$errores_API->{'300'} = new stdClass();
$errores_API->{'300'}->error = true;
$errores_API->{'300'}->code_error = 300;
$errores_API->{'300'}->message = "Ocurrio un problema eliminando la información, Intente nuevamente.";

# Error 400 - Los campos son invalidos o estan incompletos.
$errores_API->{'400'} = new stdClass();
$errores_API->{'400'}->error = true;
$errores_API->{'400'}->code_error = 400;
$errores_API->{'400'}->message = "Los campos son invalidos o estan incompletos.";

# Error 500 - Ocurrio un problema creando la información, Intente nuevamente.
$errores_API->{'500'} = new stdClass();
$errores_API->{'500'}->error = true;
$errores_API->{'500'}->code_error = 500;
$errores_API->{'500'}->message = "Ocurrio un problema creando la información, Intente nuevamente.";

# Error 600 - Ocurrio un problema cargado la información, Intente nuevamente.
$errores_API->{'600'} = new stdClass();
$errores_API->{'600'}->error = true;
$errores_API->{'600'}->code_error = 600;
$errores_API->{'600'}->message = "Ocurrio un problema cargado la información, Intente nuevamente.";

# Error 700 - Usuario no encontrado, compruebe sus datos e intente nuevamente.
$errores_API->{'700'} = new stdClass();
$errores_API->{'700'}->error = true;
$errores_API->{'700'}->code_error = 700;
$errores_API->{'700'}->message = "Usuario no encontrado, compruebe sus datos e intente nuevamente.";

# Error 710 - Ocurrio un problema cargado el usuario o perfil, Intente nuevamente.
$errores_API->{'710'} = new stdClass();
$errores_API->{'710'}->error = true;
$errores_API->{'710'}->code_error = 700;
$errores_API->{'710'}->message = "Ocurrio un problema cargado el usuario o perfil, Intente nuevamente.";

/* --------------------
 SUCCESS DE LA API
-------------------- */ 
$success_API = new stdClass();

# Success 5 - Contenido eliminado con exito.
$success_API->{'5'} = new stdClass();
$success_API->{'5'}->error = false;
$success_API->{'5'}->message = "Contenido eliminado con exito.";

# Success 6 - No hay quiz activo para eliminar.
$success_API->{'6'} = new stdClass();
$success_API->{'6'}->error = false;
$success_API->{'6'}->message = "No hay quiz activo para eliminar.";

# Success 10 - Contenido creado con exito.
$success_API->{'10'} = new stdClass();
$success_API->{'10'}->error = false;
$success_API->{'10'}->message = "Contenido creado con exito.";

# Success 15 - Contenido modificado con exito.
$success_API->{'15'} = new stdClass();
$success_API->{'15'}->error = false;
$success_API->{'15'}->message = "Contenido modificado con exito.";

# Success 20 - Contenido activado con exito.
$success_API->{'20'} = new stdClass();
$success_API->{'20'}->error = false;
$success_API->{'20'}->message = "Contenido activado con exito.";

# Success 25 - Contenido cargado con exito.
$success_API->{'25'} = new stdClass();
$success_API->{'25'}->error = false;
$success_API->{'25'}->message = "Contenido cargado con exito.";

# Success 30 - Sesion creada con exito.
$success_API->{'30'} = new stdClass();
$success_API->{'30'}->error = false;
$success_API->{'30'}->message = "Sesion creada con exito.";

# Success 32 - Sesion conectada con exito.
$success_API->{'32'} = new stdClass();
$success_API->{'32'}->error = false;
$success_API->{'32'}->message = "Sesion conectada con exito.";

# Success 34 - Sesion encontrada con exito.
$success_API->{'34'} = new stdClass();
$success_API->{'34'}->error = false;
$success_API->{'34'}->message = "Sesion encontrada con exito.";


/* --------------------
 REPARAR CAMPOS DE API / FIELDS / GET - POST 
-------------------- */
$jsonFinal = $errores_API->{'50'};

if(isset($_POST) && count($_POST)>0){ $data = ($_POST); }
elseif(isset($_GET) && count($_GET)>0){ $data = ($_GET); }
elseif(isset($_DELETE) && count($_DELETE)>0){ $data = $_DELETE; }
else{ $data = array(); };

function validarPagiacion($data){
	if(!isset($data['order'])){ $data['order'] = "DESC"; }else{ if(strtolower($data['order']) == 'asc' || strtolower($data['order']) == 'desc'){ $data['order'] = (string) $data['order']; }; };
	if(!isset($data['page']) || $data['page'] < 0){ $data['page'] = 1; }else{ $data['page'] = (int) $data['page']; };
	if(!isset($data['limit']) || $data['limit'] < 0){ $data['limit'] = 10; }else{ $data['limit'] = (int) $data['limit']; };	
	$data['offset'] = (int) (($data['limit'] * $data['page']) - $data['limit']);
	$data['page_next'] = $data['page']+1;
	return $data;
}

/* PAGINACION EJEMPLO */
/*
	if(!isset($data['order'])){ $data['order'] = "DESC"; }else{ $data['order'] = (string) $data['order']; };
	if(!isset($data['page'])){ $data['page'] = 1; }else{ $data['page'] = (int) $data['page']; };
	if(!isset($data['limit'])){ $data['limit'] = 10; }else{ $data['limit'] = (int) $data['limit']; };	
	$data['offset'] = (int) (($data['limit'] * $data['page']) - $data['limit']);
*/






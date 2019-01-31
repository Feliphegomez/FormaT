<?php 

/** INICIO **/
### Cargar Info del Perfil por Id del Personal
function userForId($userid){
	$check = datosSQL("Select * from ".TBL_PERSONAL." where id='{$userid}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		unset($check->data[0]['more']);
		return $check->data[0];
	}else{
		return array();
	}
};

/** SDK **/
### Crear Session En Servidor con el user/login o Cédula
// UTILIZADO EN LOGIN
function crearSessionForUserOrCedula($userOrCedula){
	$consulta = datosSQL("Select * from ".TBL_PERSONAL."  where user='{$userOrCedula}' OR cedula='{$userOrCedula}' ");
	
	if(isset($consulta->error) && $consulta->error == false && $consulta->data[0]){
		$accessToken = generate_AccessToken_CCyUser($consulta->data[0]['user'],$consulta->data[0]['cedula']);
		$consulta->data[0]['more'] = json_decode($consulta->data[0]['more']);
		$consulta->data[0]['cargo_name'] = nameCargoById($consulta->data[0]['cargo']);
		$consulta->data[0]['supervisor_name'] = nameJefeById($consulta->data[0]['supervisor']);
		$consulta->data[0]['piloto_name'] = namePilotoById($consulta->data[0]['piloto']);
		$consulta->data[0]['estado_name'] = nameEstadoById($consulta->data[0]['estado']);
		$consulta->data[0]['rol_name'] = nameRolById($consulta->data[0]['rol']);
		$consulta->data[0]['ejecutivo_de_experiencia_name'] = nameJefeById($consulta->data[0]['ejecutivo_de_experiencia']);
		$consulta->data[0]['avatar_url'] = urlImageByAvatar($consulta->data[0]['avatar'],$consulta->data[0]['genero'],$accessToken);
		
		$consulta->data[0]["permisos"] = json_decode(permisosByCargos($consulta->data[0]["cargo"]));
		
		unset($consulta->data[0]['more']);
		
		$arreglo = new stdClass();
		$arreglo->accessToken = $accessToken;
		$arreglo->session = $consulta->data[0];
		
		return $arreglo;
	}else{
		return false;
	}
}

### Cnvertir id del avatar en URL con AccessToken Actual / En caso de no tener avatar muetsra los default
function urlImageByAvatar($id,$gender,$accessToken){
	if($id>0){
		return url_api."/pictures.php?accesstoken={$accessToken}&id={$id}";
	}else{
		if(strtoupper($gender) == "M"){
			return url_api."/_docs/icons/128/boy.png";
		}else if(strtoupper($gender) == "F"){
			return url_api."/_docs/icons/128/girl.png";
		}else{
			return url_api."/_docs/icons/128/team.png";
		}
	}	
};

### Consultar Nombre Estado X Id
function permisosByCargos($id){
	$check = datosSQL("Select * from ".TBL_CARGOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['permisos'];
	}else{
		return "";
	}
};
/** FIN **/

### Crear Session En Servidor con el AccessToken Dado
function crearSessionForAccessToken($accessToken){
	$accessToken = explode("|",base64_decode($accessToken));
	$consulta = datosSQL("Select * from ".TBL_PERSONAL."  where user IN ('{$accessToken[0]}') and cedula IN ('{$accessToken[1]}') ");
	
	if(isset($consulta->error) && $consulta->error == false && $consulta->data[0]){
		$consulta->data[0]['more'] = json_decode($consulta->data[0]['more']);
		$consulta->data[0]['cargo_name'] = cargoNameById($consulta->data[0]['cargo']);
		$consulta->data[0]['supervisor_name'] = supervisorNameById($consulta->data[0]['supervisor']);
		$consulta->data[0]['piloto_name'] = pilotoNameById($consulta->data[0]['piloto']);
		$consulta->data[0]['estado_name'] = estadoNameById($consulta->data[0]['estado']);
		$consulta->data[0]['rol_name'] = rolNameById($consulta->data[0]['rol']);
		$consulta->data[0]['ejecutivo_de_experiencia_name'] = supervisorNameById($consulta->data[0]['ejecutivo_de_experiencia']);
		unset($consulta->data[0]['more']);
		
		$arreglo = new stdClass();
		$arreglo->accessToken = generate_AccessToken_CCyUser($consulta->data[0]['user'],$consulta->data[0]['cedula']);
		$arreglo->session = $consulta->data[0];
		
		return $arreglo;
	}else{
		return false;
	}
};

### Generar AccessToken Con Usuario y Cedula
function generate_AccessToken_CCyUser($cedula,$user){
	$fcreate = date("Y-m-d H:i:s",time());
	return base64_encode($cedula.'|'.$user.'|'.$fcreate);
};

### Chekear AccessToken
function chechear_AccessToken_CCyUser($accessToken){
	if(base64_encode(base64_decode($accessToken)) == ($accessToken)){
		$accessToken = explode("|",base64_decode($accessToken));
		if(isset($accessToken[0]) && isset($accessToken[1]) && isset($accessToken[2])){
			$check = datosSQL("Select * from ".TBL_PERSONAL."  where cedula IN ('{$accessToken[0]}','{$accessToken[1]}') and user IN ('{$accessToken[0]}','{$accessToken[1]}')");

			if(isset($check->error) && $check->error == false && $check->data[0]){
				unset($check->data[0]["more"]);
				$check->data[0]["permisos_cargos"] = json_decode(permisosByCargos($check->data[0]["cargo"]));
				return $check->data[0];
			}else{
				return false;
			}
		}
	}else{
		return false;
	}
};

### Consultar Nombre Estado X Id
function nameRolById($id){
	$check = datosSQL("Select * from ".TBL_ROLES." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguno";
	}
};

### Consultar Nombre Estado X Id
function nameEstadoById($id){
	$check = datosSQL("Select * from ".TBL_STATUS_PEOPLE." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguno";
	}
};

### Consultar Nombre Piloto X Id
function namePilotoById($id){
	$check = datosSQL("Select * from ".TBL_PILOTOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguno";
	}
};

### Consultar Nombre Jefe X Id
function nameJefeById($id){
	$check = datosSQL("Select * from ".TBL_JEFES_PERSONAL." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguno";
	}
};

### Consultar Nombre Cargo X Id
function nameCargoById($id){
	$check = datosSQL("Select * from ".TBL_CARGOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguna";
	}
};

### Consultar Nombre Cargo X Id
function idCargoByName($name){
	$check = datosSQL("Select * from ".TBL_CARGOS." where name='{$name}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		return "Ninguna";
	}
};


### Consultar Nombre Categoria X Id y Type
function nameCategoryById($id,$type){
	$check = datosSQL("Select * from ".TBL_CATEGORIES." where id='{$id}' and type='{$type}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguna";
	}
};

### Consultar id Raiz Categoria X Id y Type
function raizCategoryById($id,$type){
	$check = datosSQL("Select * from ".TBL_CATEGORIES." where id='{$id}' and type='{$type}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['raiz'];
	}else{
		return "Ninguna";
	}
};

### Nombre Marca de Dispositivo
function nameManufacturerById($id){
	$check = datosSQL("Select * from ".TBL_DV_MARCAS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Desconocido";
	}
};

### Nombre Topic de Dispositivo VirtualSteps
function nameTopicDevicesById($id){
	$check = datosSQL("Select * from ".TBL_DV_TOPICS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Desconocido";
	}
};

### Nombre Dispositivo X Id
function nameDeviceById($id){
	$check = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Desconocido";
	}
};

### Nombre Plataforma de Dispositivo
function namePlataformaById($id){
	$check = datosSQL("Select * from ".TBL_DV_PLATAFORMAS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Desconocido";
	}
};

### Picture Plataforma de Dispositivo
function picturePlataformaById($id){
	$check = datosSQL("Select * from ".TBL_DV_PLATAFORMAS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['image_icon'];
	}else{
		return 0;
	}
};

### Picture Dispositivo 
function pictureDeviceById($id){
	$check = datosSQL("Select * from ".TBL_DV_DISPOSITIVOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['image_icon'];
	}else{
		return 0;
	}
};


### Cargar Permisos de User Actual [TODOS]
function cargarPermisos(){
	$permisos_cargos = json_decode($_SESSION["permisos_cargos"]);
	return $permisos_cargos;
};

### Cargar Permisos de User Actual [Individuales]
function cargarPermisosFor($for){
	$r = false;
	if(isset($_SESSION["permisos_cargos"])){
		$permisos_cargos = json_decode($_SESSION["permisos_cargos"]);
		if(isset($permisos_cargos->{$for}) && $permisos_cargos->{$for} !== ''){
			$r = $permisos_cargos->{$for};
		}
	}
	return $r;
};

### 
function importPeopleEnable(){
	$import = cargarPermisosFor("import");
	if(isset($import->people) && $import->people == true){
		return true;
	}else{
		return false;
	}
};

### 
function exportQuizEnable(){
	$export = cargarPermisosFor("export");
	if(isset($export->quiz) && $export->quiz == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el chat esta activo para el usuario actual
function chatEnable(){
	$chat = cargarPermisosFor("chat");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el foro esta activo para el usuario actual
function forumEnable(){
	$chat = cargarPermisosFor("forum");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el calendario esta activo para el usuario actual
function calendaryEnable(){
	$calendary = cargarPermisosFor("calendary");
	if(isset($calendary->view) && $calendary->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar las capacitacioes [Calendary]
function calendaryEditEnable(){
	$calendary = cargarPermisosFor("calendary");
	if(isset($calendary->edit) && $calendary->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar las capacitacioes [Calendary]
function calendaryCreateEnable(){
	$calendary = cargarPermisosFor("calendary");
	if(isset($calendary->create) && $calendary->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar las capacitacioes [Calendary]
function calendaryDeleteEnable(){
	$calendary = cargarPermisosFor("calendary");
	if(isset($calendary->delete) && $calendary->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar si los indicadores estan activos para el usuario actual
function indicatorsEnable(){
	$chat = cargarPermisosFor("indicators");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el cronometro esta activo para el usuario actual
function stopwatchEnable(){
	$chat = cargarPermisosFor("stopwatch");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si las alertas estan activas para el usuario actual
function alertsEnable(){
	$chat = cargarPermisosFor("alerts");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si las alertas estan activas para el usuario actual
function alertsEditEnable(){
	$alerts = cargarPermisosFor("alerts");
	if(isset($alerts->edit) && $alerts->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar si las alertas estan activas para el usuario actual
function alertsCreateEnable(){
	$alerts = cargarPermisosFor("alerts");
	if(isset($alerts->create) && $alerts->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar si puedes eliminar las alertas para el usuario actual
function alertsDeleteEnable(){
	$alerts = cargarPermisosFor("alerts");
	if(isset($alerts->delete) && $alerts->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el historial de alertas esta activo para el usuario actual
function alertsHistoryEnable(){
	$alerts = cargarPermisosFor("alerts");
	if(isset($alerts->history) && $alerts->history == true){
		return true;
	}else{
		return false;
	}
};

### Validar si los dispositivos estan activos para el usuario actual
function devicesEnable(){
	$devices = cargarPermisosFor("devices");
	if(isset($devices->view) && $devices->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar si los dispositivos estan activos para el usuario actual
function devicesCategoriesEditEnable(){
	$devices = cargarPermisosFor("devices");
	if(isset($devices->categories->edit) && $devices->categories->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar si los dispositivos estan activos para el usuario actual
function devicesCategoriesDeleteEnable(){
	$devices = cargarPermisosFor("devices");
	if(isset($devices->categories->edit) && $devices->categories->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar si los articulos estan activos para el usuario actual
function articlesEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->view) && $articles->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede eliminar contenido [Foro]
function forumDeleteEnable(){
	$forum = cargarPermisosFor("forum");
	if(isset($forum->delete) && $forum->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede eliminar contenido [Articulos]
function articlesDeleteEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->delete) && $articles->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar contenido [Articulos]
function articlesEditEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->edit) && $articles->edit == true){
		return true;
	}else{
		return false;
	}
};

### 
function createQuizEnable(){
	$quiz = cargarPermisosFor("quiz");
	if(isset($quiz->create) && $quiz->create == true){
		return true;
	}else{
		return false;
	}
};

###
function editQuizEnable(){
	$quiz = cargarPermisosFor("quiz");
	if(isset($quiz->edit) && $quiz->edit == true){
		return true;
	}else{
		return false;
	}
};

### 
function deleteQuizEnable(){
	$quiz = cargarPermisosFor("quiz");
	if(isset($quiz->delete) && $quiz->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [Articulos]
function articlesCreateEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->create) && $articles->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el historial de articulos esta activo para el usuario actual
function articlesHistoryEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->history) && $articles->history == true){
		return true;
	}else{
		return false;
	}
};

### Validar si el historial de articulos esta activo para el usuario actual
function ecardsHistoryEnable(){
	$ecards = cargarPermisosFor("ecards");
	if(isset($ecards->history) && $ecards->history == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar contenido [Foro]
function forumEditEnable(){
	$forum = cargarPermisosFor("forum");
	if(isset($forum->edit) && $forum->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar si las eCards estan activas para el usuario actual
function ecardsEnable(){
	$chat = cargarPermisosFor("ecards");
	if(isset($chat->view) && $chat->view == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede eliminar contenido [eCards]
function ecardsDeleteEnable(){
	$chat = cargarPermisosFor("ecards");
	if(isset($chat->delete) && $chat->delete == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede modificar contenido [eCards]
function ecardsEditEnable(){
	$chat = cargarPermisosFor("ecards");
	if(isset($chat->edit) && $chat->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [eCards]
function ecardsCreateEnable(){
	$ecards = cargarPermisosFor("ecards");
	if(isset($ecards->create) && $ecards->create == true){
		return true;
	}else{
		return false;
	}
};


### Validar el usuario actual puede crear contenido [eCards]
function ecardsCategoriesCreateEnable(){
	$ecards = cargarPermisosFor("ecards");
	if(isset($ecards->categories->create) && $ecards->categories->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [eCards]
function ecardsCategoriesEditEnable(){
	$ecards = cargarPermisosFor("ecards");
	if(isset($ecards->categories->edit) && $ecards->categories->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [eCards]
function ecardsCategoriesDeleteEnable(){
	$ecards = cargarPermisosFor("ecards");
	if(isset($ecards->categories->delete) && $ecards->categories->delete == true){
		return true;
	}else{
		return false;
	}
};


### Validar el usuario actual puede crear contenido [articles]
function articlesCategoriesCreateEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->categories->create) && $articles->categories->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [articles]
function articlesCategoriesEditEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->categories->edit) && $articles->categories->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [articles]
function articlesCategoriesDeleteEnable(){
	$articles = cargarPermisosFor("articles");
	if(isset($articles->categories->delete) && $articles->categories->delete == true){
		return true;
	}else{
		return false;
	}
};


### Validar el usuario actual puede crear contenido [forum]
function forumCategoriesCreateEnable(){
	$forum = cargarPermisosFor("forum");
	if(isset($forum->categories->create) && $forum->categories->create == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [forum]
function forumCategoriesEditEnable(){
	$forum = cargarPermisosFor("forum");
	if(isset($forum->categories->edit) && $forum->categories->edit == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede crear contenido [forum]
function forumCategoriesDeleteEnable(){
	$forum = cargarPermisosFor("forum");
	if(isset($forum->categories->delete) && $forum->categories->delete == true){
		return true;
	}else{
		return false;
	}
};



### Validar el usuario actual puede responder preguntas del foro
function forumResponses(){
	$chat = cargarPermisosFor("forum");
	if(isset($chat->response) && $chat->response == true){
		return true;
	}else{
		return false;
	}
};

### Validar el usuario actual puede responder comentarios del contenido
function commentsResponses(){
	$chat = cargarPermisosFor("comments");
	if(isset($chat->response) && $chat->response == true){
		return true;
	}else{
		return false;
	}
};

### Marcar Chat como leido 
function leerChat($userid,$idchat){
	$chat_sql = datosSQL("Select * from ".TBL_MSG_CHATS." where id IN ('{$idchat}') ");
	if(isset($chat_sql->error) && $chat_sql->error == false && $chat_sql->data[0]){
		$data['list'] = (explode(',',$chat_sql->data[0]['ids_reads']));
		$data['list'][] = $userid;
		sort($data['list']);
		$arrlength = count($data['list']);
		$b = array();
		for($x = 0; $x < $arrlength; $x++) {
			if($data['list'][$x] !== ''){
				$b[] = $data['list'][$x];
			}
		}
		$data['list'] = array_unique($data['list']);
		$data['list'] = implode(',',$data['list']);
		
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("UPDATE ".TBL_MSG_CHATS." SET ids_reads=? WHERE id='{$idchat}' ");
			$stmt = $sentencia->execute(array($data['list']));
			return true;
		}
		catch(PDOException $e)
		{
			return false;
		}
	}else{
		return false;
	}
};

### Cargar plataformas
function cargarPlataformas(){
	$cats1_sql = datosSQL("Select * from ".TBL_DV_PLATAFORMAS." where trash='0' ");
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = $cats1_sql->data;
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Cargar plataformas
function cargarMarcasForPlataformaId($id){
$cats1_sql = datosSQL("Select * from ".TBL_DV_MARCAS." where type='{$id}' and trash='0' ORDER BY name ASC");
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = $cats1_sql->data;
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### 
function nameTypePublishs($type){
	if($type == 'articles'){
		$label = 'Top Semanal';
	}else if($type == 'ecards'){
		$label = 'Info Banner';
	}else if($type == 'forum'){
		$label = 'Foro';
	}else{
		$label = 'Desconocido';
	}
	return $label;
}

### Otener HTML Para Sidebar de categorias
function parseCatsSidebarLI($element){
	if($element['type'] == 'articles' || $element['type'] == 'ecards'){
		$pageView = 'explore-articles';
	}else if($element['type'] == 'forum'){
		$pageView = 'view-forum';
	}else if($element['type'] == 'capas'){
		$pageView = 'view-calendary';
	}else{
		$pageView = '404';
	}
	
	$h = '<li>';
		$h .= '<a>';
			$h .= '<span class="cursor-pointer" onclick="javascript:location.replace('."'index.php?pageActive={$pageView}&type={$element['type']}&of={$element['id']}'".');"><i class="'.$element['icon'].'"></i> '.$element['name'].' </span>';
			$h .= '<span class="cursor-pointer" onclick="javascript:deleteCategory('.$element['id'].');"><i class="fas fa-ban"></i></span> ';
			$h .= '<span class="cursor-pointer" onclick="javascript:dialogEditCategoryFast('.$element['id'].','."'".$element['name']."'".','.$element['raiz'].','."'".$element['type']."'".');"><i class="fas fa-pencil-alt"></i></span>';
		$h .= '</a>';
	$h .= '</li>';
	
	
	if(isset($element['tree'][0])){
		$h .= '<li>';
			$h .= '<a href="#Submenu'.$element['id'].'" data-toggle="collapse" aria-expanded="false">';
				$h .= '<i class="'.$element['icon'].'"></i>';
				$h .= "\n".$element['name'].' [Categorias]';
			$h .= '</a>';
			
			$h .= "<ul class='collapse list-unstyled' id='Submenu{$element['id']}'>";
				foreach($element['tree'] As $element2){
					$h .= parseCatsSidebarLI($element2);
				}			
			$h .= '</ul>';
		$h .= '</li>';
			
	}
		
	return $h;
}

### Cargar eCards Para el Banner
function eCards_Banner($limit=10){
	$r = false;
	if(ecardsEnable() == true){
		$check = datosSQL("Select * from ".TBL_CONTENIDO." where type IN ('ecards') and trash IN (0) and public IN (1) and piloto IN ({$_SESSION['piloto']}) order by fcreate DESC");
		if(isset($check->error) && $check->error == false && isset($check->data[0])){
			$i=0;
			foreach($check->data As $che){
				$check->data[$i]["thumbnail_url"] = urlImageById($che['thumbnail']);
				$check->data[$i]["category_name"] = nameCategoryById($che['category'],'ecards');
				$i++;
			}
			return $check->data;
		}
	}
	return $r;
};


### Cnvertir id Imagen en URL con AccessToken Actual
function urlImageById($id){
	return url_api."/pictures.php?accesstoken={$_SESSION['accessToken']}&id={$id}";
};

### Cargar ultimos articulos publicados
function ultimasPublicaciones($piloto,$type,$limit){
	$cats1_sql = datosSQL("Select * from ".TBL_CONTENIDO." where piloto IN ('{$piloto}') and public IN (1) and trash IN (0) and type='{$type}' ORDER BY fcreate DESC limit ".$limit);
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = array();
		foreach($cats1_sql->data As $element){
			$cats1[] = parseArticles($element);
		}
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Explorar articulos publicados
function explorarPublicaciones($piloto,$type,$limit){
	$cats1_sql = datosSQL("Select * from ".TBL_CONTENIDO." where piloto IN ('{$piloto}') and public IN (1) and trash IN (0) and type='{$type}' ORDER BY fcreate DESC limit ".$limit);
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = array();
		foreach($cats1_sql->data As $element){
			$cats1[] = parseArticles($element);
		}
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Cargar categorias
function parseCategoriasTreeList($piloto,$type,$raiz){
	$cats1_sql = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ('{$piloto}') and view='1' and raiz='{$raiz}' and type='{$type}'");
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$arreglo = array();
		foreach($cats1_sql->data As $elem){
			$elem['tree'] = cargarCategoriasList($piloto,$type,$elem['id']);
			$arreglo[] = $elem;
		}
		$cats1 = $arreglo;
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Listar Categorias (Solo Id)
function asa($cats){
	$categoria = array();
	foreach($cats As $c){
		if(isset($c['tree'][0])){
			$categoria[]= implode(',',asa($c['tree']));
		}
		$categoria[] = $c['id'];
	}
	return $categoria;
}

### Cargar Likes y Views de un articulo X Id
function cargarLikesAndViewsPublish($id,$type){
	$re = new stdClass();
	$re->views = 0;
	$re->likes = 0;
	
	$post = datosSQL("Select * from ".TBL_VIEWS_AND_LIKES." where id_ref='{$id}' and type='{$type}'");
	if(isset($post ->error) && $post ->error == false && $post ->data[0]){
		$re->views = (int) $post->data[0]['views']+1;
		$re->likes = (int) $post->data[0]['likes'];
		
		$command = "UPDATE ".TBL_VIEWS_AND_LIKES." SET views=?,likes=? WHERE id_ref='{$id}' and type='{$type}'";
		$create = crearSQL($command,array($re->views,$re->likes));
		if(isset($create->error) && $create->error == false){
		}else{
			$re->views = $post->data[0]['views']-1;
		}
	}else{
		$command = "INSERT INTO ".TBL_VIEWS_AND_LIKES." ( id_ref,type,views,likes ) VALUES (?,?,?,?)";
		$create = crearSQL($command,array($id,$type,1,0));
		if(isset($create->error) && $create->error == false){
			$re->views = 1;
			$re->likes = 0;
		}
	}
	return $re;
};

### Explorar articulos publicados
function explorarRaizPublicaciones($piloto,$type,$limit,$offset,$of){
	$cats = cargarCategorias($piloto,$type,$of);
	$categoria = asa($cats);
	$categoria[] = ($of);
	
	
	$of = implode(',',$categoria);
	$cats1_sql = datosSQL("Select * from ".TBL_CONTENIDO." where piloto IN ('{$piloto}') and public IN (1) and trash IN (0) and type='{$type}' and category IN ({$of}) ORDER BY fcreate DESC LIMIT {$offset}, {$limit} ");
	return $cats1_sql;
};


### Cargar ultimos articulos publicados
function ultimasConversaciones($userId,$limit=10){
	$aregloFinal = array();
	$temp = datosSQL("Select * from ".TBL_MSG_TALKS." where group_ids LIKE '%{$userId}%' ORDER BY last_activity DESC LIMIT {$limit}");
	
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
	
	return $aregloFinal;	
};

### Cargar ultimos articulos publicados
function abrirConversacion($id,$userid,$limit=10){
	$jsonFinal = new stdClass();
	
	$temp = datosSQL("Select * from ".TBL_MSG_CHATS." where enviado_para IN ('{$id}') ORDER BY fcreate ASC LIMIT {$limit}");
	if(isset($temp->error) && $temp->error == false && $temp->data[0]){
		foreach($temp->data As $elem){
			$elem['enviado_por'] = cargarNamePeopleForUserid($elem['enviado_por']);
			
			$jsonFinal->data[] = $elem;
		}
	}else{
		$jsonFinal->data = array();
	}
	
	$jsonFinal->enviado_por = cargarNamePeopleForUserid($userid);
	$sqk = "Select * from ".TBL_MSG_TALKS." where id IN ({$id}) limit 1";
	$sqlConv = datosSQL($sqk);
	if(isset($sqlConv->error) && $sqlConv->error == false && $sqlConv->data[0]){
		$group_ids = (explode(',',$sqlConv->data[0]['group_ids']));
		
		foreach($group_ids As $per){
			$jsonFinal->enviado_para[] = cargarNamePeopleForUserid($per);
		}
	}
	
	return $jsonFinal;	
};

### Organizar Elementos de Articulos
function parseArticles($post,$accessToken){
	$post['thumbnail_url'] = urlImageByIdAndAccessToken($post['thumbnail'],$accessToken);
	$post['short_description'] = cortar_string(strip_tags($post['data'], '<i><b><s><br>'),300).'...';
	$post['type_name'] = nameTypePublishs($post['type']);

	$post['tags'] = explode(",",$post['tags']);
	$post['category_name'] = categoryNameById($post['category']);
	$post['author'] = cargarNamePeopleForUserid($post['author']);
	$post['stadistics'] = cargarLikesAndViewsPublish($post['id'],$post['type']);
	return $post;
};

### Cnvertir id Imagen en URL con AccessToken Actual
function urlImageByIdAndAccessToken($id,$accessToken){
	return url_api."/pictures.php?accesstoken={$accessToken}&id={$id}";
};


### Recortar cadena de texto para parrafo
function cortar_string($string, $largo) { 
   $marca = "<!--corte-->"; 
   if (strlen($string) > $largo) {
       $string = wordwrap($string, $largo, $marca); 
       $string = explode($marca, $string); 
       $string = $string[0]; 
   } 
   return $string;
};

### Cargar preguntas del foro pendientes por responder
function cargarPreguntasPendientes($type,$piloto,$limit){
$cats1_sql = datosSQL("Select * from ".TBL_COMENTARIOS." where type IN ('{$type}') and trash IN (0) and reply IN (0) and piloto IN ({$piloto}) order by f_query DESC limit ".$limit);
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		foreach($cats1_sql->data As $elemet){
			$elemet['author'] = cargarNamePeopleForUserid($elemet['author']);
			$elemet['category_name'] = nameCategoryById($elemet['raiz'],"forum");
			$cats1[] = $elemet;
		}
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Cargar chats pendientes por responder
function cargarChatsPendientes($idProfile){
	if(!isset($idProfile) || $idProfile == '' || $idProfile <= 0){ $idProfile = $_SESSION['id']; }
	$aregloFinal = array();
	$temp = datosSQL("Select * from ".TBL_MSG_TALKS." where group_ids LIKE '%{$idProfile}%' ORDER BY last_activity DESC LIMIT 10");
	if(isset($temp->error) && $temp->error == false && $temp->data[0]){
		foreach($temp->data As $elem){
			$tempChats = datosSQL("Select * from ".TBL_MSG_CHATS." where enviado_para IN ({$elem['id']}) and enviado_por NOT IN ({$idProfile}) and ids_reads NOT LIKE '%{$idProfile}%' group by enviado_para ");
			
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
	return $aregloFinal;
};

### Cargar Info del Perfil por Id del Personal --> FINAL
function ProfileForUserid($userid){
	$check = datosSQL("Select * from ".TBL_PERSONAL." where id='{$userid}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		unset($check->data[0]['more']);
		return $check->data[0];
	}else{
		return array();
	}
};

### Cargar Indicadores por Id Personal
function cargarIndicadores($userid){
	$consulta = datosSQL("Select * from ".TBL_INDICADORES." where user='{$userid}' ");
	if(isset($consulta->error) && $consulta->error == false && isset($consulta->data[0])){
		$aregloFinal = $consulta->data[0];
	}else{
		$aregloFinal = array();
	}
	return $aregloFinal;
};

### Normalizar Porcentajes de los Indicadores 100% - $value = INDICADOR
function porcentTo100_parse($value){
	if($value>100){
		$value = $value-100;
		if($value>100){ $value = $value-100; }else{ $value = 100-$value; }
	}
	if($value>100){
		$value = porcentTo100_parse($value);
	}
	return $value;
}

### Color segun porcentajes de indicadores
function colorLabelIndicadoresPorcent($porcent){
	if($porcent <= 75){
		$label = 'red';
	}else if($porcent >= 95){
		$label = 'green';
	}else{
		$label = 'yellow';
	}
	return $label;
}

### Cargar alertas activas
function cargarAlertsActivas($piloto){
	$cats1_sql = datosSQL("Select * from ".TBL_ALERTS." where trash IN (0) and piloto IN ({$piloto}) order by fecha_apertura DESC ");
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = $cats1_sql->data;
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Validar si el string contiene url
function ValidarUrlString($url){
	if($url !== '#'){
		$exists = true;
	}else{
		$exists = false;
	}
	return $exists;
};

### Validar si la URL existe o no.
function validarURL($url){
	if($url !== '#'){
		$file_headers = @get_headers($url);
		if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		}else {
			$exists = true;
		}
	}else{
		$exists = false;
	}
	return $exists;
};

### Validar el host de la url
function validarNameUrlString($url){
	$a = parse_url($url);
	if(isset($a['host'])){
		$r = $a['host'];
		
	}else{
		$r = "Enlace";
	}
	return $r;
};

### Total de preguntas en foro X Id
function totalPreguntasForoById($piloto,$raiz){
	$cats1_sql = datosSQL("Select * from ".TBL_COMENTARIOS." where type IN ('forum') and trash IN (0) and piloto IN ({$piloto}) and raiz IN ('{$raiz}') ");
	if(isset($cats1_sql->error) && $cats1_sql->error == false && $cats1_sql->data[0]){
		$cats1 = count($cats1_sql->data);
	}else{
		$cats1 = 0;
	}
	return $cats1;
};

### parse forum temas 
function parseForumTemas($element){
	$html = '';
	$html .= '<div class="panel panel-primary">';
		$html .= '<div class="panel-heading">';
			$html .= '<h4 class="panel-title">';
				$html .= '<a class="accordion-toggle question-heading" data-toggle="collapse" data-parent="#accordion" href="#comment-páge-id-'.$element['id'].'">';
					$html .= $element['name'];
				$html .= '</a>';
			$html .= '</h4>';
		$html .= '</div>';
		$html .= '<div class="panel-bodys">';
			$html .= '<div id="comment-páge-id-'.$element['id'].'" class="panel-collapse collapse ">';
				$html .= '<div class="answer-body">';
					$html .= '<div class="row">';
						$html .= '<div class="col-sm-1">';
							$html .= '<a href="index.php?pageActive=view-forum&type=forum&of='.$element['id'].'" title="Ingresar a: '.$element['name'].'" class="btn btn-xs btn-primary" data-toggle="tooltip"><i class="fas fa-sign-in-alt"></i></a>';
						$html .= '</div>';
						$html .= '<div class="col-sm-11">';
							$html .= '<b>Tema principal: </b>'.nameCategoryById($element['raiz'],"forum").'<br>';
							$html .= '<b>Piloto: </b>'.namePilotoById($element['piloto']).'<br>';
							$html .= '<b>Preguntas: </b>'.totalPreguntasForoById($element['piloto'],$element['id']).'<br>';
						
							$html .= '<div class="clearfix"></div>';
							if(isset($element['tree'][0])){
								$html .= '<div class="panel panel-primary">';
									foreach($element['tree'] As $more){
										$html .= parseForumTemas($more);
									}
								$html .= '</div>';
							}
							
							
	if(forumEditEnable() == true){
		$html .= '<div class="panel panel-success">';
			$html .= '<div class="panel-heading">';
				$html .= '<h4 class="panel-title">';
					$html .= '<a class="accordion-toggle question-heading" data-toggle="collapse" data-parent="#accordion" href="#comment-páge-id-create-'.$element['id'].'">';
						$html .= "Agregar Tema en ".$element['name'];
					$html .= '</a>';
				$html .= '</h4>';
			$html .= '</div>';
			$html .= '<div class="panel-bodys">';
				$html .= '<div id="comment-páge-id-create-'.$element['id'].'" class="panel-collapse collapse ">';
					$html .= '<div class="answer-body">';
						$html .= '<div class="row">';
							$html .= '<div class="col-sm-12">';
								
								$html .= '<form method="post">'; // action="index.php"
									$html .= '<div class="input-group">';
										$html .= '<span class="input-group-addon">Nombre</span>';
										$html .= '<input name="name" type="text" class="form-control" placeholder="Nombre" required >';
									$html .= '</div>';
									$html .= '<div class="text-right">';
										$html .= '<a class="accordion-toggle btn btn-primary btn-default" data-toggle="collapse" data-parent="#accordion" href="#comment-páge-id-create-'.$element['id'].'">';
											$html .= "Cancelar";
										$html .= '</a>';
									
										$html .= '<button type="submit" class="btn btn-success btn-default">';
											$html .= 'Agregar';
										$html .= '</button>';
									$html .= '</div>';
								
									
									$html .= '<input name="raiz" type="hidden" class="form-control" placeholder="raiz" value="'.$element['id'].'" >';
									$html .= '<input name="piloto" type="hidden" class="form-control" placeholder="piloto" value="'.$element['piloto'].'" >';
									$html .= '<input name="type" type="hidden" class="form-control" placeholder="type" value="'.$element['type'].'" >';
									$html .= '<input name="view" type="hidden" class="form-control" placeholder="view" value="1" >';
									$html .= '<input name="icon" type="hidden" class="form-control" placeholder="icon" value="0" >';
									$html .= '<input name="action_forms" type="hidden" class="form-control" placeholder="icon" value="newCategory" >';
								
								$html .= '</form>';
							
								$html .= '<div class="clearfix"></div>';
							$html .= '</div>';
							$html .= '<div class="clearfix"></div>';
						$html .= '</div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
	};
	
	
	if(forumDeleteEnable() == true){
		$html .= '<div class="panel panel-danger">';
			$html .= '<div class="panel-heading">';
				$html .= '<h4 class="panel-title">';
					$html .= '<a class="accordion-toggle question-heading" data-toggle="collapse" data-parent="#accordion" href="#comment-páge-id-remove-'.$element['id'].'">';
						$html .= "Eliminar Tema ".$element['name'];
					$html .= '</a>';
				$html .= '</h4>';
			$html .= '</div>';
			$html .= '<div class="panel-bodys">';
				$html .= '<div id="comment-páge-id-remove-'.$element['id'].'" class="panel-collapse collapse ">';
					$html .= '<div class="answer-body">';
						$html .= '<p class="text-center">';
							$html .= '¿Confirmas que deseas remover este tema del foro?<br>';
							$html .= '<a class="accordion-toggle btn btn-primary btn-default" data-toggle="collapse" data-parent="#accordion" href="#comment-páge-id-remove-'.$element['id'].'">';
								$html .= "Cancelar";
							$html .= '</a>';
						
							$html .= '<a href="javascript:deleteCategory('.$element['id'].');" class="btn btn-danger btn-default">';
								$html .= 'Eliminar';
							$html .= '</a>';
							
					
						$html .= '</p>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
	};
	
	
							
						$html .= '</div>';
						$html .= '<div class="clearfix"></div>';
					$html .= '</div>';
				$html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
	$html .= '</div>';
	
	
	return $html;
};

function Censurar($string){
	#$string = str_replace(lista_negra_palabras, "****", $string);
	$string = str_ireplace(lista_negra_palabras, "****", $string);
	return $string; 
};

function newPeopleBD($columnas,$personal){	
	try {
		$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sentencia = $conn->prepare("INSERT INTO ".TBL_UPLOADS_TEMP." ( columnas,datos ) VALUES (?,?)");
		$insert = $sentencia->execute(array(json_encode($columnas),json_encode($personal)));
		$last_id = $conn->lastInsertId();
		if($insert==true){
			return $last_id;
		}else{
			return 0;
		}
	}
	catch(PDOException $e)
	{
		return $e->getMessage();
	}
	$conn = null;
};

function cargo_createAlt_newPeopleBD($id,$name){
	$check = datosSQL("Select * from ".TBL_CARGOS." where name='{$name}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_CARGOS." ( id,name ) VALUES (?,?)");
			$insert = $sentencia->execute(array($id,$name));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return $last_id;
			}else{
				return "Intenta nuevamente";
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
		$conn = null;
		
		/**/
	}
};

function grado_createAlt_newPeopleBD($id,$name){
	$check = datosSQL("Select * from ".TBL_ROLES." where id='{$id}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_ROLES." ( id,name ) VALUES (?,?)");
			$insert = $sentencia->execute(array($id,$name));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return $last_id;
			}else{
				return "Intenta nuevamente";
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
		$conn = null;
	}
};

function estado_createAlt_newPeopleBD($name){
	$name = ucwords(strtolower($name));
	$check = datosSQL("Select * from ".TBL_STATUS_PEOPLE." where name='{$name}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_STATUS_PEOPLE." ( name ) VALUES (?)");
			$insert = $sentencia->execute(array($name));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return $last_id;
			}else{
				return "Intenta nuevamente";
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
		$conn = null;
	}
};

function cliente_createAlt_newPeopleBD($name){
	$name = ucwords(strtolower($name));
	$check = datosSQL("Select * from ".TBL_PILOTOS." where name='{$name}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_PILOTOS." ( name ) VALUES (?)");
			$insert = $sentencia->execute(array($name));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return $last_id;
			}else{
				return "Intenta nuevamente";
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
		$conn = null;
	}
};

function jefe_createAlt_newPeopleBD($cc,$name,$cargo){
	$cargo_id = idCargoByName($cargo);

	$check = datosSQL("Select * from ".TBL_JEFES_PERSONAL." where name='{$name}' OR cedula='{$cc}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['id'];
	}else{
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_JEFES_PERSONAL." ( cedula,name,cargo ) VALUES (?,?,?)");
			$insert = $sentencia->execute(array($cc,$name,$cargo));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return $last_id;
			}else{
				return "Intenta nuevamente";
			}
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
		$conn = null;
	}
};

function newPeopleImport($arreglo){
	$arreglo->cedula = isset($arreglo->cedula) ? $arreglo->cedula : '';
	$arreglo->nombre = isset($arreglo->nombre) ? $arreglo->nombre : '';
	$arreglo->user = isset($arreglo->user) ? $arreglo->user : '';
	$arreglo->cargo = isset($arreglo->cargo) ? $arreglo->cargo : '';
	$arreglo->piloto = isset($arreglo->piloto) ? $arreglo->piloto : '';
	$arreglo->estado = isset($arreglo->estado) ? $arreglo->estado : '';
	$arreglo->supervisor = isset($arreglo->supervisor) ? $arreglo->supervisor : '';
	$arreglo->novedad = isset($arreglo->novedad) ? $arreglo->novedad : '';
	$arreglo->fecha_nacimiento = isset($arreglo->fecha_nacimiento) ? $arreglo->fecha_nacimiento : '';
	$arreglo->fecha_ingreso = isset($arreglo->fecha_ingreso) ? $arreglo->fecha_ingreso : '';
	$arreglo->rol = isset($arreglo->rol) ? $arreglo->rol : '';
	$arreglo->genero = isset($arreglo->genero) ? $arreglo->genero : '';
	$arreglo->ejecutivo_de_experiencia = isset($arreglo->ejecutivo_de_experiencia) ? $arreglo->ejecutivo_de_experiencia : '';
	$arreglo->more = isset($arreglo->more) ? $arreglo->more : '';
	
	# VALIDAR SI EL USER EXISTE EN LA DB
	$check = datosSQL("Select * from ".TBL_PERSONAL." where cedula='{$arreglo->cedula}' or user='{$arreglo->user}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		# ACTUALIZAR EN CASO DE QUE EXISTA EL USER EN LA DB
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("UPDATE ".TBL_PERSONAL." SET nombre=?,user=?,cargo=?,piloto=?,estado=?,supervisor=?,novedad=?,fecha_nacimiento=?,fecha_ingreso=?,rol=?,genero=?,ejecutivo_de_experiencia=?,more=? where cedula='{$arreglo->cedula}' ");
			$stmt = $sentencia->execute(array($arreglo->nombre,$arreglo->user,$arreglo->cargo,$arreglo->piloto,$arreglo->estado,$arreglo->supervisor,$arreglo->novedad,$arreglo->fecha_nacimiento,$arreglo->fecha_ingreso,$arreglo->rol,$arreglo->genero,$arreglo->ejecutivo_de_experiencia,json_encode($arreglo->more)));
			return true;
		}
		catch(PDOException $e)
		{
			#return false;
			return $e->getMessage();
		}
		$conn = null;
	}else{
		# CREAR EL EL USER EN CASO DE QUE NO EXISTA EN LA DB
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sentencia = $conn->prepare("INSERT INTO ".TBL_PERSONAL." ( cedula,nombre,user,cargo,piloto,estado,supervisor,novedad,fecha_nacimiento,fecha_ingreso,rol,genero,ejecutivo_de_experiencia,more ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$insert = $sentencia->execute(array($arreglo->cedula,$arreglo->nombre,$arreglo->user,$arreglo->cargo,$arreglo->piloto,$arreglo->estado,$arreglo->supervisor,$arreglo->novedad,$arreglo->fecha_nacimiento,$arreglo->fecha_ingreso,$arreglo->rol,$arreglo->genero,$arreglo->ejecutivo_de_experiencia,json_encode($arreglo->more)));
			$last_id = $conn->lastInsertId();
			if($insert==true){
				return true;
			}else{
				return false;
			}
		}
		catch(PDOException $e)
		{
			#return 0;
			return $e->getMessage();
		}
		$conn = null;
	}
};


### SANEAR STRING ADAPTADO PARA IMPORTACION
function sanear_string($string){ 
    $string = trim($string); 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    ); 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    ); 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    ); 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    ); 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    ); 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    ); 
    $string = str_replace(
        array('°', ' ', '+', ':'),
        array('_', '_', '_', '_',),
        $string
    ); 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\"", "¨", "º", "-", "~",
             "#", "@", "|", "!", '"',
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );
 
 
    return $string;
}



/* 	------------------------------------------------------------
		######## FUNCIONES DENTRO DE LA PAGINA #######
	------------------------------------------------------------
*/




/* 	------------------------------------------------------------
		######## FUNCIONES VERIFICADAS MARZO 2018 #######
	------------------------------------------------------------
*/

## Consulta SQL SELECT
function datosSQL($sql){
	$rawdata = new stdClass();
	$rawdata->error = true;
	$rawdata->data = array();
	$rawdata->sql = $sql;
	try {
		$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $conn->prepare($sql); 
		$stmt->execute();
		$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
		
		$result = $stmt->fetchAll();
		$rawdata->error = false;
		if(count($result)>0){
			$rawdata->data = $result;
		}else{
			$rawdata->data = array();
		}
	}
	catch(PDOException $e) { $rawdata->data = "Error: " . $e->getMessage(); }
	$conn = null;	
	return $rawdata;
};

## Consulta SQL INSERT // EJEMPLO -> "INSERT INTO ".TBL_IMAGENES_GLOBAL." ( data ) VALUES (?)"
## Consulta SQL UPDATE // EJEMPLO -> $change = crearSQL("UPDATE ".TBL_CALENDARIO." SET trash=? WHERE id='{$data['id']}' ",array(1))
function crearSQL($comando,$array){
	$rawdata = new stdClass();
	$rawdata->error = true;
	$rawdata->last_id = 0;
	$rawdata->sql = $comando;
	try {
		$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sentencia = $conn->prepare($comando);
		$insert = $sentencia->execute($array);
		$last_id = $conn->lastInsertId();
		if($insert==true){
			$rawdata->error = false;
			$rawdata->last_id = $last_id;
		}else{
			$rawdata->error_message = "Intenta nuevamente";
		}
	}
	catch(PDOException $e)
	{
		$rawdata->error_message = $e->getMessage();
	}
	$conn = null;
	return $rawdata;
};

## Consulta SQL DELETE
function eliminarSQL($sql){
	$rawdata = new stdClass();
	$rawdata->error = true;
	$rawdata->sql = $sql;
	try {
		$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$conn->exec($sql);
		$rawdata->error = false;
	}
	catch(PDOException $e)
	{
		$rawdata->error_message = $sql . "<br>" . $e->getMessage();
	}
	$conn = null;
	return $rawdata;
};

### Consultar Nombre Piloto X Id
function pilotoNameById($id){
	$check = datosSQL("Select * from ".TBL_PILOTOS." where id='{$id}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguno";
	}
};

### Consultar Nombre Categoria X Id
function categoryNameById($id){
	$check = datosSQL("Select * from ".TBL_CATEGORIES." where id='{$id}'");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		return $check->data[0]['name'];
	}else{
		return "Ninguna";
	}
};

### Datos para perfil no encontrado o eliminado
function cargarDatosPerfilGuest(){
	$element = array();
	$element['id'] = "00000000000000000000000000000000";
	$element['cedula'] = "0000000000";
	$element['nombre'] = "Usuario no encontrado";
	$element['user'] = "user_no_encotrado";
	$element['cargo'] = 0;
	$element['piloto'] = 0;
	$element['estado'] = 0;
	$element['supervisor'] = 0;
	$element['novedad'] = 0;
	$element['fecha_nacimiento'] = "0000-00-00";
	$element['fecha_ingreso'] = "0000-00-00";
	$element['rol'] = 0;
	$element['ejecutivo_de_experiencia'] = 0;
	$element['novedad'] = 0;
	$element['genero'] = "NN";
	$element['avatar'] = 0;
	return $element;
}

### Cargar Info del Perfil por Id del Personal
function cargarNamePeopleForUserid($userid){
	$check = datosSQL("Select * from ".TBL_PERSONAL." where id='{$userid}' ");
	if(isset($check->error) && $check->error == false && isset($check->data[0])){
		unset($check->data[0]['more']);
		return $check->data[0];
	}else{
		return cargarDatosPerfilGuest();
	}
};

### Cargar categorias
function cargarCategorias($piloto,$type,$raiz){
	$cats1_sq = datosSQL("Select * from ".TBL_CATEGORIES." where piloto IN ('{$piloto}') and view='1' and raiz='{$raiz}' and type='{$type}'");
	if(isset($cats1_sq->error) && $cats1_sq->error === false){
		$arreglo = array();
		foreach($cats1_sq->data As $elem){
			$elem['tree'] = cargarCategorias($piloto,$type,$elem['id']);
			$arreglo[] = $elem;
		}
		$cats1 = $arreglo;
	}else{
		$cats1 = array();
	}
	return $cats1;
};

### Explorar articulos publicados
function cargarPreguntasForo($piloto,$type,$offset,$limit,$order,$raiz,$comment_raiz,$accesstokena){
	$cats1 = array();
	$cats1_sql = datosSQL("Select * from ".TBL_COMENTARIOS." where type IN ('{$type}') and trash IN (0) and piloto IN ({$piloto}) and raiz IN ('{$raiz}') and comment_raiz='{$comment_raiz}' order by f_query {$order}, f_comment {$order}  LIMIT {$offset}, {$limit} ");
	if(isset($cats1_sql->error) && $cats1_sql->error == false){
		foreach($cats1_sql->data As $elemet){
			$elemet['author'] = cargarNamePeopleForUserid($elemet['author']);
			$elemet['author']['avatar_url'] = urlImageByAvatar($elemet['author']['avatar'],$elemet['author']['genero'],$accesstokena);
			$elemet['category_name'] = nameCategoryById($elemet['raiz'],"forum");
			
			$elemet['tree'] = cargarPreguntasForo($piloto,$type,$offset,$limit,$order,$raiz,$elemet['id'],$accesstokena);
			$cats1[] = $elemet;
		}
	}
	return $cats1;
};

function organizarKPIs($indicadores,$checkToken){
	############### ---- KPIs METAS DE LOS PILOTOS ---- ###############
	$array__types = array(
	1=>'RGU',
	2=>'UPS'
	);
	$array__metas__ventas__rgu = array(
		0=>0,
		27=>3
	);
	$array__metas__ventas__ups = array(
		0=>0,
		27=>7
	);

	$indicators = array();
	if(isset($indicadores['rgu']) && $indicadores['rgu']>0){
		if(isset($array__metas__ventas__rgu[$checkToken['piloto']])){ $indicadores['rgu_meta'] = $array__metas__ventas__rgu[$checkToken['piloto']]; }
		else{ $indicadores['rgu_meta'] = $array__metas__ventas__rgu[0]; };
		
		$rgu = new stdClass();
		$rgu->name = "RGU";
		$rgu->meta = (int) $indicadores['rgu_meta'];
		$rgu->actual = (int) $indicadores['rgu'];
		$rgu->porcentage = (int) ($indicadores['rgu']*100)/$indicadores['rgu_meta'];
		$rgu->color_label = colorLabelIndicadoresPorcent($rgu->porcentage);
		$indicators[] = $rgu;
	};
		
	if(isset($indicadores['ups']) && $indicadores['ups']>0){
		if(isset($array__metas__ventas__ups[$checkToken['piloto']])){ $indicadores['ups_meta'] = $array__metas__ventas__ups[$checkToken['piloto']]; }
		else{ $indicadores['ups_meta'] = $array__metas__ventas__ups[0]; };
									
		$ups = new stdClass();
		$ups->name = "UPS";
		$ups->meta = (int) $indicadores['ups_meta'];
		$ups->actual = (int) $indicadores['ups'];
		$ups->porcentage = (int) ($indicadores['ups']*100)/$indicadores['ups_meta'];
		$ups->color_label = colorLabelIndicadoresPorcent($ups->porcentage);
		$indicators[] = $ups;
	};
	
	if(isset($indicadores['penc']) && $indicadores['penc']>0){
		$penc = new stdClass();
		$penc->name = "PENC";
		$penc->meta = (int) 100;
		$penc->actual = (int) $indicadores['penc'];
		$penc->porcentage = (int) ($indicadores['penc']);
		$penc->color_label = colorLabelIndicadoresPorcent($indicadores['penc']);
		$indicators[] = $penc;
	};
	
	if(isset($indicadores['pecu']) && $indicadores['pecu']>0){
		$pecu = new stdClass();
		$pecu->name = "PECU";
		$pecu->meta = (int) 100;
		$pecu->actual = (int) $indicadores['pecu'];
		$pecu->porcentage = (int) ($indicadores['pecu']);
		$pecu->color_label = colorLabelIndicadoresPorcent($indicadores['pecu']);
		$indicators[] = $pecu;
	};
	
	if(isset($indicadores['pecn']) && $indicadores['pecn']>0){
		$pecn = new stdClass();
		$pecn->name = "PECN";
		$pecn->meta = (int) 100;
		$pecn->actual = (int) $indicadores['pecn'];
		$pecn->porcentage = (int) ($indicadores['pecn']);
		$pecn->color_label = colorLabelIndicadoresPorcent($indicadores['pecn']);
		$indicators[] = $pecn;
	};
	
	if(isset($indicadores['nps']) && $indicadores['nps']>0){
		$nps = new stdClass();
		$nps->name = "NPS";
		$nps->meta = (int) 100;
		$nps->actual = (int) $indicadores['nps'];
		$nps->porcentage = (int) ($indicadores['nps']);
		$nps->color_label = colorLabelIndicadoresPorcent($indicadores['nps']);
		$indicators[] = $nps;
	};
		
	if(isset($indicadores['aht']) && $indicadores['aht']>0 && isset($indicadores['aht_meta']) && $indicadores['aht_meta']>0){
		$aht = new stdClass();
		$aht->name = "AHT";
		$aht->meta = (int) $indicadores['aht_meta'];
		$aht->actual = (int) $indicadores['aht'];
		$aht->porcentage = (int) porcentTo100_parse((($indicadores['aht']*100)/$indicadores['aht_meta']));
		$aht->color_label = colorLabelIndicadoresPorcent($aht->porcentage);
		$indicators[] = $aht;
	};
	return $indicators;
}


### Detectar Pagina activa
function pageActive(){
	$r = false;
	if(isset($_GET) && isset($_GET['pageActive']) && $_GET['pageActive'] !== ''){
		$r = strtolower($_GET['pageActive']);
	}
	return $r; 
};


/* 	------------------------------------------------------------
		######## FUNCIONES DENTRO DE LA PAGINA #######
	------------------------------------------------------------
*/

### Validar si hay un Action Form activo
if(isset($_POST) && isset($_POST['action_forms']) && $_POST['action_forms'] !== ''){
	### Comenzar con newCategory
	if(
		$_POST['action_forms'] == 'newCategory'
		&& isset($_POST['name']) && $_POST['name'] !== ''
		&& isset($_POST['raiz']) && $_POST['raiz'] !== ''
		&& isset($_POST['piloto']) && $_POST['piloto'] !== ''
		&& isset($_POST['type']) && $_POST['type'] !== ''
		&& isset($_POST['view']) && $_POST['view'] !== ''
		&& isset($_POST['icon']) && $_POST['icon'] !== ''
	){
		unset($_POST['action_forms']);
		
		$command = "INSERT INTO ".TBL_CATEGORIES." ( name,raiz,piloto,type,view,icon ) VALUES (?,?,?,?,?,?)";
		$create = crearSQL($command,array($_POST['name'],$_POST['raiz'],$_POST['piloto'],$_POST['type'],$_POST['view'],$_POST['icon']));
		if(isset($create->error) && $create->error == false){
			header("Location: index.php?pageActive=explore-forum&type=forum&of=".$_POST['raiz']);
			exit("Categoria creada...");
		}else{
			header("Location: ".$_SERVER['HTTP_REFERER']);
			exit("Error Creando la categoria..");
		}
		
		exit("");
	}
}

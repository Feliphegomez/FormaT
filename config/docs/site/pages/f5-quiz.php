<?php
	
	$tma = datosSQL("Select * from ".TBL_F5_TEMAS." where piloto IN ({$_GET['piloto']}) and view='1' order by fecha_creation DESC limit 1");
	if(isset($tma->error) && $tma->error == false && isset($tma->data[0])){
		
		$querys = datosSQL("Select * from ".TBL_F5_PREGUNTAS." where topic='{$tma->data[0]['id']}'");
		if(isset($querys->error) && $querys->error == false && isset($querys->data[0])){
			
		}else{
			exit("no hay preguntas");
		}
	}else{
		exit("error");
	}
	
	if(isset($_POST['response_quiz'])){
		$id = ($_POST['response_quiz']);
		unset($_POST['response_quiz']);
		
		echo "<br>";
		
		$data = new stdClass();
		foreach($querys->data As $query){
			unset($query['topic']);
			$response = json_decode($query['response']);
			$query['response'] = $response;
			$data->{$query['id']} = $query;
		}
		
		$total_note = 0;
		$arreglo = array();
		foreach($_POST As $k=>$v){
			$select = $data->{$k};
			$select['response'] = $select['response'][$v];
			
			$total_note = $total_note+$select['response']->value;
			$arreglo[] = $select;
		}
		
		
		try {
			$conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$comando = "INSERT INTO ".TBL_F5_RESPUESTAS." ( topic,user,result,result_note ) VALUES (?,?,?,?)";
			$sentencia = $conn->prepare($comando);
			$insert = $sentencia->execute(array($tma->data[0]['id'],$_GET['id'],json_encode($arreglo),$total_note));
			$last_id = $conn->lastInsertId();
			if($last_id>0){
				echo '<meta http-equiv="refresh" content="0; url='.url_site.'/index.php?pageActive=f5-result-last&ref='.$last_id.'&topic='.$tma->data[0]['id'].'">';
				exit("gracias por presentar tu prueba, espere un momento...");
			}else{
				exit("Intenta nuevamente");
			}
		}
		catch(PDOException $e)
		{
			exit($e->getMessage());
		}
		$conn = null;
	}
	
?>
<style>
.coupon {
    border: 3px dashed #bcbcbc;
    border-radius: 10px;
    font-family: "HelveticaNeue-Light", "Helvetica Neue Light", 
    "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
    font-weight: 300;
}

.coupon #head {
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    min-height: 56px;
}

.coupon #footer {
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
}

#title .visible-xs {
    font-size: 12px;
}

.coupon #title img {
    font-size: 30px;
    height: 30px;
    margin-top: 5px;
}

@media screen and (max-width: 500px) {
    .coupon #title img {
        height: 15px;
    }
}

.coupon #title span {
    float: right;
    margin-top: 5px;
    font-weight: 700;
    text-transform: uppercase;
}

.coupon-img {
    width: 100%;
    margin-bottom: 15px;
    padding: 0;
}

.items {
    margin: 0;
	list-style: upper-roman;
}

.usd, .cents {
    font-size: 20px;
}

.number {
    font-size: 40px;
    font-weight: 700;
}

sup {
    top: -15px;
}

#business-info ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
    text-align: center;
}

#business-info ul li { 
    display: inline;
    text-align: center;
}

#business-info ul li span {
    text-decoration: none;
    padding: .2em 1em;
}

#business-info ul li span i {
    padding-right: 5px;
}

.disclosure {
    padding-top: 15px;
    font-size: 11px;
    color: #bcbcbc;
    text-align: center;
}

.coupon-code {
    color: #333333;
    font-size: 11px;
}

.exp {
    color: #f34235;
}

.print {
    font-size: 14px;
    float: right;
}



/*------------------dont copy these lines----------------------*/
body {
    font-family: "HelveticaNeue-Light", "Helvetica Neue Light", 
    "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
    font-weight: 300;
}
.row {
    margin: 30px 0;
}

#quicknav ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
    text-align: center;
}

#quicknav ul li { 
    display: inline; 
}

#quicknav ul li a {
    text-decoration: none;
    padding: .2em 1em;
}

.btn-danger, 
.btn-success, 
.btn-info, 
.btn-warning, 
.btn-primary {
    width: 105px;
}

.btn-default {
    margin-bottom: 40px;
}
/*-------------------------------------------------------------*/
</style>

<div class="container">
    <div class="row"><h1 class="text-center">F5 FormaT</h1>
        <p class="text-center">Este es tu último resultado del F5!..</p>
    </div>

	
    <div class="row" id="blue">
        <div class="col-md-6 col-md-offset-3">
			<form class="panel panel-primary coupon" role="form" method="POST">
				<div class="panel-heading" id="head">
					<div class="panel-title" id="title">
						<i class="fas fa-pencil-alt fa-2x"></i>
						<span class="hidden-xs"><?php echo ($tma->data[0]['title']); ?></span>
						<span class="visible-xs"><?php echo ($tma->data[0]['title']); ?></span>
					</div>
				</div>
				<div class="panel-body">
					<img src="images/logos/logo-350.gif" class="coupon-img img-rounded">
					<div class="col-md-9">
						<div class="items">
							<?php $i = 0; ?>
							<?php foreach($querys->data As $query){ ?>								
								<div class="form-group">
									<label for="name" class="cols-sm-2 control-label"><?php echo $query['query']; ?></label>
									<div class="cols-sm-10">
										<div class="input-group">
											<span class="input-group-addon"><i class="fas fa-question fa" aria-hidden="true"></i></span>
											
											<select name="<?php echo $query['id']; ?>" class="form-control">
												<?php $query['response'] = json_decode($query['response']); ?>
												<?php foreach($query["response"] As $key=>$r){ ?>
													<option value="<?php echo $key; ?>"><?php echo $r->text; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								
							<?php $i++; ?>
							<?php } ?>
								
							<input name="response_quiz" value="<?php echo ($tma->data[0]['id']); ?>" type="hidden" />
						</div>
					</div>
					<div class="col-md-3">
						<div class="offer text-primary">
							<span class="usd"><sup>Total</sup></span>
							<span class="number"><?php echo count($querys->data); ?></span>
							<span class="cents"><sup>Preguntas</sup></span>
						</div>
					</div>
					<div class="col-md-12">
						<p class="disclosure">
							Bienvenido a la comunidad FormaT. Descubra el valor de un esfuerzo abierto colaborativo por uno de los equipos más grandes del mundo.
						</p>
						
						<?php if(editQuizEnable() == true){ ?>
							<a class="btn btn-sm btn-warning" href="javascript:disableQuizAndEdit(<?php echo ($tma->data[0]['id']); ?>);"> <i class="fas fa-edit"></i> Modificar</a>
						<?php }; ?>
					</div>
				</div>
				<div class="panel-footer">
					<div class="coupon-code">
						Quiz ID: F5-<?php echo ($tma->data[0]['id']); ?>
						<span class="print">
							
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="pull-right">
										<button type="submit" class="btn btn-primary">Terminar</button>
										
										
									</div>
								</div>
							</div>
							
							<!-- <a href="#" class="btn btn-link"><i class="fa fa-lg fa-print"></i> Print Coupon</a> -->
						</span>
					</div>
					<div class="exp">Creado: <?php echo ($tma->data[0]['fecha_creation']); ?></div>
				</div>
			</form>
        </div>
    </div>
</div>
	
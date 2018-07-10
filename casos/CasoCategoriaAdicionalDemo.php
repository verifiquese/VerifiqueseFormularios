<?php 
	//se importa el archivo PHP que realiza la conexion
	require '../configuracion/database.php';
	//se almacena la variable de la conexion y se inicializa
	$pdo = Database::connect();

	//Se se incializan las varibales que seran enviadas desde la app Verifíquese Cédula Premium con el método get
	$orh = null;
	$bdh = null;
	$wso = null;
	$pur = null;
	$gel = null;
	$poe = null;
	$wad = null;
	$qwe = null;
	$tyu = null;
	$klz = null;
	$sde = null;
	$xlq = null;
	$adicional1 = null;
	$adicional2 = null;
	$adicional3 = null;
	$adicional4 = null;
	$adicional5 = null;
	$combo = null;

	//Se toman los datos enviados desde la app Verifíquese Cédula Premium con el método get
	if (!empty($_GET['orh'])) {
        $orh = $_REQUEST['orh'];
    }
	if (!empty($_GET['bdh'])) {
        $bdh = $_REQUEST['bdh'];
    }
	if (!empty($_GET['pur'])) {
        $pur = $_REQUEST['pur'];
    }
	if (!empty($_GET['wso'])) {
        $wso = $_REQUEST['wso'];
    }
	if (!empty($_GET['gel'])) {
        $gel = $_REQUEST['gel'];
    }
	if (!empty($_GET['poe'])) {
        $poe = $_REQUEST['poe'];
    }
	if (!empty($_GET['qwe'])) {
        $qwe = $_REQUEST['qwe'];
    }
	if (!empty($_GET['tyu'])) {
        $tyu = $_REQUEST['tyu'];
    }
	if (!empty($_GET['klz'])) {
        $klz = $_REQUEST['klz'];
    }
	if (!empty($_GET['sde'])) {
        $sde = $_REQUEST['sde'];
    }
	if (!empty($_GET['xlq'])) {
        $xlq = $_REQUEST['xlq'];
    }
	if (!empty($_GET['wad'])) {
        $wad = $_REQUEST['wad'];
    }	
?> 
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="iso-8559-1"/>
	    <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- Bootstrap CSS Offline-->
		<link rel="stylesheet" type="text/css" href="../recursos/lib/bootstrap/css/bootstrap.min.css">
	</head> 
	<body>
		<center>
			<img class="img-responsive" src="../recursos/img/vfqcc_almacen.png" alt="Encabezado">
	    	<div class="container-fluid">
	            <div class="row">
	                <h3>Listado de documentos consultados</h3>
	            </div>
	            <div>
				<!--Se usa método get para el envío de los datos adicionales y los datos del documento escaneado-->
					<form class="form-horizontal" action="CasoCategoriaAdicionalDemo.php" method="get">
						<center>
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Nombre Categoría</th>
										<th>Extra</th>
									</tr>
								</thead>
								<tbody>
								<?php
									// Establecer el nivel de errores a EXCEPTION
									$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									//Se busca la categoría a la que pertece el documento escaneado
									$sentencia = $pdo->prepare("SELECT categories.name_category, categories.Extra FROM categories INNER JOIN documentcategory ON categories.id_categories = documentcategory.category WHERE documentcategory.citizen_id = :gel AND documentcategory.document_type = :qwe AND documentcategory.country = :wad ");
									//se usa en modo emulador (no valido para nuevas versiones mysql)
									// $sentencia=$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
									//bind que se aplica a la sentecia sql para evitar el sql injection
									$sentencia->bindParam(":gel", $gel, PDO::PARAM_STR);
									$sentencia->bindParam(":qwe", $qwe, PDO::PARAM_STR);
									$sentencia->bindParam(":wad", $wad, PDO::PARAM_STR);
									$sentencia->execute();
									//resultado de la consulta 
									$resultado = $sentencia->fetchAll();
									// se recorre el resultado para tomar las tuplas y muestrarlas en la tabla
									if($resultado){
										foreach ($resultado as $row) {
											echo '<tr>';
											echo '<td>'.$row['name_category'].'<input name="category" type="hidden" value="'.$row['name_category'].'"></td>';
											echo '<td>'.$row['Extra'].'<input name="categoryExtra" type="hidden" value="'.$row['Extra'].'"></td>';	
										}
									} else {
										echo '<td>';
										echo 'no hay resultados';
										echo '</td>';
									}

									//cierra conexion
									Database::disconnect();
								?>
								</tbody>
							</table>
						</center>

						<!-- Controles del formulario -->
						<div class="control-group">
							<label >Primer Nombre</label>
							<input name="orh" type="text" class="form-control" placeholder="Primer Nombre" value="<?php echo !empty($orh)?$orh:'';?>">
						</div>
						<div class="control-group">
							<label>Segundo Nombre</label>
							<input name="bdh" type="text" class="form-control"  placeholder="Segundo Nombre" value="<?php echo !empty($bdh)?$bdh:'';?>">
						</div>
						<div class="control-group">
							<label>Primer Apellido</label>
							<div class="form-control">
								<input name="pur" type="text" class="form-control" placeholder="Primer Apellido" value="<?php echo !empty($pur)?$pur:'';?>">
							</div>
						</div>
						<div class="control-group">
							<label>Segundo Apellido</label>
								<input name="wso" type="text" class="form-control" placeholder="Segundo Apellido" value="<?php echo !empty($wso)?$wso:'';?>">
						</div>
						<div class="control-group">
							<label>Número de Documento</label>
								<input name="gel" type="text" class="form-control" placeholder="Número de Documento" value="<?php echo !empty($gel)?$gel:'';?>">
						</div>
						<div class="control-group">
							<label>País</label>
								<input name="wad" type="text" class="form-control" placeholder="País" value="<?php echo !empty($wad)?$wad:'';?>">
						</div>
						<div class="control-group">
							<label>Tipo de Documento</label>
							<input name="qwe" type="text" class="form-control" placeholder="Tipo de Documento" value="<?php echo !empty($qwe)?$qwe:'';?>">
						</div>
						<div class="control-group">
							<label>Fecha de Lectura</label>
							<input name="tyu" type="text" class="form-control" placeholder="Fecha de Lectura" value="<?php echo !empty($tyu)?$tyu:'';?>">
						</div>
						<div class="control-group">
							<label>Latitud</label>
							<input name="klz" type="text" class="form-control" placeholder="Latitud" value="<?php echo !empty($klz)?$klz:'';?>">
						</div>
						<div class="control-group">
							<label>Longitud</label>
							<input name="sde" type="text" class="form-control" placeholder="Longitud" value="<?php echo !empty($sde)?$sde:'';?>">
						</div>
						<div class="control-group">
							<label>Información Extra</label>
							<textarea name="xlq" rows="5" cols="40">
								<?php echo !empty($xlq)?$xlq:'';?>
							</textarea>
						</div>
						<div class="control-group">
							<label >Combo</label>
								<select name="combo" value="<?php echo !empty($combo)?$combo:'';?>"> 
									<option value="">Seleccione</option> 
									<option value="valor1">Valor 1</option> 
									<option value="valor2">Valor 2</option> 
									<option value="valor3">Valor 3</option>
								</select> 
						</div>
						<div class="control-group">
							<label>Color</label>
							<input id="h" type="color" name="color">
						</div>
						<div class="control-group">
							<label>Fecha</label>
							<input id="h" type="date" name="date">
						</div>
						<div class="control-group">
							<label>Adicional 1</label>
							<input name="adicional1" type="text" class="form-control" placeholder="Adicional 1" value="<?php echo !empty($adiciona1)?$adicional1:'';?>">
						</div>
						<div class="control-group">
							<label>Adicional 2</label>
							<input name="adicional2" type="text" class="form-control"  placeholder="Adicional 2" value="<?php echo !empty($adiciona2)?$adicional2:'';?>">
						</div>
						<div class="control-group">
							<label>Adicional 3</label>
							<input name="adicional3" type="text" class="form-control"  placeholder="Adicional 3" value="<?php echo !empty($adiciona3)?$adicional3:'';?>">
						</div>
						<div class="control-group">
							<label>Adicional 4</label>
							<input name="adicional4" type="text" class="form-control" placeholder="Adicional 4" value="<?php echo !empty($adiciona4)?$adicional4:'';?>">
						</div>
						<div class="control-group">
							<label>Adicional 5</label>
							<input name="adicional5" type="text" class="form-control" placeholder="Adicional 5" value="<?php echo !empty($adiciona5)?$adicional5:'';?>">
						</div>
						<button type="submit" class="btn btn-success">Aceptar</button>
					</form>
				</div>	
	    	</div> <!-- /container -->
			<footer>
				<img class="img-responsive" src="../recursos/img/logoverifiqueseds.jpg" alt="Footer">
			</footer>
    	</center>
	</body>
</html>
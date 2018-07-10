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
			<div class="container-fluid"  style="text-align: center; padding-right: 0px">
				<div class="row">
					<form class="form-horizontal" method="get">
						<?php 
							//se importa el archivo PHP que realiza la conexion
							require '../configuracion/database.php';
							//Se toman los datos del documento enviados desde la aplicación móvil Verifíquese Cédula
							setlocale(LC_ALL,"es_ES");
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
							$combo = null;
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
							$state = "";
							if (!empty($_GET['state'])) {
								$state = $_REQUEST['state'];
							}
							$count = 0;
							if($state == "") {
								//obtiene la fecha actual para el registro
								$date = getdate();
								$day = $date["mday"];
								$mon = $date["mon"];
								$year = $date["year"];
								$fecha =  "".$year."/".$mon."/".$day;
								//se almacena la variable de la conexion y se inicializa
								$pdo = Database::connect();
								//Se hace una busqueda en la lista blanca para ver si el documento escaneado esta registrado
								// Establecer el nivel de errores a EXCEPTION
								$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sentencia = $pdo->prepare("SELECT * FROM whitelist WHERE country = :wad AND document_type = :qwe AND document_number = :gel ");
								//se usa en modo emulador (no valido para nuevas versiones mysql)
								// $sentencia=$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
								//bind que se aplica a la sentecia sql para evitar el sql injection
								$sentencia->bindParam(":wad", $wad, PDO::PARAM_STR);
								$sentencia->bindParam(":qwe", $qwe, PDO::PARAM_STR);
								$sentencia->bindParam(":gel", $gel, PDO::PARAM_STR);		
								$sentencia->execute();
											//resultado de la consulta 
								$resultado = $sentencia->fetchAll();
								//Se valida si la consulta en la lista blanca trae algun resultado
								if($resultado) {
									//Si el documento se encuentra registrado en la lista blanca se guarda un registro en la tabla successfulaccess
									$idWhiteList = "";
									foreach ($resultado as $row) {
										$idWhiteList = $row['id_whitelist'];
										$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$sentencia = $pdo->prepare("INSERT INTO successfulaccess (id_whitelist, date_ingress) VALUES (:idWhiteList, :fecha)");
										//bind que se aplica a la sentecia sql para evitar el sql injection
										$sentencia->bindParam(":idWhiteList", $idWhiteList, PDO::PARAM_STR);
										$sentencia->bindParam(":fecha", $fecha, PDO::PARAM_STR);
										$sentencia->execute();
										//Se cuenta cuantas veces a ingresado el documento documento escaneado
										$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$sentencia = $pdo->prepare("SELECT count(*) AS records FROM successfulaccess WHERE id_whitelist = :idWhiteList ");
										//se usa en modo emulador (no valido para nuevas versiones mysql)
										// $sentencia=$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
										//bind que se aplica a la sentecia sql para evitar el sql injection
										$sentencia->bindParam(":idWhiteList", $idWhiteList, PDO::PARAM_STR);		
										$sentencia->execute();
										//resultado de la consulta 
										$resultado = $sentencia->fetchAll();
										//valida se reciben resultados de la consulta
										if($resultado) {
											foreach ($resultado as $row) {
												$count = $row['records'];
												if($count > 1) {
													//Si la cantidad de veces ingresadas es mayor a 1 se coloca el estado de Restringido advirtiendo que el documento a ingresado varias veces y que puede ser una falsificación
													$state = "Restringido";
												} else {
													//Si la cantidad de veces ingresadas es igual a 1 se coloca el estado de Aceptado
													$state = "Aceptado";
												}
											}
											//cierra la conexion
											Database::disconnect();
										} else {
											echo 'Error en la busqueda contacte con soporte';
										}	
									}
								} else {
									//Si no el documento no esta registrado en la lista blanca se coloca el estado de Rechazado y se guarda un registro en la taabla accessdenied
									$state = "Rechazado";
									$pdo = Database::connect();
									$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$sentencia = $pdo->prepare("INSERT INTO accessdenied (date_ingress,datails,country,document_tye,document_number) VALUES (:fecha, :state, :wad, :qwe, :gel)");
									//bind que se aplica a la sentecia sql para evitar el sql injection
									$sentencia->bindParam(":fecha", $fecha, PDO::PARAM_STR);
									$sentencia->bindParam(":state", $state, PDO::PARAM_STR);
									$sentencia->bindParam(":wad", $wad, PDO::PARAM_STR);
									$sentencia->bindParam(":qwe", $qwe, PDO::PARAM_STR);
									$sentencia->bindParam(":gel", $gel, PDO::PARAM_STR);
									$sentencia->execute();
								}
							}
							if($state == "Rechazado"){
								echo "<input name='state' type='hidden' value='".$state."'>";
								echo "<button type='submit' class='btn btn-danger'><img src='../recursos/img/stop.png'></button>";
							}
							if($state == "Aceptado"){
								echo "<input name='state' type='hidden' value='".$state."'>";
								echo "<button type='submit' href='index.php' class='btn btn-success'><img src='../recursos/img/go.png'></button>";
							}
							if($state == "Restringido"){
								echo "<input name='records' type='hidden' value='".$count."'>";
								echo "<input name='state' type='hidden' value='".$state."'>";
								echo "<h2>"."Cantidad de veces ingresadas: ".$count."</h2>";
								echo "<br/>";
								echo "<button type='submit' class='btn btn-warning'><img src='../recursos/img/go.png'></button>";
							}
						?> 
					</form>
				</div>
			</div>
			<footer>
				<img class="img-responsive" src="../recursos/img/logoverifiqueseds.jpg" alt="Footer">
			</footer>
		</center>
	</body>
</html>
<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

/*************************************************************************************

	Funcion que se recibe peticiones POST a la url http://localhost/apirest/control
	y retorna un mensaje informandonos de si la autenticacion ha tenido exito.


**************************************************************************************/

$app->post("/control/", function() use($app)
{

	$pass = $app->request->post("pass");
	$user = $app->request->post("user");
	
	try{

		$connection = getConnectionAgencia();
		//$query = "SELECT COUNT(*) FROM authagencia WHERE pass = ? and id_agencia = (select id_agencia FROM agencias WHERE nombre = ?)";
		$query = "CALL autenticarAgencia(?,?)";
		$dbh = $connection->prepare($query);
		$dbh->bindParam(1, $user);
		$dbh->bindParam(2, $pass);
		$dbh->execute();
		$res = $dbh->fetch();

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		if($res['result'] == 0){
			$connection = null;
			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
		}else{
			$connection = null;
			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Succesful")));
		}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	
});

/*************************************************************************************

	Funcion que se recibe peticiones GET a la url http://localhost/apirest/inmuebles
	y retorna todos los inmuebles en formato JSON


**************************************************************************************/

$app->get("/inmuebles/", function() use($app)
{

	try{
		$connection = getConnectionAgencia();
		$dbh = $connection->prepare("SELECT * FROM inmuebles");
		$dbh->execute();
		$inmuebles = $dbh->fetchAll();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		$app->response->body(json_encode($inmuebles));
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

});

/*************************************************************************************

	Funcion que se recibe peticiones POST a la url http://localhost/apirest/inmuebles
	y retorna todos los inmuebles tanto propios como compartidos en formato JSON


**************************************************************************************/

$app->post("/inmuebles/", function() use($app)
{

	$agencia = $app->request->post("agencia");

	try{

		$connection = getConnectionAgencia();
		$dbh = $connection->prepare("SELECT COUNT(*) FROM agencias where nombre = ?");
		$dbh->bindParam(1, $agencia);
		$dbh->execute();

		if($dbh->fetchColumn() == 0){ //Si la agencia no existe le mandamos un error

			$connection = null;
			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode(array("result" => "ERROR")));

		}else{//Si la agencia existe

			$consulta = "SELECT * FROM inmuebles where id_agencia = (SELECT id_agencia FROM agencias where nombre = ?) or compartir = 1";
			$dbh = $connection->prepare($consulta);
			$dbh->bindParam(1, $agencia);
			$dbh->execute();
			$inmuebles = $dbh->fetchAll();
			$connection = null;

			$app->response->headers->set("Content-type", "application/json");
			$app->response->status(200);
			$app->response->body(json_encode($inmuebles));

		}
		
	}catch(PDOException $e){
		echo "Error: " . $e->getMessage();
	}

});

/***********************************************************************************************

	Funcion que se recibe peticiones POST a la url http://localhost/apirest/propietarios_agencia
	y devuelve los propietarios de una agencia en formato JSON


************************************************************************************************/

$app->get("/propietarios_agencia/:agencia", function($agencia) use($app)
{

	try{

		$connection = getConnectionAgencia();

		/*$query = "SELECT distinct(p.dni), p.id_propietario,p.nombre, p.primer_apellido, p.segundo_apellido, p.direccion, p.localidad, p.provincia, p.telefono, p.email
			      FROM inmuebles i INNER JOIN propietarios p ON i.id_propietario = p.id_propietario 
		          WHERE i.id_agencia = (SELECT id_agencia FROM agencias WHERE nombre = ?) 
			      ORDER BY p.nombre,p.primer_apellido,p.segundo_apellido";*/

		$query = "CALL obtenerPropietariosAgencia(?);";
		
		$dbh = $connection->prepare($query);
		$dbh->bindParam(1, $agencia);
		$dbh->execute();
		$propietarios = $dbh->fetchAll();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);
		$app->response->body(json_encode($propietarios));
		
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}

});
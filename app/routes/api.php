<?php

if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

/*************************************************************************************

	Funcion que recibe peticiones POST a la url http://localhost/apirest/control/comercial
	y retorna un mensaje informandonos de si la autenticacion ha tenido exito.


**************************************************************************************/

/*$app->post("/control/comercial/", function() use($app)
{

	$pass = $app->request->post("pass");
	$user = $app->request->post("user");
	
	try{

		$connection = getConnectionAgencia();
		//$query = "SELECT COUNT(*) FROM authagencia WHERE pass = ? and id_agencia = (select id_agencia FROM agencias WHERE nombre = ?)";
		$query = "CALL authComercial(?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $user);
		$stmt->execute();
		$dbHash = $stmt->fetchcolumn();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){
       		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Succesful")));
    	}else{
    		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
    	}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	
});*/

/*************************************************************************************

	Funcion que recibe peticiones POST a la url http://localhost/apirest/control/comercial
	y hace un insert de un propietario en la BD


**************************************************************************************/

$app->post("/control/comercial/", function() use($app)
{

	//datos del comercial a autenticar
	$user = $app->request->post("user");
	$pass = $app->request->post("pass");
	$agencia = $app->request->post("agencia");
	$id_metodo = $app->request->post("metodo");

	//datos del propietario a insertar
	$dni              = $app->request->post("dni");
	$nombre           = $app->request->post("nombre");
	$primer_apellido  = $app->request->post("primer_apellido");
	$segundo_apellido = $app->request->post("segundo_apellido");
	$direccion        = $app->request->post("direccion");
	$localidad        = $app->request->post("localidad");
	$provincia        = $app->request->post("provincia");
	$telefono         = $app->request->post("telefono");
	$email            = $app->request->post("email");
	
	try{

		$connection = getConnectionAgencia();
		//Lamada a procedimiento almacenado
		$query = "CALL authComercial(?,?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $user);
		$stmt->bindParam(2, $agencia);
		$stmt->execute();
		$dbHash = $stmt->fetchcolumn();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		//Comprobamos que la contraseña de entrada combinada con el hash de la BD sean igual al hash de la BD
		if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){

			$connection = getConnectionAgencia();
			//Lamada a funcion almacenada
			$query = "SELECT checkPermisos(?,?) as res";
			$dbh = $connection->prepare($query);
			$dbh->bindParam(1, $user);
			$dbh->bindParam(2, $id_metodo);
			$dbh->execute();
			$res = $dbh->fetch();
			$connection = null;

			if($res['res'] == 1){//Autenticacion de usuario y permisos correcto
				$connection = getConnectionAgencia();
				//Lamada a procedimiento almacenado
				$query = "CALL crearPropietario(?,?,?,?,?,?,?,?,?)";
				$dbh = $connection->prepare($query);
				$dbh->bindParam(1, $dni);
				$dbh->bindParam(2, $nombre);
				$dbh->bindParam(3, $primer_apellido);
				$dbh->bindParam(4, $segundo_apellido);
				$dbh->bindParam(5, $direccion);
				$dbh->bindParam(6, $localidad);
				$dbh->bindParam(7, $provincia);
				$dbh->bindParam(8, $telefono);
				$dbh->bindParam(9, $email);
				$dbh->execute();
				$connection = null;
				
       			$app->response->body(json_encode(array("status" => "Ok", "message" => "Operation Succesful")));
       		}else{
       			//Fallo al comprobar permisos del usuario
       			$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
       		}

    	}else{  
    		//Fallo al autenticar el usuario
    		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
    	}

	}
	catch(PDOException $e)
	{
		//$app->response->body(json_encode(array("status" => "Ok", "message" => $e->getMessage())));
		echo "Error: " . $e->getMessage();
	}
	
});

/*************************************************************************************

	


**************************************************************************************/

$app->post("/operacion/comercial/", function() use($app)
{

	$pass = $app->request->post("pass");
	$user = $app->request->post("user");
	
	try{

		$connection = getConnectionAgencia();
		//$query = "SELECT COUNT(*) FROM authagencia WHERE pass = ? and id_agencia = (select id_agencia FROM agencias WHERE nombre = ?)";
		$query = "CALL authComercial(?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $user);
		$stmt->execute();
		$dbHash = $stmt->fetchcolumn();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){
       		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Succesful")));
    	}else{
    		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
    	}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	
});

/*************************************************************************************

	Funcion que recibe peticiones POST a la url http://localhost/apirest/control/agencia
	y retorna un mensaje informandonos de si la autenticacion ha tenido exito.


**************************************************************************************/

$app->post("/control/agencia/", function() use($app)
{

	$pass = $app->request->post("pass");
	$user = $app->request->post("user");
	
	try{

		$connection = getConnectionAgencia();
		$query = "CALL authAgencia(?)";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $user);
		$stmt->execute();
		$dbHash = $stmt->fetchcolumn();
		$connection = null;

		$app->response->headers->set("Content-type", "application/json");
		$app->response->status(200);

		if(strcmp(crypt($pass, $dbHash), $dbHash) == 0){
       		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Succesful")));
    	}else{
    		$app->response->body(json_encode(array("status" => "Ok", "message" => "Autentication Failure")));
    	}

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	
});

/*************************************************************************************

	Funcion que recibe peticiones POST a la url http://localhost/apirest/control/agencia
	y retorna un mensaje informandonos de si la autenticacion ha tenido exito.


**************************************************************************************/

/*$app->post("/control/agencia/", function() use($app)
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
	
});*/

/*************************************************************************************

	Funcion que recibe peticiones GET a la url http://localhost/apirest/inmuebles
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

	Funcion que recibe peticiones POST a la url http://localhost/apirest/inmuebles
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

	Funcion que recibe peticiones POST a la url http://localhost/apirest/propietarios_agencia
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

		$query = "CALL obtenerPropietariosAgencia(?)";
		
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


$app->delete("/propietarios_agencia/:agencia", function($agencia) use($app)
{
	$pass = $app->request->post("pass");
	$user = $app->request->post("user");

	$app->response->headers->set("Content-type", "application/json");
	$app->response->status(200);
	$app->response->body(json_encode(array("result" => "Haciendo DELETE en la $agencia con pass $pass y user $user")));

});

$app->put("/propietarios_agencia/:agencia", function($agencia) use($app)
{
	$pass = $app->request->post("pass");
	$user = $app->request->post("user");

	$app->response->headers->set("Content-type", "application/json");
	$app->response->status(200);
	$app->response->body(json_encode(array("result" => "Haciendo PUT en la $agencia con pass $pass y user $user")));

});

//PROBA /////////////////////////////////////////////////////////////////////////////////////

$app->get("/propietarios/existe/:dni", function($dni) use($app)
{

	$app->response->headers->set("Content-type", "application/json");
	$app->response->status(200);

	$result = propietarioExiste($dni);

	if($result == 0){
		$app->response->body(json_encode(array("result" => "El propietario no existe en la BD")));
	}else if($result == 1){
		$app->response->body(json_encode(array("result" => "El propietario ya existe en la BD")));
	}else{
		$app->response->body(json_encode(array("result" => "Error de la BD")));
	}
	
});

function propietarioExiste($dni){

	try{

		$connection = getConnectionAgencia();

		$query = "SELECT COUNT(*) FROM propietarios WHERE dni = ?";
		
		$stmt = $connection->prepare($query);
		$stmt->bindParam(1, $dni);
		$stmt->execute();
		$result = $stmt->fetchcolumn();
		$connection = null;

		return $result;

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
		return -1;
	}

}
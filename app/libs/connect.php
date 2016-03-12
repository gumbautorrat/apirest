<?php if(!defined("SPECIALCONSTANT")) die("Acceso denegado");

function getConnection()
{

	try{

		$db_username = "root";
		$db_password = "dvmmvd033";
		$connection = new PDO("mysql:host=localhost;dbname=restfulslim", $db_username, $db_password);
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	return $connection;
	
}

function getConnectionAgencia()
{

	try{

		//$db_username = "root";
		//$db_password = "dvmmvd033";
		$db_username = "agencia";
		$db_password = "123456";

		$host = "localhost";
		$db_name = "federacion";
		$db_conection_string = "mysql:host=".$host.";dbname=".$db_name;

		$connection = new PDO($db_conection_string,$db_username, $db_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
		$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	return $connection;

}
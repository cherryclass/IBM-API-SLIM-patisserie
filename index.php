<?php 
require 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;



$app = new \Slim\App;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});



$app->post('/gateau', function(Request $request, Response $response){
	$id = $request->getQueryParam('id');	
	$nom = $request->getQueryParam('nom');	
	return setGateau($id, $nom);
});

$app->get('/gateau/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	return getGateau($id);
});


$app->get('/gateaux', function(Request $request, Response $response){
	return getGateaux();
});

function connexion(){ 
		$vcap_services = json_decode($_ENV['VCAP_SERVICES'], true);
		$uri = $vcap_services['compose-for-mysql'][0]['credentials']['uri'];
		$db_creds = parse_url($uri);
		$dbname = "patisserie";
		$dsn = "mysql:host=" . $db_creds['host'] . ";port=" . $db_creds['port'] . ";dbname=" . $dbname;
		return $dbh = new PDO($dsn, $db_creds['user'], $db_creds['pass'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		/*return $dbh = new PDO("mysql:host=localhost;dbname=patisserie", 'root', '',array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));*/
	 
}

function getGateau($id)
{

	$sql = "SELECT * FROM gateau WHERE id=:id";
	try{
		$dbh=connexion();
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":id", $id);
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_CLASS);
		return json_encode($result, JSON_PRETTY_PRINT);
	} catch(PDOException $e){
		return '{"error":'.$e->getMessage().'}}';
	}
	

}

function setGateau($id, $nom)
{
$dbh=connexion(); 
	$req=$dbh->prepare('INSERT INTO gateau VALUES(:id, :nom)');
	$res=$req->execute(array(
		':id'=> $id,
		':nom' => $nom,
	));
	return $res;
}
function getGateaux()
{
	$sql = "SELECT * FROM gateau";
	$dbh=connexion();
	$statement = $dbh->prepare($sql);
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_CLASS); 
	return json_encode($result, JSON_PRETTY_PRINT);

}

$app->run();
   



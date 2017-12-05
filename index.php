<?php 
//require 'vendor/autoload.php';
require 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Firebase\JWT\JWT;


/**
 * @SWG\Swagger(
 *     schemes={"https"},
 *    host="mybluemix.net",
 *   	basePath="/gateau",
 *     @SWG\Info(
 *         version="1.1.0",
 *         title="API Gatêau",
 *         description="Enregistrement et affichage de Gâteaux",
 *         @SWG\Contact(
 *             email="luc.frebourg@ac-versailles.fr"
 *         ),
 *         @SWG\License(
 *             name="Apache 2.0",
 *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *         )
 *     ),
  * )
 */

/**
 * @SWG\Tag(
 *   name="gateau",
 *   description="Operations about gateau",
 * )
 */

$app = new \Slim\App;

error_reporting(E_ALL);
ini_set('display_errors', 1);


/**
 * @SWG\Post(
 *   path="/gateau",
   * tags={"gateau"},
 *   summary="Inserer un gateau",
 *   description="Inserer un gateau",
 *    @SWG\Parameter(
     *         name="id",
     *         in="header",
     *         required=true,
     *         type="integer"
     *     ),
  *    @SWG\Parameter(
     *         name="nom",
     *         in="header",
     *         required=true,
     *         type="string"
     *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="successful operation",
 *		@SWG\Schema(
 *       type="object"
 *     ),
*   )
 * )
 */
$app->post('/gateau', function(Request $request, Response $response){
	//$gateau = json_decode($request->getBody());
	$id = $request->getQueryParam('id');	
	$nom = $request->getQueryParam('nom');	
	return setGateau($id, $nom);
});
/**
 * @SWG\Get(
 *   path="/gateau/{id}",
   * tags={"gateau"},
 *   summary="Obtenir un gateau",
 *   description="Obtenir un gateau",
 *    @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
 *   @SWG\Response(
 *     response=200,
 *     description="successful operation"		
 *   	)
 * )
 */ 
$app->get('/gateau/{id}', function(Request $request, Response $response){
	$id = $request->getAttribute('id');
	return getGateau($id);
});



function connexion(){ 
		$vcap_services = json_decode($_ENV['VCAP_SERVICES'], true);
		$uri = $vcap_services['compose-for-mysql'][0]['credentials']['uri'];
		$db_creds = parse_url($uri);
		$dbname = "patisserie";
		$dsn = "mysql:host=" . $db_creds['host'] . ";port=" . $db_creds['port'] . ";dbname=" . $dbname;
		return $dbh = new PDO($dsn, $db_creds['user'], $db_creds['pass'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	 
}

function getGateau($id)
{

	$sql = "SELECT * FROM gateau WHERE id=:id";
	try{
		$dbh=connexion();
		$statement = $dbh->prepare($sql);
		$statement->bindParam(":id", $id);
		$statement->execute();
		$result = $statement->fetchObject();
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

$app->run();
   



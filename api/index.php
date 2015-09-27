<?php

require 'Slim/Slim.php';

$app = new Slim();

//comandas
// $app->get('/comandas', 'getComandas');
// $app->get('/comandas/:id', 'getComanda');
// $app->post('/comandas/add', 'addComanda');
// $app->put('/comandas/:id', 'updateComanda');
// $app->delete('/comandas/:id', 'deleteComanda');
$app->get('/comandasPerRestauranteAndUser/:userID/:restauranteID','getComandasPerRestaurante');
$app->get('/comandasPerRestaurante/:restauranteID','getComandasPerRestauranteAndUser');

function getComandasPerRestaurante($restauranteID){
	$sql = "SELECT * FROM comandas WHERE restauranteID='".$restauranteID."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getComandasPerRestauranteAndUser($userID,$restauranteID){
	$sql = "SELECT * FROM comandas WHERE userID='".$userID."' AND restauranteID='".$restauranteID."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

//pedidos
$app->get('/mypedidos/:userID', 'getMyPedidos');
$app->get('/restaurantepedidos/:restauranteID', 'getRestaurantePedidos');
$app->get('/restaurantepedidos/:restauranteID/:userID', 'getRestauratePedidosByUser');
// $app->get('/pedidos/:id', 'getPedido');
// $app->post('/pedidos/add', 'addPedido');
// $app->put('/pedidos/:id', 'updatePedido');
// $app->delete('/pedidos/:id', 'deletePedido');
function getMyPedidos($userID){
	$sql = "SELECT * FROM pedidos WHERE userID='".$userID."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getRestaurantePedidos($restauranteID){
	$sql = "SELECT * FROM pedidos WHERE restauranteID='".$restauranteID."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getRestauratePedidosByUser($userID,$restauranteID){
	$sql = "SELECT * FROM pedidos WHERE userID='".$userID."' AND restauranteID='".$restauranteID."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
//users
$app->get('/users', 'getUsers');
$app->get('/users/:id', 'getUser');
$app->get('/userByUserName/:username','userByUserName');
$app->get('/login/:username/:password','login');
$app->post('/add_user', 'addUser');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');

function getUsers() {
	$sql = "select * FROM users ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function login($username,$password){
	$sql = "SELECT username,email,id,role FROM users WHERE username='".$username."' AND password='".$password."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	//echo '{"name":"'.$username.'",password:"'.$password.'"}'; 
}

function userByUserName($username) {
	$sql = "SELECT * FROM users WHERE username='".$username."'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUser($id) {
	$sql = "select * FROM users WHERE id=".$id." ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addUser() {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $user->username);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("password", $user->password);
		$stmt->execute();
		$user->id = $db->lastInsertId();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateUser($id) {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "UPDATE users SET username=:username, first_name=:first_name, last_name=:last_name, address=:address WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $user->username);
		$stmt->bindParam("first_name", $user->first_name);
		$stmt->bindParam("last_name", $user->last_name);
		$stmt->bindParam("address", $user->address);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUser($id) {
	$sql = "DELETE FROM users WHERE id=".$id;
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


//restaurantes
$app->get('/restaurantes', 'getRestaurantes');
$app->get('/restaurantes/:id', 'getRestaurante');
// $app->post('/restaurantes/add', 'addRestaurante');
// $app->put('/restaurantes/:id', 'updateRestaurante');
// $app->delete('/restaurantes/:id', 'deleteRestaurante');

function getRestaurantes(){
	$sql = "select * FROM restaurantes ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$restaurantes = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($restaurantes);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getRestaurante($id){
	$sql = "select * FROM restaurantes WHERE id=".$id." ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}



//connection settings
function getConnection() {
	$dbhost="mysql.hostinger.com.br";
	$dbuser="u523987351_user1";
	$dbpass="p@ssw0rd";
	$dbname="u523987351_db1";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
$app->run();
?>
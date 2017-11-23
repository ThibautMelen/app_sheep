<?php
function dashboard(){

	$pdo = get_pdo();

	$lastDepenses = getSpendByUserPart(3, 0);
	$allUserSpended = getTotalSpend();
	$userSpended = getAllSpendByUser();
	$colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#65A4C5", "#f1c40f", "#e74c3c", "#2c3e50"];
	$i = 0;
	$diff = 25;
    include __DIR__ . '/../views/back/dashboard.php';
}

function history(){
	//recup pdo de connexion
	$pdo = get_pdo();

	if (isset($_GET['page'])) {
		$page = ($_GET['page'] - 1) * 8;

	} else {
		$page = 0;
	}
	$depenses = getSpendByUserPart($page, 8);
	
    include __DIR__ . '/../views/back/history.php';
}

function logout(){
	session_destroy();
	header('Location: /');
	exit;
}

function addSpend(){
	session_destroy();
	header('Location: /');
	exit;
}

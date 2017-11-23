<?php
/**
 *  Point d'entrée de l'application
 *
 */

// bootstrap de l'application /  (integre ces le fichier ici)
require_once __DIR__.'/../app.php';



//HOME #ROOTER
//Si le chemin d'acces est nul allez sur la page de connection
if ('/' === $uri) {
	//lance index qui se trouve dans front_controller et include auth.php qui contient notre page
	index();
}
//CONNEXION
//Si le chemin d'acces est la page de connection et que la methode utilises dans le form est POST
elseif ('/auth' == $uri and $method == 'POST')
{
	auth();
}
//GO BACK
elseif( $uri == '/admin' ){
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	dashboard();
}
//HISTORY
elseif ( $uri == '/history' || $uri == '/history/') {
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	history();
}
//DECONNEXION
elseif ( $uri == '/logout') {
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	logout();
}
//ADD SPENSE
elseif ( $uri == '/addSpend') {
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	addSpend();
}
//404
else {
    header('HTTP/1.1 404 Not Found');
    echo $uri;
    echo '<html><body><h1>Page Not Found</h1></body></html>';
}

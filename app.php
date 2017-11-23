<?php
# démarrer les sessions en PHP
session_start();

# constantes de l'application
define('SALT', 'pU1TIYoa6f3Gmqkg0UviAewPvkCLc9mCxKJsVFUX2cU9CiasvsLei');

# constante database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DBNAME', 'sheep');

# autoloader (integre ces fichiers ici)
require __DIR__ . '/library/helpers.php';
require __DIR__ . '/controllers/front_controller.php';
require __DIR__ . '/controllers/back_controller.php';
require __DIR__ . '/model/spend_model.php';

# $_SERVER = variables globales $_SERVERu contenant plusieurs methodes affichant des informations comme les en-têtes, dossiers et chemins du script. Les entrées de ce tableau sont créées par le serveur web
# URI = Chemin du script/ identifiant une ressource sur un réseau
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
# REQUEST_METHOD =  Affiche la méthode de requête utilisée pour accéder à la page, ici 	GET / Méthode d'appel du script
$method = $_SERVER['REQUEST_METHOD'];

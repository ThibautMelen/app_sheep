<?php
  require_once __DIR__ . '/../vendor/fzaninotto/faker/src/autoload.php'; // _DIR_ => CHEMIN ABSOLUE
  $faker = Faker\Factory::create(); // faker la dépendance sous forme d'un objet PHP

  define('DB_SEED', true);
  define('NUMBER_USER', 5);

   $defaults = [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ];
  // trois arguments pour se connecter à la base données le premier c'est la chaîne de connexion, le deuxième c'est le user et le dernier pass
  $pdo = new PDO('mysql:host=localhost;dbname=sheep', 'root', '',$defaults);
  print_r($pdo);
  $users ="
    CREATE TABLE `users`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `password` VARCHAR(100) NOT NULL,
        `avatar` VARCHAR(100) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  ";

  $spends ="
    CREATE TABLE `spends`(
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `title` VARCHAR(100) NOT NULL,
      `description` TEXT NULL DEFAULT NULL,
      `price`DECIMAL(7,2) NULL DEFAULT NULL,
      `pay_date` DATETIME NULL DEFAULT NULL,
      `status` ENUM('in progress', 'canceled', 'paid') NOT NULL DEFAULT 'in progress',
      PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  ";

  $user_spend ="
    CREATE TABLE `user_spend`(
        `user_id` INT UNSIGNED NOT NULL,
        `spend_id` INT UNSIGNED NOT NULL,
        `price` DECIMAL(7,2) NULL DEFAULT NULL,
    KEY `user_spend_user_id_foreign` (`user_id`),
    KEY `user_spend_spend_id_foreign` (`spend_id`),
    CONSTRAINT `user_spend_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `user_spend_spend_id_foreign` FOREIGN KEY (`spend_id`) REFERENCES `spends` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  ";

  $parts ="
    CREATE TABLE `parts`(
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` INT UNSIGNED NULL DEFAULT NULL,
      `day` SMALLINT NOT NULL,
      `started` DATETIME NULL DEFAULT NULL,
      PRIMARY KEY (`id`),
      CONSTRAINT `parts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  ";

  $balances ="
    CREATE TABLE `balances`(
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` INT UNSIGNED NOT NULL,
      `pricePart` DECIMAL(7,2) NOT NULL,
      `priceStay`  DECIMAL(7,2) NOT NULL,
      `priceDebit`  DECIMAL(7,2) NOT NULL,
      `priceCredit`  DECIMAL(7,2) NOT NULL,
      PRIMARY KEY (`id`),
      CONSTRAINT `balance_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
  ";


  $pdo->exec("DROP TABLE IF EXISTS user_spend");
  $pdo->exec("DROP TABLE IF EXISTS parts");
  $pdo->exec("DROP TABLE IF EXISTS balances");
  $pdo->exec("DROP TABLE IF EXISTS users");
  $pdo->exec("DROP TABLE IF EXISTS spends");

  $pdo->exec($users);
  $pdo->exec($parts);
  $pdo->exec($spends);
  $pdo->exec($user_spend);
  $pdo->exec($balances);

  if (DB_SEED == true){

    // CREATION UTILISATEUR
    $prepare_user = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`) VALUES (?,?,?) ;");

    for ($i=0;$i<7;$i++) {

      $prepare_user->bindValue(1, $faker->name);
      $prepare_user->bindValue(2, $faker->unique()->email);
      $prepare_user->bindValue(3, 'admin');

      $prepare_user->execute(); // Insérer les données dans la table users
    }

    // CREATION DEPENSE
    $prepare_spend = $pdo->prepare("INSERT INTO `spends` (`title`, `description`, `status`, `price`, `pay_date`) VALUES (?,?,?,?, ?) ;");

    for ($i=0; $i<30 ; $i++) {
      $prepare_spend->bindValue(1, $faker->randomElement(['shopping', 'transport', 'location', 'energy', 'billet', 'visit', 'various']));
      $prepare_spend->bindValue(2, $faker->sentence);
          $prepare_spend->bindValue(3, $faker->randomElement(['in progress', 'canceled', 'paid']));
      $prepare_spend->bindValue(4, $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 3000));
          $t = 60*24*3600;
          $d = rand(0,$t);
          $prepare_spend->bindValue(5, date('Y-m-d h:i:s', time() - $d ));

      $prepare_spend->execute(); // Insérer les données dans la table users
    }

    // LIEN UTILISATEUR / DEPENSE

      function aleaUserIds($nbIds, $maxUser){

          $ids = [];

          while(count($ids) < $nbIds){

              $choiceId = rand(1, $maxUser);

              while(in_array($choiceId, $ids) == true ) $choiceId = rand(1, $maxUser);

              $ids[] = $choiceId;

          }

          return $ids;

      }

      $prepareUser_spend = $pdo->prepare('INSERT INTO `user_spend` (`user_id`, `spend_id`, `price`) VALUES (?, ?, ?) ');

       // récupérer toutes les dépendances
       $queryDepend = $pdo->query('SELECT id, price FROM spends');

       $depends = $queryDepend->fetchAll(); // tableau de tableau associatif

       $queryCountUser = $pdo->query('SELECT COUNT(id) as total FROM users');

       $totalUser = $queryCountUser->fetch()['total']; // renvoie avec PDO une ligne


       foreach($depends as $depend){

          if($depend['price'] > 1000){

              $nbUser = rand(2, $totalUser);
              $priceUser = round($depend['price'] / $nbUser,2);

              $ids = aleaUserIds($nbUser, $totalUser); // fonction permettant de récupérer les ids aléatoirement

              for($i = 0; $i < $nbUser; $i++)
              {

                  $prepareUser_spend->bindValue(1,$ids[$i]);
                  $prepareUser_spend->bindValue(2,$depend['id']);
                  $prepareUser_spend->bindValue(3, $priceUser);

                  $prepareUser_spend->execute(); // pour insérer effectivement

              }

          }else{

              $prepareUser_spend->bindValue(1, rand(1, $totalUser));
              $prepareUser_spend->bindValue(2,$depend['id']);
              $prepareUser_spend->bindValue(3, $depend['price']);

              $prepareUser_spend->execute(); // pour insérer effectivement

          }

       }

       $prepareUser_spend = null;
       $queryDepend = null;

  }

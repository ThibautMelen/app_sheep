<?php ob_start();
//Demarre la temporisation de sortie (en gros ca copie)
?>

<section id="form">
  <img src="/assets/logo.svg"/>

  <!-- Si l'utilisateur tente de se connecter et n'y arriver on affiche le message precedement preparer dans index.php  -->
  <?php echo getFlashMessage(); ?>

  <form action="/auth" method="post" class="login-form">
    <input type="email" placeholder="email" name="email" value="<?php echo $_SESSION['email']?? ''; ?>"/>
    <input type="password"  placeholder="password" name="password"/>

        <input type="hidden" name="token" value="<?php echo  md5( date('Y-m-d h:i:00')  . SALT ); ?>">

    <button class="button">login</button>
    <p class="message">Not registered? <a href="#">Create an account</a></p>
  </form>
</section>

<?php $content = ob_get_clean();
//Met fin a ob_start et inclu ce qui a etait copie dans la variable $content
?>
<!-- Envoi la variable content -->
<?php include __DIR__ . '/../layouts/master.php' ; ?>

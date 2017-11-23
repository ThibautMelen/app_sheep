<?php ob_start();
//Demarre la temporisation de sortie (en gros ca copie)
?>



<!-- DASHBORD -->
<section class="dahbord">

    <section class="graphic_svg">
 		   <?php include  __DIR__ . '/../partials/graphic.php'; ?>
    </section>

    <section class="">
        <?php if( $lastDepenses != false ) : ?>
       	<ul>
        	<?php foreach ($lastDepenses as $data) : ?>
            	<li>Nom(s) <?php echo htmlentities($data['names']); ?>, Prix : <?php echo intval($data['price']); ?>, date : <?php echo $data['pay_date']; ?></li>
            <?php endforeach; ?>
        </ul>
       <?php else : ?>
       	<p>Pas de dépenses pour l'instant </p>
       <?php endif; ?>
    </section>
</section>

<!-- END -->
<?php $content = ob_get_clean() ; ?>
<?php include __DIR__ . '/../layouts/master.php' ?>

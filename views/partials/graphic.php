<svg width="30vw" height="500px" class="center">
	    <?php foreach ($userSpended as $uSpend) :
	        // prix de l'uilisateur / le total * 100
	        $pourcentage = ($uSpend['price'] / $allUserSpended * 2250);
	    ?>
		<rect x="<?php echo $diff?>" y="-500" width="52" rx="10"
			fill="<?php echo $colors[$i] ?>" transform="scale(1,-1)">

			<animate attributeName="height" attributeType="XML"
					fill="freeze"
					from="0" to="<?php echo $pourcentage?>"
					begin="0s" dur="2s"/>

		</rect>

		<?php $diff+= 60; $i++?>
		<?php endforeach;?>
</svg>

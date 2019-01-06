<section id="controle" class="whatwedo-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<h1>Contrôle qualité de donnée génomique NGS</h1><hr>
				<form method="post" action="services/controle.php" enctype="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
					<label for="fichier">Importer le fichier (.fastq ou .fq) :</label>
					<div align="center">
						<input type="file" name="fichier" id="fichier">
					</div><br>
					<input type="submit" name="submit" value="Valider">
				</form>
				<?php  print_r($_FILES); ?>
			</div>
		</div>
	</div>
</section>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>MediBioTech</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/sticky-menu.css" rel="stylesheet">
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.easing.min.js"></script>
		<script src="js/scroll-animation.js"></script>
	</head>
	<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
		<?php include ("include/menu.php"); ?>
		
		<?php include ("include/accueil.php"); ?>

		<?php include ("include/services.php"); ?>
		<section id="controle" class="whatwedo-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<h1>Contrôle qualité de donnée génomique NGS</h1><hr>
						<form method="post" action="index.php#controle" enctype="multipart/form-data">
							<input type="hidden" name="MAX_FILE_SIZE" value="860000000" />
							<div class="form-group">
								<label for="fichier">Importer le fichier FASTQ (.fastq ou .fq) :</label>
								<div align="center">
									<input type="file" name="fichier" id="fichier">
								</div><br>
							</div>
							
							<input type="submit" name="submit" value="Valider">
						</form>
						<?php
							$fastqDir = "fastqFiles/";
							$allowed =  array('fastq','fq');
							if(isset($_FILES['fichier'])){
								$filename = $fastqDir.$_FILES['fichier']['name'];
								$ext = pathinfo($filename, PATHINFO_EXTENSION);
								if(!in_array($ext, $allowed)) {
						?>
									<font color="red"><i> Mauvais type de fichier, veuillez importer un fichier fastq ou fq </i></font>
						<?php
								} else {
									if(check_structure($filename)){
										$nb_reads = nb_reads($filename);
										$nb_base_pb = nb_base_pb($filename);
										$length_sequences = range_file($filename);
										$moyenne = moyenne($length_sequences, $nb_reads);
										$nb_GC_AT = nb_GC_AT($filename);
										$nb_base_N = nb_base_N($filename);
										$score = score($filename);
						?>
										<br><br>
										<table class="table">
											<caption>Rapport de qualité</caption>
											<thead>
												<tr>
													<td><b>Nombre de reads<b></td>
													<td><b>Nombre de base PB<b></td>
													<td><b>Range<b></td>
													<td><b>Longueur moyenne<b></td>
													<td><b>%GC<b></td>
													<td><b>Nombre de base N<b></td>
													<td><b>Score moyen de la séquence<b></td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><?php echo $nb_reads; ?></td>
													<td><?php echo $nb_base_pb; ?></td>
													<td><?php echo min($length_sequences) . " | " . max($length_sequences); ?></td>
													<td><?php echo $moyenne; ?></td>
													<td><?php echo $nb_GC_AT; ?></td>
													<td><?php echo $nb_base_N; ?></td>
													<td><?php echo $score/$nb_base_pb; ?></td>
												</tr>
											</tbody>
										</table>
										<br>
										<a class="btn btn-primary page-scroll" href="filtrer.php?filename=<?php echo $filename; ?>" target="_blank">Améliorer la qualité</a><br>
						<?php			
									} else {
						?>
										<font color="red"><i> Le fichier fastq est invalide </i></font>
						<?php
									}
								}		
							}								
						?>
					</div>
				</div>
			</div>
		</section>
		<section id="integration" class="whatwedo-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<h1>Intégration sémantique</h1><hr>
						<form method="post" action="index.php#integration" enctype="multipart/form-data">
							<input type="hidden" name="MAX_FILE_SIZE" value="860000000" />
							<div class="form-group">
								<label for="fasta_fichier">Importer le fichier FASTA (.fasta ou .fa) :</label>
								<div align="center">
									<input type="file" name="fasta_fichier" id="fasta_fichier">
								</div>
							</div>
							<div class="form-group">
								<label for="organisme">Organisme origine</label>
								<input type="text" class="form-control" id="organisme" name="organisme" placeholder="Saisir organisme" required="true">
							</div>
							<div class="form-group">
								<label for="date">Date du séquençage</label>
								<input type="date" class="form-control" id="date" name="date" placeholder="Saisir la date du séquençage (AAAA-mm-jj)" required="true"
							</div>
							<div class="form-group">
								<label for="machine">Machine utilisée</label>
								<input type="text" class="form-control" id="machine" name="machine" placeholder="Saisir la machine utilisée pour le séquençage" required="true">
							</div>
							<div class="form-group">
								<label for="infos">Informations complémentaires</label>
								<textarea class="form-control" id="infos" name="infos" rows="3"></textarea>
							</div>
							<div class="form-group">
								<input type="submit" name="submit" value="Valider"><br>
							</div>
						</form>
						<?php
							$fastaDir = "fastaFiles/";
							$fasta_allowed =  array('fasta', '.fa');
							if(isset($_FILES['fasta_fichier'])){
								$fasta_filename = $fastaDir.$_FILES['fasta_fichier']['name'];
								$fasta_ext = pathinfo($fasta_filename, PATHINFO_EXTENSION);
								if(!in_array($fasta_ext, $fasta_allowed)) {
						?>
									<font color="red"><i> Mauvais type de fichier, veuillez importer un fichier fasta ou fa</i></font>
						<?php
								} else {
									$fasta_sequences = getSequencesFromFasta($fasta_filename);
									include "createFastaDataBase.php";
								}		
							}								
						?>
					</div>
				</div>
			</div>
		</section>
		<section id="recherche" class="whatwedo-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<h1>API-REST Recherche</h1><hr>
						<form method="get" action="recherche.php" target="_blank">
							<div class="form-group">
								<label for="recherche">Termes à rechercher</label>
								<input type="text" id="recherche" name="recherche" class="form-control" placeholder="Tapez les mots clés de votre recherche"><br>
							</div>
							<div class="form-group">
								<label for="nbre_resultats">Nombre de résultats souhaités</label>
								<input type="number" id="nbre_resultats" name="nbre_resultats" class="form-control"><br>
							</div>
								<button type="submit" class="btn btn-default"> Rechercher </button>
							</div>
						</form>							
					</div>
				</div>
			</div>
		</section>
		<?php include ("include/contact.php"); ?>
		<?php
			function getSequencesFromFasta($filename) {
				$sequences_id_array = array();
				$sequences_values_array = array();
				$sequences_desc = array();
				
				$handle = fopen($filename, "r");
				$seq_value_char = "";
				if ($handle) {
					while (!feof($handle)) {
						$buffer = fgets($handle);		
						if($buffer[0] === ">"){
							if($seq_value_char ==! "") {
								$sequences_values_array[] = $seq_value_char;
								$seq_value_char = "";
							}
							$sequences_id_array[] = trim(substr($buffer, 1));
						} else if (strlen($buffer) === 0) {
								$sequences_values_array[] = $seq_value_char;
								$seq_value_char = "";
						} else {
							$seq_value_char .= trim($buffer) . "\n";
						}
					}
					fclose($handle);
				} else {
					echo "Error while reading the file " . $filename;
				}
				$sequences_desc[] = $sequences_id_array;
				$sequences_desc[] = $sequences_values_array;
				
				return $sequences_desc;
			}
			
			function check_structure($filename) {				
				$nbLigne = 0;
				$seq_id_array = array();
				$seq_array = array();
				$plus_array = array();
				$qualite_array = array();
				
				$handle = fopen($filename, "r");
				if ($handle) {
						while (!feof($handle)) {
							$buffer = fgets($handle);
							if($nbLigne % 4 === 0){
								$seq_id_array[] = trim($buffer);
							}
							if($nbLigne % 4 === 1){
								$seq_array[] = trim($buffer);
							}
							if($nbLigne % 4 === 2){
								$plus_array[] = trim($buffer);
							}
							if($nbLigne % 4 === 3){
								$qualite_array[] = trim($buffer);
							}
							$nbLigne++;
						}
						fclose($handle);
				} else {
						echo "Error while reading the file " . $filename;
				}

				/* 
					la qualité doit être composée que de ces caractères suivants
					voir (https://www.france-bioinformatique.fr/sites/default/files/formats.pdf)
				*/
				$qualite_char_allowed = array (
					'!', '\"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ':', ';', '<', '=', '>', '?', '@', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'
				);
				$sequence_char_allowed = array (
					'N', 'T', 'G', 'C', 'A'
				);
				
				$valid_file = false;
				if(
					valide_seq_id($seq_id_array) &&
					valide_sequence($seq_array, $sequence_char_allowed) &&
					valide_plus($plus_array) &&
					valide_qualite_structure($qualite_array, $qualite_char_allowed)
				){
					$valid_file = true;
				}
				
				return $valid_file;
			}
	
			function valide_seq_id($seq_id_array) {
				foreach($seq_id_array as $seq_id){
					$first_char = $seq_id[0];
					if($first_char !== "@") {
						return false;
					}
				}
				return  true;
			}

			function valide_sequence($sequence_array, $sequence_char_allowed) {
				foreach($sequence_array as $sequence){
					$index = 0;
					//echo $sequence . "<br>";
					while($index < strlen($sequence)){
						$sequence_char = trim($sequence[$index]);
						//echo $sequence_char . "<br>";
						if(!in_array($sequence_char, $sequence_char_allowed)) {
							//echo "NON TROUVE <br>";
							return false;
						}
						//echo "TROUVE <br>";
						$index++;
					}
				}
				return true;
			}
			
			function valide_plus($plus_array) {
				foreach($plus_array as $plus){
					if($plus !== "+") {
						return false;
					}
				}
				return  true;
			}
		
			function valide_qualite_structure($qualite_array, $qualite_char_allowed) {
				foreach($qualite_array as $qualite){
					$index = 0;
					//echo $qualite . "<br>";
					while($index < strlen($qualite)){
						$qualite_char = trim($qualite[$index]);
						//echo $qualite_char . "<br>";
						if(!in_array($qualite_char, $qualite_char_allowed)) {
							//echo "NON TROUVE <br>";
							return false;
						}
						//echo "TROUVE <br>";
						$index++;
					}
				}
				return true;
			}
			
			function moyenne($array, $nb){
				$somme = 0;
				foreach($array as $element) {
					$somme += $element;
				}
				
				return $somme/$nb;
			}
			
			function nb_reads($filename) {
				$handle = fopen($filename, "r");
				$nbLigne = 0;
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						$nbLigne++;
					}
				}
				fclose($handle);
				
				return $nbLigne/4;
			}
			
			function nb_base_N($filename) {
				$handle = fopen($filename, "r");
				$seq_array = array();
				$nbLigne = 0;
				$nb_base_n = 0;
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						if($nbLigne % 4 === 1){
							$seq_array[] = trim($buffer);
						}
						$nbLigne++;
					}
				}
				fclose($handle);
				
				foreach($seq_array as $seq){
					$nb_base_n += substr_count($seq, "N");
				}
				return $nb_base_n;
			}
			
			function score($filename) {
				$handle = fopen($filename, "r");
				$nbLigne = 0;
				$score = 0;
				$score_array = array();
				$qualite_char_scores = array (
					0 =>'!', 1 =>'\"' ,  2 =>'#', 3 =>'$', 4 =>'%', 5 =>'&', 6 =>'\'',
					 7 =>'(',  8 =>')',  9 =>'*', 10 =>'+', 11 =>',', 12 =>'-', 13 =>'.',
					 14 =>'/', 15 =>'0' , 16 =>'1', 17 =>'2', 18 =>'3', 19 =>'4', 20 =>'5',
					21 =>'6' , 22 =>'7' , 23 =>'8', 24 =>'9', 25 =>':', 26 =>';', 27 =>'<',
					28 =>'=' ,29 => '>' ,30 => '?', 31 =>'@', 32 =>'A', 33 =>'B', 34 =>'C',
					35 =>'D' , 36 =>'E' , 37 =>'F', 38 =>'G', 39 =>'H', 40 =>'I'
				);
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						if($nbLigne % 4 === 3){
							$score_array[] = trim($buffer);
						}
						$nbLigne++;
					}
				}
				fclose($handle);
				
				foreach($score_array as $elem){
					$tmp = 0;
					$i = 0;
					while($i<strlen($elem)){
						$val = array_search(trim($elem[$i]), $qualite_char_scores);
						$score += $val;
						$tmp += $val;
						$i++;
					}
					//echo $tmp . "<br>";
				}
				return $score;
			}
			
			function nb_base_pb($filename) {
				$handle = fopen($filename, "r");
				$seq_array = array();
				$nbLigne = 0;
				$nb_base_pb = 0;
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						if($nbLigne % 4 === 1){
							$seq_array[] = trim($buffer);
						}
						$nbLigne++;
					}
				}
				fclose($handle);
				
				foreach($seq_array as $seq){
					$nb_base_pb += substr_count($seq, "AT") + substr_count($seq, "GC");
				}
				return $nb_base_pb;
			}
			
			function nb_GC_AT($filename) {
				$handle = fopen($filename, "r");
				$seq_array = array();
				$nbLigne = 0;
				$nb_GC = 0;
				$nb_AT = 0;
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						if($nbLigne % 4 === 1){
							$seq_array[] = trim($buffer);
						}
						$nbLigne++;
					}
				}
				fclose($handle);
				
				foreach($seq_array as $seq){
					$nb_AT += substr_count($seq, "AT");
					$nb_GC += substr_count($seq, "GC");
				}
				
				return $nb_GC/$nb_AT;
			}

			function range_file($filename) {
				$handle = fopen($filename, "r");
				$seq_array = array();
				$length_array = array();
				$nbLigne = 0;
				if ($handle) {
					while (!feof($handle)){
						$buffer = fgets($handle);
						if($nbLigne % 4 === 1){
							$seq_array[] = trim($buffer);
						}
						$nbLigne++;
					}
				}
				
				fclose($handle);
				
				// On calcul la longueur de chaque séquence
				foreach($seq_array as $seq){
					//echo strlen($seq) . " ";
					$length_array[] = strlen($seq);
				}
				
				return $length_array;
			}
		?>
	</body>
</html>

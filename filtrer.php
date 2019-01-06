<?php
	$filename = $_GET['filename'];
	$filtred_filename = pathinfo($filename, PATHINFO_FILENAME) . "_filtred.fastq";
	$handle = fopen($filename, "r");
	$filtred_file = fopen($filtred_filename, "w") or die("Impossible d'ouvrir le fichier !");
	$nbLigne = 0;
	$infos_to_stock = array();
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
			$infos_to_stock[] = trim($buffer);
			if($nbLigne>0 && $nbLigne % 4 === 3){
				$score = 0;
				$i = 0;
				$qualite = $infos_to_stock[3];
				while($i<strlen($qualite)){
					$val = array_search(trim($qualite[$i]), $qualite_char_scores);
					$score += $val;
					$i++;
				}
				if(($score/nb_reads($filename)) > 20){
					foreach($infos_to_stock as $info){
						//echo $info . "<br>";
						fwrite($filtred_file, $info.PHP_EOL);
					}
					//echo $nbLigne . "<br>";
				}
				$infos_to_stock = array();
			}
			$nbLigne++;
		}
	}
	fclose($handle);
	fclose($filtred_file);

	// Supprimer la dernière ligne du nouveau fichier généré (ligne vide)
	file_put_contents($filtred_filename,
    preg_replace(
        '~[\r\n]+~',
        "\r\n",
        trim(file_get_contents($filtred_filename))
    )
	);

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
	
	$nb_base_pb = nb_base_pb($filtred_filename);
	$score = score($filtred_filename);
?>
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
	<body>
		<table class="table">
			<caption>Rapport de qualité</caption>
			<thead>
				<tr>
					<td><b>Score moyen de la séquence<b></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $score/$nb_base_pb; ?></td>
				</tr>
			</tbody>
		</table>
	</body>
</html>
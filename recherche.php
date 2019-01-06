<?php
	$mots_recherches = $_GET['recherche'];
	$nbre_resultats = $_GET['nbre_resultats'];	
	$mots_recherches = preg_replace('/\s+/', '+', $mots_recherches);

	$curl = curl_init();
		
	$url = 'https://www.ebi.ac.uk/ena/data/search?query='.$mots_recherches.'&result=sequence_release&display=fasta&offset=1&length='.$nbre_resultats;
	
	//echo $url;
	
	curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url
	));
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
	$result = curl_exec($curl);
	if(!$result){
    die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
	}
	
	//print_r($result);
	
	curl_close($curl);
	
	$alea = rand();
	$search_filename = "search_file_".$alea.".fasta";
	$search_filepath = "tmp/" . $search_filename;
	
	$search_file = fopen($search_filepath, "w") or die("Impossible d'ouvrir le fichier !");
	fwrite($search_file, $result);
	fclose($search_file);
	
	$sequences_desc = traiter_search_file($search_filepath);

	function traiter_search_file($filename) {
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
		
		$sequences_id = $sequences_desc[0];
		$sequences_values = $sequences_desc[1];
		
		// Enregistrement de la recherche sous format xml
		$dom = new DOMDocument();
		$imp = new DOMImplementation();
		$dtd = $imp->createDocumentType('recherche', '', 'dtd_xsd/searchResult.dtd');
		$dom -> encoding = 'utf-8';
		$dom -> xmlVersion = '1.0';
		$dom -> formatOutput = true;
		$dom = $imp->createDocument("", "", $dtd);
		
		$xml_file_name = "searchResult" . $alea . ".xml";
		$recherche_node = $dom->createElement('recherche');
		$sequences_node = $dom->createElement('sequences');
		for ($i = 0; $i < sizeof($sequences_id); $i++) {
				$sequence_node = $dom->createElement('sequence');
				$sequence_id_node = $dom->createElement('sequence_id', $sequences_id[$i]);
				$sequences_value_node = $dom->createElement('sequence_value', $sequences_values[$i]);
				
				$sequences_node->appendChild($sequence_node);
				$sequence_node->appendChild($sequence_id_node);
				$sequence_node->appendChild($sequences_value_node);
		}

		$attr_recherche_nameSpace = new DOMAttr('xs:noNamespaceSchemaLocation', 'dtd_xsd/searchResult.xsd');
		$attr_recherche_xmlns = new DOMAttr('xmlns:xs', 'http://www.w3.org/2001/XMLSchema-instance');
		$recherche_node->setAttributeNode($attr_recherche_nameSpace);
		$recherche_node->setAttributeNode($attr_recherche_xmlns);

		$dom->appendChild($recherche_node);

		$recherche_node->appendChild($sequences_node);
		
		$dom->save('generatedXML/' . $xml_file_name);
?>
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
			<caption>Résultat de la recherche</caption>
			<thead>
				<tr>
					<td class="col-md-6" align="center"><b>Identifiant de la séquence<b></td>
					<td align="center"><b>Valeur de la séquence<b></td>
				</tr>
			</thead>
			<tbody>
				<?php
					for ($i = 0; $i < sizeof($sequences_id); $i++) {
				?>
				<tr>
					<td align="center"><?php echo $sequences_id[$i]; ?></td>
					<td><?php echo $sequences_values[$i]; ?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</body>
</html>
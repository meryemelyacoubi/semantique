<?php
	if(isset($_POST['submit'])){
		$organisme = $_POST['organisme'];
		$date = $_POST['date'];
		$machine = $_POST['machine'];
		$other_infos = $_POST['infos'];

		$sequences_id = $fasta_sequences[0];
		$sequences_values = $fasta_sequences[1];

		$dom = new DOMDocument();
		$imp = new DOMImplementation();
		$dtd = $imp->createDocumentType('description', '', 'dtd_xsd/fastaDTD.dtd');
		$dom -> encoding = 'utf-8';
		$dom -> xmlVersion = '1.0';
		$dom -> formatOutput = true;
		$dom = $imp->createDocument("", "", $dtd);

		$xml_file_name = pathinfo($fasta_filename, PATHINFO_FILENAME) . "_fastaDataBase.xml";

		$description_node = $dom->createElement('description');
		$sequences_node = $dom->createElement('sequences');
		for ($i = 0; $i < sizeof($sequences_id); $i++) {
				$sequence_node = $dom->createElement('sequence');
				$sequence_id_node = $dom->createElement('sequence_id', $sequences_id[$i]);
				$sequences_value_node = $dom->createElement('sequence_value', $sequences_values[$i]);
				
				$sequences_node->appendChild($sequence_node);
				$sequence_node->appendChild($sequence_id_node);
				$sequence_node->appendChild($sequences_value_node);
		}
		$organisme_node = $dom->createElement('organisme', $organisme);
		$date_node = $dom->createElement('date', $date);
		$machine_node = $dom->createElement('machine', $machine);
		$other_infos_node = $dom->createElement('other_informations', $other_infos);

		$attr_description_nameSpace = new DOMAttr('xs:noNamespaceSchemaLocation', 'dtd_xsd/fastaSchema.xsd');
		$attr_description_xmlns = new DOMAttr('xmlns:xs', 'http://www.w3.org/2001/XMLSchema-instance');
		$description_node->setAttributeNode($attr_description_nameSpace);
		$description_node->setAttributeNode($attr_description_xmlns);

		$dom->appendChild($description_node);

		$description_node->appendChild($sequences_node);
		$description_node->appendChild($organisme_node);
		$description_node->appendChild($date_node);
		$description_node->appendChild($machine_node);
		$description_node->appendChild($other_infos_node);
		
		$dom->save('generatedXML/' . $xml_file_name);
?>	<div class="form-group">
			<font color='green'><i> <?php echo $xml_file_name; ?> a été créée avec succès </i></font><br>
		</div>
<?php
	}
?>
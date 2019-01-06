---------------------------------------------------------------------------------------
------------------------------------------ Guide d'utilisation-------------------------
---------------------------------------------------------------------------------------
Pour démarrer le projet, déposer le dossier semantique dans le dosser www de wamp.
Aller à l'url:
http://localhost/semantique/index.php

Pour la partie Contrôle de qualité :
- Il faut que le fichier fastq soit dans le dossier "fastqFiles".
- Le fichier de séquence filtré est généré ensuite dans le même dossier "fastqFiles".


Pour la partie Intégration sémantique :
- Il faut que le fichier fasta soit dans le dossier fastaFiles.
- Le fichier fasta utilisé doit contenir une ligne vide à la fin du fichier.
- Les DTD et XSD sont dans le dossier "dtd_xsd".
  - fastaDTD.dtd
  - fastaSchema.xsd
- Le fichier XML est généré dans le dossier "generatedXML"


Pour la partie API/REST Recherche :
- Les DTD et XSD sont dans le dossier "dtd_xsd".
- searchResult.dtd
- searchResult.xsd
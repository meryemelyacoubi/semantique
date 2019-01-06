---------------------------------------------------------------------------------------
------------------------------------------ Guide d'utilisation-------------------------
---------------------------------------------------------------------------------------
Pour d�marrer le projet, d�poser le dossier semantique dans le dosser www de wamp.
Aller � l'url:
http://localhost/semantique/index.php

Pour la partie Contr�le de qualit� :
- Il faut que le fichier fastq soit dans le dossier "fastqFiles".
- Le fichier de s�quence filtr� est g�n�r� ensuite dans le m�me dossier "fastqFiles".


Pour la partie Int�gration s�mantique :
- Il faut que le fichier fasta soit dans le dossier fastaFiles.
- Le fichier fasta utilis� doit contenir une ligne vide � la fin du fichier.
- Les DTD et XSD sont dans le dossier "dtd_xsd".
  - fastaDTD.dtd
  - fastaSchema.xsd
- Le fichier XML est g�n�r� dans le dossier "generatedXML"


Pour la partie API/REST Recherche :
- Les DTD et XSD sont dans le dossier "dtd_xsd".
- searchResult.dtd
- searchResult.xsd
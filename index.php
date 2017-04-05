<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<?php 
	$string = file_get_contents("dictionnaire.txt", FILE_USE_INCLUDE_PATH);
	$dico = explode("\n", $string);
	//var_dump($dico);

	echo "Mots dans le dictionnaire :" . count($dico);
	echo "<br>";	

	$nombreMots=0;
	$whereW=0;
	$whereQ=0;

	foreach ($dico as $key => $mot) {
		$longueurMot = strlen($mot);
		if($longueurMot===15){
			$nombredeMots++;
		}
		$posW= strpos($mot, "w");
		if($posW !== false){
			$whereW++;
		}
		$posQ= strpos($mot, "q", strlen($mot)-1);
		if($posQ !== false){	
			$whereQ++;
		}
	}
	echo "Nbr de mots qui ont 15 lettres: " . $nombredeMots;
	echo "<br>";
	echo "Mots qui ont la lettre w: " . $whereW;
	echo "<br>";
	echo "Mots qui finnissent par q: " . $whereQ;

	$string = file_get_contents("films.json", FILE_USE_INCLUDE_PATH);
	$brut = json_decode($string, true);
	$top = $brut["feed"]["entry"]; # liste de films
	$top100 = count($top);

	echo "<h3>Top 10 from IMDB</h3>";
	for ($i=1; $i < 11 ; $i++) { 
		$titre = $top[$i]["im:name"]["label"];
		echo $i . " " . $titre;
		echo "<br>";
	}

	for ($i=0; $i < 100 ; $i++) { 
		$titre = $top[$i]["im:name"]["label"];
		if ($titre === 'Gravity'){
			echo "<h3>Classement de Gravity:</h3>". $i;
		}
	}

	echo "<h4>Realisateur de The lego Movie</h4>";
	for ($i=0; $i < $top100 ; $i++) {
		$titre = $top[$i]['im:name']["label"];
		if($titre == "The LEGO Movie"){
			echo 'Les réalisateurs :'. $top[$i]["im:artist"]["label"];
		}
	}
	echo "<h3>Nombres de films sortie avant 2000</h3>";
	$nbFilm =0;
	for ($i=0; $i < $top100 ; $i++) { 
		$date = $top[$i]['im:releaseDate']['label'];
		if (date_parse($date)['year']<2000) {
			$nbFilm++ ;
		}
	}
	echo $nbFilm . " films";

	echo "<h3>Film le plus récent</h3>";
	// $recent = '';
	for ($i=0; $i < $top100; $i++) { 
		if($i==0){
			$recent=$top[$i];
		}
		else{
			$date = $top[$i]['im:releaseDate']['label'];
			$contenu = $recent['im:releaseDate']['label'];
			if($date>$contenu){
				$recent=$top[$i];
			}
		}
	}
	echo $recent['im:name']['label'];

	echo "<h3>Film le plus ancien</h3>";

	for ($i=0; $i < $top100; $i++) { 
		if($i==0){
			$ancien=$top[$i];
		}
		else{
			$date = $top[$i]['im:releaseDate']['label'];
			$interne = $ancien['im:releaseDate']['label'];
			if($date<$interne){
				$ancien=$top[$i];
			}
		}
	}
	echo $ancien['im:name']['label'];

	echo "<h3>Categorie la plus représenté:</h3>";
	$categorie=array();
	foreach ($top as $key => $film) {
		$catFilm=$film['category']['attributes']['label'];
		$categorie[$catFilm]++;
	}

	$plusRepresente=array('category'=>'', 'nombre'=> 0	);
	foreach ($categorie as $cat => $nb) {
		if($nb > $plusRepresente['nombre']){
			$plusRepresente = array('category'=> $cat, 'nombre' => $nb);
		}
	}
	echo $plusRepresente['category'];

	echo "<h3> Réalisateur le plus representé</h3>";
	foreach ($top as $key => $film) {
		$real = $film['im:artist']['label'];
		$realList[$real]++;
	}
	$realPlus = array('realisateur' => '', 'nombre' => 0);
	foreach ($realList as $real => $nombre) {
		if($nombre > $realPlus['nombre']){
			$realPlus = array('realisateur'=>$real, 'nombre' => $nombre);
		} 
	}
	echo $realPlus['realisateur'];

	echo "<h3>Prix du top 10</h3>";

	$achat=0;
	$location=0;
	for ($i=0; $i < 10; $i++) { 
		$prixA = $top[$i]['im:price']['attributes']['amount'];
		$prixL = $top[$i]['im:rentalPrice']['attributes']['amount'];
		$achat += $prixA;
		$location += $prixL;
	}

	echo $achat . " €";
	echo '<br>';
	echo $location . " €";

	echo "<h3>Mois qui a reçu le plus de film</h3>";


	$famous = [];
	$famous1 = [];
	foreach ($top as $key => $film) {
		$famous[explode(' ', $film['im:releaseDate']['attributes']['label'])[0]]++ ;
	}arsort($famous) ;
	$famous1 = array_keys($famous, max($famous));
	if($famous > 1){
		foreach ($famous1 as $key => $value) {
			echo $value . '<br>';
		}
	}else{
		echo $famous1[0];
	}

	echo 'Les 10 meilleurs films à voir';

	foreach ($top as $key => $film) {
		$dollar = $film['im:price']['label'];
		echo $dollar;
	}
	?>
</body>
</html>
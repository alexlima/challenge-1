<?php 

class Googlon 
{ 
    private $text;
    public $words;
    private $foo = array('h', 'c', 'k', 'x', 'b');
    private $alphabet = array('t', 'q', 'j', 'b', 'n', 'k', 'g', 'x', 'r', 'c', 'd', 'l', 'f', 'p', 'z', 'm', 'v', 'h', 's', 'w');
    
	function __construct($file) 
    { 
        $this->file = $file;
        $this->text = trim(file_get_contents($file));
    	$this->words = explode(" ", $this->text);
	} 

    // As preposições são as palavras de 3 letras que terminam numa letra tipo bar, mas onde não ocorre a letra d. 
    function prepositions()
    {
    	$prepositions = array();
    	foreach ($this->words as $key => $word) {
			if(strlen($word) == 3){
				$char = substr($word, -1);
				if(!in_array($char, $this->foo)){
					if(strpos($word, 'd') === false){
						$prepositions[] = $word;
					}
				}
			}
		}
		return $prepositions;
	}

	// Os verbos são palavras de 8 ou mais letras que terminam numa letra tipo foo.
    function verbs()
    {
    	$verbs = array();
    	foreach ($this->words as $key => $word) {
			if(strlen($word) >= 8){
				$char = substr($word, -1);
				if(in_array($char, $this->foo)){
					$verbs[] = $word;
				}
			}
		}
		return $verbs;
    }

    // Se um verbo começa com uma letra tipo foo, o verbo está em primeira pessoa.
	function verbsInFirstPerson()
	{
		$verbs = array();
    	$words = $this->verbs();
    	foreach ($words as $key => $word) {
			if(in_array($word[0], $this->foo)){
				$verbs[] = $word;
			}
		}
		return $verbs;
	}

	function bubbleSortImproved(array $arr)
	{
	    $n = sizeof($arr);
	    for ($i = 1; $i < $n; $i++) {
	        $flag = false;
	        for ($j = $n - 1; $j >= $i; $j--) {
	            // if($arr[$j-1] > $arr[$j]) {
	            if($this->heavierThan($arr[$j-1], $arr[$j])){
	                $tmp = $arr[$j - 1];
	                $arr[$j - 1] = $arr[$j];
	                $arr[$j] = $tmp;
	                $flag = true;
	            }
	        }
	        if (!$flag) {
	            break;
	        }
	    }
	 
	    return $arr;
	}

	// Essas listas devem estar ordenadas e não podem conter repetições de palavras. 
	function vocabulary()
	{
		$words = $this->words;
		$vocabulary = $this->bubbleSortImproved($words);
		$vocabulary = array_unique($vocabulary);
		$vocabulary = array_reverse($vocabulary);
		
		// print_r("<pre>");
		// print_r($vocabulary);
		return $vocabulary;
	}

	function heavierThan($word1, $word2)
	{
		$word = $this->compareWith($word1, $word2);
		if($word == $word1){
			return true;
		}
		return false;
	}

	function compareWith($word1, $word2)
	{
		$length = strlen($word1);
		if ($length > strlen($word2)) {
			$length = strlen($word2);
		}
		for ($i = 0; $i < $length; $i++) { 
			if($this->letterWeight($word1[$i]) > $this->letterWeight($word2[$i])){
				return $word1;
			}
			if($this->letterWeight($word1[$i]) < $this->letterWeight($word2[$i])){
				return $word2;
			}
		}
		if(strlen($word1) > strlen($word2)){
			return $word2;
		}
		return $word1;
	}

	function letterWeight($letter)
	{
		$index = array_search($letter, $this->alphabet);
		$weight = count($this->alphabet) - $index;
		return $weight;
	} 

	// As palavras também são números dados em base 20, onde cada letra é um dígito, e os dígitos são ordenados do menos significativo para o mais significativo. Os valores das letras são dados pela ordem em que elas aparecem no alfabeto.
	function base20($word)
	{
		$total = 0;
		for ($i = 0; $i < strlen($word); $i++) { 
			$index = array_search($word[$i], $this->alphabet);
			$weight = pow(20, $i) * $index;
			$total = $total + $weight;
		}
		return $total;
	} 

	function isDivisible($word)
	{
		$total = 0;
		for ($i = 0; $i < strlen($word); $i++) { 
			$index = array_search($word[$i], $this->alphabet);
			$total = $total + $index;
		}
		return ($total % 3 == 0);
	} 

	// Os Googlons consideram um número bonito se ele satisfaz essas duas propriedades:
	// 1) É maior ou igual a 444741;
	// 2) É divisível por 3;
	function beautifulNumbers()
	{
		$numbers = array();
    	foreach ($this->words as $key => $word) {
			$weight = $this->base20($word);
			if($weight >= 444741){
				if($this->isDivisible($word)){
					$numbers[] = $word;
				}
			}
		}
		return $numbers;
	}

	function describe()
	{
		echo("File: " . $this->file . "<br>");
		echo("Prepositions: " . count($this->prepositions()) . "<br>");
		echo("Verbs: " . count($this->verbs()) . "<br>");
		echo("First person: " . count($this->verbsInFirstPerson()) . "<br>");
		echo("Beautiful numbers: " . count($this->beautifulNumbers()) . "<br>");
		echo("Vocabulary: " . implode(" ", $this->vocabulary()) . "<br>");
		echo("<br>");
	}
} 

$googlonA = new Googlon("textoA.txt"); 
$googlonA->describe();

$googlonB = new Googlon("textoB.txt"); 
$googlonB->describe();
?> 

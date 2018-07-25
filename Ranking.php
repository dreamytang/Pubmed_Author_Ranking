<?php
 
//Configuration
$filename = "pubmed_result.xml"; //File exported from Pubmed
$listnum = 10; //Top $listnum authors ordered by frequency

//Processing
$author_array = array();
$doc = new DOMDocument();
$doc->load($filename);
$paper_nodes = $doc->getElementsByTagName("PubmedArticle");
if ($paper_nodes->length !== 0){
    for ($i = 0; $i < $paper_nodes->length; $i++){
        $paper_node = $paper_nodes->item($i);
        $author_nodes = $paper_node->getElementsByTagName("Author");
        if ($author_nodes->length !== 0){
            for ($ii = 0; $ii < $author_nodes->length; $ii++){
                $author_node = $author_nodes->item($ii);
                $lastname = $author_node->getElementsByTagName("LastName")->item(0)->nodeValue;
                $firstname = $author_node->getElementsByTagName("ForeName")->item(0)->nodeValue;
                array_push($author_array, $firstname . " " . $lastname);
            }
        }
    }
}
$author_array_ranking = array_count_values($author_array);
arsort($author_array_ranking);

//Show Ranking
if (count($author_array_ranking) > 0 && count($author_array_ranking) < $listnum){$listnum = count($author_array_ranking);}
$for_number = 0;
echo "<strong>Author Ranking</strong><br />";
foreach ($author_array_ranking as $key=>$value){
    echo "<strong>Author Name:</strong> " . $key . " <strong>Frequency:</strong> " . $value . "<br />";
    $for_number++;
    if($for_number == $listnum){break;}
}
?>
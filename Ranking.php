<?php
//Record start time
$start = microtime(true);

//Configuration
$filename = "pubmed_result.txt"; //File exported from Pubmed
$listnum = 10; //Top $listnum authors ordered by frequency

//Function
function ReadMedline($file) {
    $author_array = array();
    $filestream = fopen($file, 'r');

    while(!feof($filestream)) {
        $author_name = trim(fgets($filestream));
        if (!empty($author_name) && strpos($author_name, "FAU - ") !== false){
            array_push($author_array, str_replace("FAU - ", "", $author_name));
        }
    }
    fclose($filestream);
    return $author_array;
}

//Processing
$authors_array = ReadMedline($filename);
$authors_array_ranking = array_count_values($authors_array);
arsort($authors_array_ranking);

//Show Ranking
if (count($authors_array_ranking) > 0 && count($authors_array_ranking) < $listnum){$listnum = count($authors_array_ranking);}
$for_number = 0;
echo "<strong>Author Ranking</strong><br /><br />";
foreach ($authors_array_ranking as $key=>$value){
    echo "<strong>Author Name:</strong> " . $key . " <strong>Frequency:</strong> " . $value . "<br />";
    $for_number++;
    if($for_number == $listnum){break;}
}

//Record end time and calculate processing time
$end = microtime(true);
$delta = $end - $start;

//Calculate memory consumption
function formatBytes($bytes, $precision = 2) {
    $units = array('b', 'kb', 'mb', 'gb', 'tb');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

echo "<br />Memory consumption: " . formatBytes(memory_get_peak_usage());

//Output processing time
echo "<br />Execution time: " . $delta . PHP_EOL . " s";

?>

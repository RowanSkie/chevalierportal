<?php
function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="'.$filename.'";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
		    $line=preg_replace('/(?<=^|;)"(.+)"(?=;)/','$1',$line);
        fputcsv($f, $line, $delimiter);
        //fwrite($f,"\n");
    }

}
?>

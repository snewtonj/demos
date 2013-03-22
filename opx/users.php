<?php
if (($handle = fopen("opx-users.csv", "r")) !== FALSE) {
    $header = fgetcsv($handle, 1024);
    while (($data = fgetcsv($handle, 1024)) !== FALSE) {
      $num_fields = count($data);
      $entry = array();
      for($i =0; $i<$num_fields; $i++) {
        $entry[$header[$i]] = $data[$i]; 
      }
      echo $entry['sreg_email'], "\n";
    }
    fclose($handle);
}
?>

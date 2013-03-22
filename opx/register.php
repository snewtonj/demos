<?php

$username = $_POST['username'];

if (strlen($username) > 0) {
  // check if user exists
  $taken = false;
  if (($handle = fopen("opx-users.csv", "r")) !== FALSE) {
    $header = fgetcsv($handle, 1024);
    while (!$taken && (($data = fgetcsv($handle, 1024)) !== FALSE)) {
      if ($data[0] === $username) {
        echo "<strong>" . $username . "</strong> is already taken\n";
        $taken = true;
      }
    }
    fclose($handle);
  }

  if (!$taken) {
    $password = $_POST['password'];
    $nickname = $_POST['sreg_nickname'];
    $email = $_POST['sreg_email'];
    $fullname = $_POST['sreg_fullname'];
    $postal = $_POST['sreg_postcode'];
    $dob = $_POST['sreg_dob'];
    $gender = $_POST['sreg_gender'];
    $country = $_POST['sreg_country'];
    $language = $_POST['sreg_language'];
    $timezone = $_POST['sreg_timezone'];
    $blob = $_POST['blob'];
    echo '<pre>';
    echo 'Hello, ' . htmlspecialchars($nickname) . "!\n";
    echo $nickname . "\n";
    echo $email . "\n";
    echo $fullname . "\n";
    echo $postal . "\n";
    echo $dob . "\n";
    echo $gender . "\n";
    echo $country . "\n";
    echo $language . "\n";
    echo $timezone . "\n";
    echo $blob . "\n";
    echo '</pre>';
    if (($handle = fopen("opx-users.csv", "a")) !== FALSE) {
       $entry = array();
       //username,password,sreg_nickname,sreg_email,sreg_fullname,sreg_postcode,sreg_dob,sreg_gender,sreg_country,sreg_language,sreg_timezone,blob
      $entry['username'] = $username;
      $entry['password'] = $password;
      $entry['sreg_nickname'] = $nickname;
      $entry['sreg_email'] = $email;
      $entry['sreg_fullname'] = $fullname;
      $entry['sreg_postcode'] = $postal;
      $entry['sreg_dob'] = $dob;
      $entry['sreg_gender'] = $gender;
      $entry['sreg_country'] = $country;
      $entry['sreg_language'] = $language;
      $entry['sreg_timezone'] = $timezone;
      $entry['blob'] = $blob;
      fputcsv($handle, $entry);
      fclose($handle);
    } else {
      echo 'ERROR';
    }
  } // taken

} else {
    header('Location: register.html', true, 302);
}
?>

<html>
<head>
<link href="http://cdn.quilt.janrain.com/2.1.8/quilt.css" media="all" rel="stylesheet" type="text/css" />
<title>Login</title>
</head>
<body>
 <div class="grid-block"> <div class="col3 center_col">
  <h1>Login</h1>
<?php
$opxApiKey = 'opx key';
$postBackURL = 'https://opxdemo.janrainfederate.com/ows_auth';
$returnToURL = 'https://opxdemo.janrainfederate.com/ows_return';

function loadUser($username) {
  $users = array();
  if (($handle = fopen("opx-users.csv", "r")) !== FALSE) {
    $current_entry = array();
    $header = fgetcsv($handle, 1024);
    while (($data = fgetcsv($handle, 1024)) !== FALSE) {
      $num_fields = count($data);
      for($i =0; $i<$num_fields; $i++) {
        $entry[$header[$i]] = $data[$i];
      }
      $users[$entry['username']] = $entry;
    }
    fclose($handle);
  }
  $entry = array('username' => 'guest');
  if (array_key_exists($username, $users)) {
   $entry = $users[$username];
  }
  return $entry;
}
$username = $_GET['username'];
$request_id = $_GET['request_id'];
if (strlen($username) > 0) {
 $user_data = loadUser($username);
 $post_data = http_build_query(array_merge($user_data, array('request_id' => $request_id,
                     'api_key' => $opxApiKey,
                     'no_prompt' => 'true')));
  
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_URL, $postBackURL);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($curl, CURLOPT_HEADER, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($curl, CURLOPT_FAILONERROR, true);
  $result = curl_exec($curl);
  if ($result == false) {
    $now = date(DateTime::ISO8601);
    printf("\nThat's an error. username: %s request id: %s %s %s at %s\n", $username, $request_id, curl_getinfo($curl, CURLINFO_HTTP_CODE), curl_error($curl), $now);
  } else {
    header('Location: ' . $returnToURL . '?request_id=' . $request_id, true, 302);
    curl_close($curl);
    exit;
  }
  curl_close($curl);
}
?>
  <form action="" method="get">
    <table>
       <tr><td align="right">Username:</td><td><input type="text" name="username" size="30" value=""/></td></tr>
       <tr><td align="right"><input type="submit" value="Login"/></td></tr>
     </table>
     <input type="hidden" name="request_id" value="<?php echo $request_id; ?>"/>
  </form>
</div></div>
</body>
</html>

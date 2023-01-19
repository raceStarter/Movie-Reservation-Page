<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$id = test_input($_POST["name"]);
$pw = test_input($_POST["password"]);

if (file_exists("data/person.json") && filesize("data/person.json") != 0) {
  $pefile = fopen("data/person.json", "r");

  while (!feof($pefile)) {
    $person_data = json_decode(fgets($pefile), true);
    $user_id = $person_data["Name"];

    if ($user_id == $id) {
      echo "fail";
      fclose($pefile);
      exit;
    }
  }

  fclose($pefile);
}


$loginData = (object) array (
  'Name' => $id,
  'Password' => $pw 
);

$pfile = fopen("data/person.json", "a");

if (filesize("data/person.json") == 0) {

  fwrite($pfile, json_encode($loginData, JSON_UNESCAPED_UNICODE));
  fclose($pfile);
} else {

  fwrite($pfile, "\n");
  fwrite($pfile, json_encode($loginData, JSON_UNESCAPED_UNICODE));
  fclose($pfile);
}

?>
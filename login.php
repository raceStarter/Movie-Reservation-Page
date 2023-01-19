<?php
session_start();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$id = test_input($_POST["name"]);
$pw = test_input($_POST["password"]);

if (!file_exists("data/person.json") || filesize("data/person.json") == 0) {
  echo "fail";
  exit;
}

$pfile = fopen("data/person.json", "r");

while (!feof($pfile)) {
  $personObj = json_decode(fgets($pfile));
  $getId = $personObj->Name;
  $getPw = $personObj->Password;

  if ($getId == $id && $getPw == $pw) {
    $_SESSION["user_id"] = $id;
    echo $id;
    fclose($pfile);
    exit;
  }
}

echo "fail";

fclose($pfile);

?>
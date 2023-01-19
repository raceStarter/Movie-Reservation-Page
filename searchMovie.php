<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$retArr = array();
$input_value = test_input($_POST["input_value"]);

if (!file_exists("data/movie.json") || filesize("data/movie.json") == 0) {
  echo "no data";
  exit;
}

$mfile = fopen("data/movie.json", "r");

while (!feof($mfile)) {
  $movie_data = json_decode(fgets($mfile), true);
  $actors = $movie_data["actors"];

  if (strpos($movie_data["movie_name"], $input_value) !== false ||
  strpos($movie_data["director"], $input_value) !== false) {
    array_push($retArr, $movie_data);
  } else {
    for ($i = 0; $i < count($actors); $i++) {
      if (strpos($actors[$i], $input_value) !== false) {
        array_push($retArr, $movie_data);
        break;
      }
    }
  }
}

echo json_encode($retArr,JSON_UNESCAPED_UNICODE);

fclose($mfile);

?>
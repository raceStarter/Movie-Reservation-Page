<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$retArr = array();
$movie_id = test_input($_POST["movie_id"]);

$sfile = fopen("data/screening.json", "r");

if (filesize("data/screening.json") == 0) {
  echo "no data";
  exit;
}

while (!feof($sfile)) {
  $screening_data = json_decode(fgets($sfile), true);
  $m_id = $screening_data["movie_id"];

  if ($m_id == $movie_id) {
    array_push($retArr, $screening_data);
  }
}

echo json_encode($retArr,JSON_UNESCAPED_UNICODE);

fclose($sfile);

?>
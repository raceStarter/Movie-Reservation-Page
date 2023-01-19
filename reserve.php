<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$temp = array();
$s_id = test_input($_POST["selected_screen"]);
$num = test_input($_POST["reserved_num"]);

if (!file_exists("data/screening.json") || filesize("data/screening.json") == 0) {
  echo "no data";
  exit;
}

$sfile = fopen("data/screening.json", "r");

while (!feof($sfile)) {
  $screening_data = json_decode(fgets($sfile), true);
  array_push($temp, $screening_data);
}

fclose($sfile);

for ($i = 0; $i < count($temp); $i++) {
  if ($temp[$i]["id"] == $s_id) {
    if ($temp[$i]["reserve_seat"] + $num > 20) {
      echo "full";
      exit;
    } else {
      $temp[$i]["reserve_seat"] += $num;
      break;
    }
  }
}

$s_file = fopen("data/screening.json", "w");

for ($i = 0; $i < count($temp); $i++) {
  if ($i == 0) {
    fwrite($s_file, json_encode($temp[$i], JSON_UNESCAPED_UNICODE));
  } else {
    fwrite($s_file, "\n");
    fwrite($s_file, json_encode($temp[$i], JSON_UNESCAPED_UNICODE));
  }
  
}

fclose($s_file);


$member_id = test_input($_POST["member_id"]);
$movie_id = test_input($_POST["selected_movie"]);
$file_name = "data/".$member_id.".json";

$rfile = fopen($file_name, "a");

$u_id = "u".count(file($file_name));
$reserve_data = (object) array (
  "id" => $u_id,
  "movie_id" => $movie_id,
  "s_id" => $s_id,
  "reserve_num" => $num
);

if (filesize($file_name) == 0) {

  fwrite($rfile, json_encode($reserve_data, JSON_UNESCAPED_UNICODE));
  fclose($rfile);
} else {

  fwrite($rfile, "\n");
  fwrite($rfile, json_encode($reserve_data, JSON_UNESCAPED_UNICODE));
  fclose($rfile);
}

echo "success";

?>
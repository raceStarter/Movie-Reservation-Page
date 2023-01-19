<?php

$showDate = $_POST["date"];
$title = $_POST["title"];
$registered = false;

if (!file_exists("data/movie.json") || filesize("data/movie.json") == 0) {
  echo "";
  exit;
}

$mfile = fopen("data/movie.json", "r");

while (!feof($mfile)) {
  $dataobj = json_decode(fgets($mfile));
  $movie_name = $dataobj->movie_name;
  
  if ($movie_name == $title) {
    $registered = true;
    break;
  }
}
fclose($mfile);

$dfile = fopen("data/screening.json", "r");

while (!feof($dfile)) {
  $dataobj = json_decode(fgets($dfile));
  $date = $dataobj->date;
  $place = $dataobj->screening_id;
  
  if ($date == $showDate) {
    if ($registered) {
      echo "already added";
      break;
    }
    echo $place."|";
  }
}

fclose($dfile);

?>
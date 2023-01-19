<?php
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// 영화 등록에 필요한 최소한의 정보 요구(제목, 상영 정보, 포스터)
$movie_name = test_input($_POST["title"]);
if ($movie_name == "") {
  echo "영화 제목이 입력되지 않았습니다. 영화 저장에 실패했습니다.";
  exit;
}

$showInfos = array();
if (array_key_exists("showInfo0", $_POST)) {
  array_push($showInfos, explode(",", test_input($_POST["showInfo0"])));
} else {
  echo "상영 정보가 등록되지 않았습니다. 영화 저장에 실패했습니다.";
  exit;
}

$target_dir = "uploads/";
$target_file = $target_dir.basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $file_name = $_FILES["fileToUpload"]["tmp_name"];
  if ($file_name == "") {
    echo " 이미지가 첨부되지 않았거나, 첨부된 이미지의 크기가 2MB를 초과하여 업로드 할 수 없습니다.";
    exit;
  } else {
    $check = getimagesize($file_name);
    if($check !== false) {
      $uploadOk = 1;
    } else {
      echo "이미지 파일이 아닙니다.";
      $uploadOk = 0;
    }
  }
}

// Check if file already exists
if (file_exists($target_file)) {
  echo " 해당 이미지가 이미 업로드 되어 있습니다.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  echo " JPG, JPEG, PNG, GIF 파일만 업로드 할 수 있습니다.";
  $uploadOk = 0;
}

if ($uploadOk == 0) {
  echo "<br>"."영화 저장에 실패했습니다.";
  exit;
// if everything is ok, try to upload file
} else {
  if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "업로드 중 오류가 발생했습니다.";
    exit;
  }
}


$genre = test_input($_POST["genre"]);
$director = test_input($_POST["director"]);
$actors = array();
$file_name = test_input($_FILES["fileToUpload"]["name"]);

// 배우 정보 등록
array_push($actors, test_input($_POST["actor0"]));

if (array_key_exists("actor1", $_POST)) {
  array_push($actors, test_input($_POST["actor1"]));
}

if (array_key_exists("actor2", $_POST)) {
  array_push($actors, test_input($_POST["actor2"]));
}

// 다른 상영 정보가 있을 경우 추가 등록
if (array_key_exists("showInfo1", $_POST)) {
  array_push($showInfos, explode(",", test_input($_POST["showInfo1"])));
}

if (array_key_exists("showInfo2", $_POST)) {
  array_push($showInfos, explode(",", test_input($_POST["showInfo2"])));
}



$movie_file = fopen("data/movie.json", "a");

$movie_id = "m".count(file("data/movie.json"));
$movie_data = (object) array(
  "id" => $movie_id,
  "movie_name" => $movie_name,
  "genre" => $genre,
  "director" => $director,
  "actors" => $actors,
  "file_name" => $file_name
);

if (filesize("data/movie.json") == 0) {

  fwrite($movie_file, json_encode($movie_data, JSON_UNESCAPED_UNICODE));
  fclose($movie_file);
} else {

  fwrite($movie_file, "\n");
  fwrite($movie_file, json_encode($movie_data, JSON_UNESCAPED_UNICODE));
  fclose($movie_file);
}

$screening_file = fopen("data/screening.json", "a");

for ($i = 0; $i < count($showInfos); $i++) {
  $screening_data = (object) array (
    "id" => "r".count(file("data/screening.json")),
    "date" => $showInfos[$i][0],
    "movie_id" => $movie_id,
    "screening_id" => $showInfos[$i][1],
    "reserve_seat" => 0
  );

  if (filesize("data/screening.json") == 0 && $i == 0) {
    fwrite($screening_file, json_encode($screening_data, JSON_UNESCAPED_UNICODE));
  } else {
    fwrite($screening_file, "\n");
    fwrite($screening_file, json_encode($screening_data, JSON_UNESCAPED_UNICODE));
  }
}

fclose($screening_file);

echo "저장되었습니다."

?>
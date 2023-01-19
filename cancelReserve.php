<?php
session_start();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

$cancelList = json_decode($_POST["cancelList"], true);
$user = $_SESSION["user_id"];
$file_name = "data/".$user.".json";

$keepitem = array(); // 유저 정보에 남길 항목들
$canceled_s_id = array(); // 삭제된 내역의 screening id 목록
$canceled_seats = array(); // 취소된 좌석 수 목록
$screening_data_list = array();

$ufile = fopen($file_name, "r");

while (!feof($ufile)) {
  $res_data = json_decode(fgets($ufile), true);
  $u_id = $res_data["id"];
  $canceled_num = $res_data["reserve_num"];

  if (in_array($u_id, $cancelList)) {
    array_push($canceled_s_id, $res_data["s_id"]);
    array_push($canceled_seats, $canceled_num);
  } else {
    array_push($keepitem, json_encode($res_data, JSON_UNESCAPED_UNICODE));
  }
}

fclose($ufile);

$ufile2 = fopen($file_name, "w");

for ($i = 0; $i < count($keepitem); $i++) {
  if ($i == 0) {
    fwrite($ufile2, $keepitem[$i]);
  } else {
    fwrite($ufile2, "\n");
    fwrite($ufile2, $keepitem[$i]);
  }
  
}

fclose($ufile2);


$sfile = fopen("data/screening.json", "r");

while (!feof($sfile)) {
  $line_data = json_decode(fgets($sfile), true);
  $r_id = $line_data["id"];

  if (in_array($r_id, $canceled_s_id)) {
    $changed_data = (object) array (
      "id" => $line_data["id"],
      "date" => $line_data["date"],
      "movie_id" => $line_data["movie_id"],
      "screening_id" => $line_data["screening_id"],
      "reserve_seat" => ($line_data["reserve_seat"] - $canceled_seats[array_search($r_id, $canceled_s_id)])
    );
    array_push($screening_data_list, json_encode($changed_data, JSON_UNESCAPED_UNICODE));
  } else {
    array_push($screening_data_list, json_encode($line_data, JSON_UNESCAPED_UNICODE));
  }
}

fclose($sfile);

$sfile2 = fopen("data/screening.json", "w");

for ($i = 0; $i < count($screening_data_list); $i++) {
  if ($i == 0) {
    fwrite($sfile2, $screening_data_list[$i]);
  }
  else {
    fwrite($sfile2, "\n");
    fwrite($sfile2, $screening_data_list[$i]);
  }
}

echo 'success';

fclose($sfile2);

?>
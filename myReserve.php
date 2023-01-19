<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>예약정보</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="reserve_info_style.css" />
</head>

<body>
  <?php
  if (!isset($_SESSION["user_id"])) {
    echo "<script>alert('로그인 후 예약 정보 보기가 가능합니다.');</script>";
  } else {
    echo "<p id='user'>"."<span>".$_SESSION["user_id"]."</span>"." 회원"."</p>";
    echo "<br>";

    echo "<div id='table_box'><table id='list_table'>";
    echo "<tr><th>체크</th><th>예약 번호</th><th>영화 제목</th><th>상영 날짜</th><th>상영 장소</th>
          <th>예매 수</th></tr>";

    $file_name = "data/".$_SESSION["user_id"].".json";

    if (!file_exists($file_name) || filesize($file_name) == 0) {
      echo "<td colspan = 6><p class='notice'>예약된 내역이 없습니다.</p></td></table>";
      exit;
    }

    $resfile = fopen($file_name, "r");

    while (!feof($resfile)) {
      $u_data = json_decode(fgets($resfile), true);

      foreach ($u_data as $key => $value) {
        
        switch ($key) {
          case "id":
            echo "<tr><td><input type='checkbox'></td>";
            echo "<td>".$u_data[$key]."</td>";
            break;
          case "movie_id":
            $mfile = fopen("data/movie.json", "r");

            while (!feof($mfile)) {
              $line = json_decode(fgets($mfile), true);
              
              if ($line["id"] == $u_data[$key]) {
                echo "<td>".$line["movie_name"]."</td>";
                break;
              }
            }
            fclose($mfile);
            break;
          case "s_id":
            $sfile = fopen("data/screening.json", "r");

            while (!feof($sfile)) {
              $line = json_decode(fgets($sfile), true);
              
              if ($line["id"] == $u_data[$key]) {
                echo "<td>".$line["date"]."</td>";
                echo "<td>".$line["screening_id"]."</td>";
                break;
              }
            }
            fclose($sfile);
            break;
          case "reserve_num":
            echo "<td>".$u_data[$key]."</td></tr>";
            break;
        }
      }
    }

    echo "</table></div>";
    echo "<br><br>";
    echo "<button id='cancel_btn'>취소하기</button>";
  }
    

  ?>
  <script src="myReserveapp.js"></script>
</body>

</html>
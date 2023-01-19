<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  echo "none";
} else {
  echo $_SESSION["user_id"];
}
?>
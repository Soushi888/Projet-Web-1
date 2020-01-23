<?php

session_start();

if (empty($_SESSION)) {
    header("Location: login.php");
} else {
    header("Location: commandes/index.php");
}

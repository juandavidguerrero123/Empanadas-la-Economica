<?php

session_start();
$_SESSION = [];
session_destroy();

header("Location: ../view/html/index.html");
exit;
<?php
$url = '../Admin/Login/index';
Header("HTTP/1.1 303 See Other");
Header("Location: $url");
exit;
?>
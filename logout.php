<?php

setcookie(session_name(), '', time() - 1, '/');
session_unset();
session_destroy();
header('Location: index.php');


<?php

session_start();
session_unset();//frees all session variables currently registered
session_destroy();
header("Location: ../index.php");
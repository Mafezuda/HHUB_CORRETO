<?php
session_start();
session_destroy();
header("Location: /healthhub/public/index.php");

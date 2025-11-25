<?php
session_start();
session_unset();    // hapus semua isi session
session_destroy();  // hancurkan session

header("Location: /simaksi/auth/login.php");
exit;

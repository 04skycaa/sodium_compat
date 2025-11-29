<?php
session_start();

    $_SESSION['status_sukses'] = 'Data berhasil disimpan dan dicatat!';
    header('Location: pembukuan.php');
    exit(); 
// }

?>
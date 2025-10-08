<?php
include __DIR__ . '/../../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM pengguna WHERE id_pengguna = $id";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>

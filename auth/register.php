<?php
session_start();
include '../config/database.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $peran = 'pendaki'; 
    $dibuat_pada = date('Y-m-d H:i:s');

    $sql_check = "SELECT email FROM pengguna WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $_SESSION['register_status'] = 'error';
        $_SESSION['register_message'] = "Email sudah terdaftar! Silakan gunakan email lain.";
        header('Location: ../auth/register.php');
        exit;
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO pengguna (nama_lengkap, email, kata_sandi_hash, peran, dibuat_pada) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama_lengkap, $email, $hashed_password, $peran, $dibuat_pada);

        if ($stmt->execute()) {
            $_SESSION['register_status'] = 'success';
            $_SESSION['register_message'] = "Pendaftaran berhasil! Silakan login.";
            header('Location: ../auth/login.php');
            exit;
        } else {
            $_SESSION['register_status'] = 'error';
            $_SESSION['register_message'] = "Terjadi kesalahan: " . $stmt->error;
        }

        $stmt->close();
        header('Location: ../auth/register.php'); 
        exit;
    }

    $stmt_check->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-SIMAKSI - Register</title>
    <link rel="stylesheet" href="../assets/css/auth.css">

</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1>SELAMAT DATANG DI <br> GUNUNG BUTAK</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae eum optio debitis fugiat ad, suscipit tenetur totam labore possimus beatae itaque accusantium soluta libero quos recusandae obcaecati voluptatum temporibus enim?</p>
        </div>

        <div class="right-section">
            <div class="register-box">
                <div class="logo">
                    <img src="../assets/images/logo1.png" alt="E-SIMAKSI Logo">
                </div>
                <h2>REGISTER</h2>
                <p>Buat akun baru untuk melanjutkan cerita pendakianmu</p>

                <form action="register.php" method="POST">
                    <div class="input-group floating-label">
                        <input type="text" name="username" id="username" required placeholder=" ">
                        <label for="username">Username</label>
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="6" r="4" fill="currentColor"/><path fill="currentColor" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/></svg>
                    </div>

                    <div class="input-group floating-label">
                        <input type="email" name="email" id="email" required placeholder=" ">
                        <label for="email">Email</label>
                        <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2m0 4l-8 5l-8-5V6l8 5l8-5v2z"/></svg>
                    </div>

                    <div class="input-group floating-label">
                        <input type="password" id="password" name="password" placeholder=" " autocomplete="new-password" required>
                        <label for="password">Password</label>
                        <span class="input-icon toggle-password" tabindex="0" role="button" aria-label="Toggle password visibility">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5c-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5M3.18 12a9.821 9.821 0 0 0 17.64 0a9.821 9.821 0 0 0-17.64 0"/></svg>
                            <svg id="eye-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2 5.27L3.28 4L20 20.72L18.73 22l-3.08-3.08c-1.15.38-2.37.58-3.65.58c-5 0-9.27-3.11-11-7.5c.69-1.76 1.79-3.31 3.19-4.54zM12 9a3 3 0 0 1 3 3a3 3 0 0 1-.17 1L11 9.17A3 3 0 0 1 12 9m0-4.5c5 0 9.27 3.11 11 7.5a11.8 11.8 0 0 1-4 5.19l-1.42-1.43A9.86 9.86 0 0 0 20.82 12A9.82 9.82 0 0 0 12 6.5c-1.09 0-2.16.18-3.16.5L7.3 5.47c1.44-.62 3.03-.97 4.7-.97M3.18 12A9.82 9.82 0 0 0 12 17.5c.69 0 1.37-.07 2-.21L11.72 15A3.064 3.064 0 0 1 9 12.28L5.6 8.87c-.99.85-1.82 1.91-2.42 3.13"/></svg>    
                        </span>
                    </div>

                    <button type="submit" class="register-btn">Register</button>
                </form>

                <p class="login-link">Sudah punya akun? <a href="../auth/login.php">login</a></p>
            </div>
        </div>
    </div>
    
   
    <div id="status-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modal-title"></h2>
        <p id="modal-message"></p>
    </div>
    </div>

<script>
    const loginStatus = "<?php echo isset($_SESSION['register_status']) ? $_SESSION['register_status'] : ''; ?>";
    const loginMessage = "<?php echo isset($_SESSION['register_message']) ? $_SESSION['register_message'] : ''; ?>";

    if (loginStatus && loginMessage) {
        alert(loginMessage); 
    }
</script>
<?php
unset($_SESSION['register_status']);
unset($_SESSION['register_message']);
?>

<script src="../assets/js/auth.js"></script>

</body>
</html>
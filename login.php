<?php
// Menyertakan file Database.php untuk koneksi database
require_once 'Database.php';

// Memulai session untuk menyimpan informasi login pengguna
session_start();

// Mengecek apakah request menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil input dari formulir dengan menggunakan metode POST dan membersihkan whitespace
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi: memastikan semua field diisi
    if (empty($username) || empty($email) || empty($password)) {
        die('Semua field harus diisi!');
    }

    // Membuat instance dari kelas Database
    $db = new Database();

    try {
        // Mendapatkan koneksi database
        $conn = $db->getConnection();

        // Query untuk mencari pengguna berdasarkan username atau email
        $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);

        // Mengecek jika statement gagal disiapkan
        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Menghubungkan parameter input dengan statement
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();

        // Menghubungkan hasil query dengan variabel
        $stmt->bind_result($userId, $fetchedUsername, $hashedPassword);

        // Mengecek apakah ada data yang cocok
        if ($stmt->fetch()) {
            // Verifikasi password menggunakan fungsi password_verify
            if (password_verify($password, $hashedPassword)) {
                // Jika password cocok, simpan informasi pengguna ke session
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $fetchedUsername;

                // Menyimpan username ke cookie (berlaku selama 30 hari)
                setcookie('username', $fetchedUsername, time() + (86400 * 30), "/");

                // Redirect ke halaman atlet.php jika login berhasil
                header('Location: atlet.php');
                exit();
            } else {
                // Jika password salah, tampilkan pesan
                echo 'Password salah.';
            }
        } else {
            // Jika username atau email tidak ditemukan
            echo 'Username atau Email tidak ditemukan.';
        }

        // Menutup statement
        $stmt->close();
    } catch (Exception $e) {
        // Menangkap dan menampilkan error jika terjadi kesalahan
        die('Terjadi kesalahan: ' . $e->getMessage());
    } finally {
        // Menutup koneksi database di blok finally
        $db->close();
    }
} else {
    // Jika metode request tidak menggunakan POST, tampilkan pesan error
    die('Metode tidak diizinkan.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script>
        // Fungsi untuk menyimpan username ke localStorage
        function saveToLocalStorage(username) {
            localStorage.setItem('username', username);
        }

        // Fungsi untuk mengambil username dari localStorage
        function getFromLocalStorage() {
            return localStorage.getItem('username');
        }

        // Menampilkan pesan selamat datang jika username ada di localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const username = getFromLocalStorage();
            if (username) {
                document.getElementById('welcomeMessage').innerText = `Selamat datang kembali, ${username}!`;
            }
        });
    </script>
</head>
<body>
    <!-- Elemen untuk menampilkan pesan selamat datang -->
    <div id="welcomeMessage"></div>

    <!-- Formulir login -->
    <form method="POST" action="">
        <!-- Input untuk username -->
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <!-- Input untuk email -->
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <!-- Input untuk password -->
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <!-- Tombol login dengan onclick untuk menyimpan username ke localStorage -->
        <button type="submit" onclick="saveToLocalStorage(document.getElementById('username').value)">
            Login
        </button>
    </form>
</body>
</html>

<?php
// Mengimpor file database.php untuk menggunakan kelas Database
require_once 'database.php';

// Mengecek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form
    $username = $_POST['username'] ?? ''; // Username yang diinput oleh pengguna
    $email = $_POST['email'] ?? '';       // Email yang diinput oleh pengguna
    $password = $_POST['password'] ?? ''; // Password yang diinput oleh pengguna
    $gender = $_POST['gender'] ?? '';     // Gender yang diinput oleh pengguna
    $country = $_POST['country'] ?? '';   // Country yang diinput oleh pengguna

    // Validasi: Mengecek apakah semua field telah diisi
    if (empty($username) || empty($email) || empty($password) || empty($gender) || empty($country)) {
        die('Semua field harus diisi!'); // Menghentikan eksekusi jika ada field yang kosong
    }

    // Validasi: Mengecek format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Format email tidak valid!'); // Menghentikan eksekusi jika email tidak valid
    }

    // Hash password: Menggunakan algoritma bcrypt untuk keamanan
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Menyimpan data ke database
    try {
        $db = new Database(); // Membuat instance dari kelas Database

        // Query SQL untuk menyimpan data pengguna ke tabel `users`
        $sql = "INSERT INTO users (username, email, password, gender, country) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepareStatement($sql); // Menyiapkan statement menggunakan prepared statement

        // Mengikat parameter ke dalam query untuk mencegah SQL Injection
        $stmt->bind_param('sssss', $username, $email, $hashedPassword, $gender, $country);

        // Mengeksekusi statement
        if ($stmt->execute()) {
            // Jika berhasil, tampilkan pesan sukses
            echo 'Pendaftaran berhasil. <a href="login.html">Klik di sini untuk login</a>.';
        } else {
            // Jika gagal, tampilkan pesan error
            echo 'Gagal mendaftar: ' . $stmt->error;
        }

        $stmt->close(); // Menutup statement untuk membebaskan sumber daya
        $db->close();   // Menutup koneksi database
    } catch (Exception $e) {
        // Menangkap error dan menampilkan pesan
        die('Error: ' . $e->getMessage());
    }
} else {
    // Jika metode request bukan POST, hentikan eksekusi
    die('Metode tidak diizinkan.');
}
?>

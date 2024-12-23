<?php
// Memasukkan file 'atlet.php' yang berisi class dan metode untuk mengelola data atlet
require_once 'atlet.php';

// Mengecek apakah permintaan yang diterima adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // **Validasi dan Sanitasi Input**
    // Membersihkan input 'name' untuk menghindari karakter berbahaya
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    
    // Memvalidasi input 'speed' agar berupa angka dengan nilai antara 0 dan 100
    $speed = filter_input(INPUT_POST, 'speed', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    
    // Memvalidasi input 'technical' agar berupa angka dengan nilai antara 0 dan 100
    $technical = filter_input(INPUT_POST, 'technical', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    
    // Memvalidasi input 'intelligence' agar berupa angka dengan nilai antara 0 dan 100
    $intelligence = filter_input(INPUT_POST, 'intelligence', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    
    // Memvalidasi input 'shooting' agar berupa angka dengan nilai antara 0 dan 100
    $shooting = filter_input(INPUT_POST, 'shooting', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    
    // Memvalidasi input 'passing' agar berupa angka dengan nilai antara 0 dan 100
    $passing = filter_input(INPUT_POST, 'passing', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);
    
    // Memvalidasi input 'defending' agar berupa angka dengan nilai antara 0 dan 100
    $defending = filter_input(INPUT_POST, 'defending', FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => 100]]);

    // Mengecek apakah semua input telah terisi dengan benar
    if ($name && $speed !== false && $technical !== false && $intelligence !== false && $shooting !== false && $passing !== false && $defending !== false) {
        // Membuat instance dari kelas 'Atlet'
        $atlet = new Atlet();

        // Menambahkan data atlet menggunakan metode 'addAtlet' dari kelas 'Atlet'
        if ($atlet->addAtlet($name, $speed, $technical, $intelligence, $shooting, $passing, $defending)) {
            // Jika berhasil, redirect ke halaman 'atlet.html'
            header("Location: atlet.html");
            exit(); // Menghentikan eksekusi script setelah redirect
        } else {
            // Menampilkan pesan error jika data gagal ditambahkan
            echo "Gagal menambahkan data atlet.";
        }
    } else {
        // Menampilkan pesan error jika data yang dimasukkan tidak valid
        die("Data yang dimasukkan tidak valid!");
    }
} else {
    // Menampilkan pesan error jika akses dilakukan dengan metode selain POST
    die("Akses tidak valid.");
}
?>

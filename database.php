<?php
// Definisi kelas Database untuk mengelola koneksi dan operasi database
class Database {
    // Properti untuk menyimpan detail koneksi database
    private $servername = "localhost"; // Nama server database
    private $username = "root";        // Username database
    private $password = "";            // Password database
    private $dbname = "atlet_db";      // Nama database
    private $conn;                     // Properti untuk menyimpan koneksi database

    // Konstruktor untuk membuat koneksi ke database
    public function __construct() {
        // Membuat objek koneksi MySQLi dengan parameter yang sudah didefinisikan
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Mengecek apakah koneksi berhasil atau gagal
        if ($this->conn->connect_error) {
            // Jika gagal, tampilkan pesan error dan hentikan eksekusi
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    // Fungsi untuk mendapatkan koneksi database (digunakan di luar kelas)
    public function getConnection() {
        return $this->conn; // Mengembalikan objek koneksi
    }

    // Fungsi untuk menjalankan query biasa (non-prepared statement)
    public function executeQuery($sql) {
        // Menjalankan query menggunakan metode query() dari objek koneksi
        $result = $this->conn->query($sql);

        // Jika query gagal, lemparkan exception dengan pesan error
        if ($result === false) {
            throw new Exception("Error executing query: " . $this->conn->error);
        }

        // Mengembalikan hasil query
        return $result;
    }

    // Fungsi untuk menyiapkan query dengan prepared statement
    public function prepareStatement($sql) {
        // Menyiapkan statement menggunakan metode prepare() dari objek koneksi
        $stmt = $this->conn->prepare($sql);

        // Jika statement gagal disiapkan, lemparkan exception dengan pesan error
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $this->conn->error);
        }

        // Mengembalikan objek statement yang sudah disiapkan
        return $stmt;
    }

    // Fungsi untuk menutup koneksi database
    public function close() {
        // Mengecek apakah koneksi masih aktif
        if ($this->conn) {
            // Menutup koneksi database
            $this->conn->close();

            // Mengatur properti koneksi menjadi null setelah ditutup
            $this->conn = null;
        }
    }

    // Destructor yang secara otomatis dipanggil ketika objek dihapus
    public function __destruct() {
        // Memastikan koneksi database ditutup ketika objek dihancurkan
        $this->close();
    }
}
?>

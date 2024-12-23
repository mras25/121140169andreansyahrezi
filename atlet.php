<?php
// Memulai sesi untuk melacak login pengguna
session_start();
if (!isset($_SESSION['user_id'])) {
    // Jika pengguna belum login, redirect ke halaman login
    header("Location: login.php");
    exit(); // Menghentikan eksekusi skrip
}

// Memasukkan file database untuk koneksi ke database
require_once 'database.php';

// Mendefinisikan kelas Atlet untuk mengelola data atlet
class Atlet {
    private $db;

    // Konstruktor: Membuat koneksi ke database
    public function __construct() {
        $this->db = new Database();
    }

    // Membuat tabel atlet jika belum ada
    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS atlet (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            speed INT NOT NULL CHECK(speed BETWEEN 0 AND 100),
            technical INT NOT NULL CHECK(technical BETWEEN 0 AND 100),
            intelligence INT NOT NULL CHECK(intelligence BETWEEN 0 AND 100),
            shooting INT NOT NULL CHECK(shooting BETWEEN 0 AND 100),
            passing INT NOT NULL CHECK(passing BETWEEN 0 AND 100),
            defending INT NOT NULL CHECK(defending BETWEEN 0 AND 100),
            browser VARCHAR(255),
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        try {
            $this->db->executeQuery($sql); // Menjalankan query
        } catch (Exception $e) {
            die("Gagal membuat tabel: " . $e->getMessage());
        }
    }

    // Menambahkan data atlet ke database
    public function addAtlet($name, $speed, $technical, $intelligence, $shooting, $passing, $defending) {
        $browser = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'; // Mendapatkan informasi browser
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown'; // Mendapatkan alamat IP pengguna

        $sql = "INSERT INTO atlet (name, speed, technical, intelligence, shooting, passing, defending, browser, ip_address) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bind_param("siiiiisss", $name, $speed, $technical, $intelligence, $shooting, $passing, $defending, $browser, $ip_address);
            return $stmt->execute(); // Menjalankan pernyataan
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Mendapatkan semua data atlet
    public function getAllAtlet() {
        $sql = "SELECT * FROM atlet ORDER BY created_at DESC";
        try {
            $result = $this->db->executeQuery($sql);
            return $result->fetch_all(MYSQLI_ASSOC); // Mengembalikan data sebagai array asosiatif
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Menghapus data atlet berdasarkan ID
    public function deleteAtlet($id) {
        $sql = "DELETE FROM atlet WHERE id = ?";
        try {
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Mengedit data atlet berdasarkan ID
    public function editAtlet($id, $name, $speed, $technical, $intelligence, $shooting, $passing, $defending) {
        $sql = "UPDATE atlet SET name = ?, speed = ?, technical = ?, intelligence = ?, shooting = ?, passing = ?, defending = ? WHERE id = ?";
        try {
            $stmt = $this->db->prepareStatement($sql);
            $stmt->bind_param("siiiiiii", $name, $speed, $technical, $intelligence, $shooting, $passing, $defending, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Destruktor: Menutup koneksi database
    public function __destruct() {
        $this->db->close();
    }
}

// Membuat instance objek Atlet
$atlet = new Atlet();

// Memproses data yang dikirimkan melalui form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        // Menambahkan data atlet
        $atlet->addAtlet($_POST['name'], $_POST['speed'], $_POST['technical'], $_POST['intelligence'], $_POST['shooting'], $_POST['passing'], $_POST['defending']);
    } elseif (isset($_POST['edit'])) {
        // Mengedit data atlet
        $atlet->editAtlet($_POST['id'], $_POST['name'], $_POST['speed'], $_POST['technical'], $_POST['intelligence'], $_POST['shooting'], $_POST['passing'], $_POST['defending']);
    }
}

// Memproses permintaan penghapusan data melalui GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $atlet->deleteAtlet($_GET['delete']);
}

// Mendapatkan semua data atlet untuk ditampilkan
$dataAtlet = $atlet->getAllAtlet();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Calon Atlet Sepak Bola</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50%;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 20px;
        }

        .modal.active {
            display: block;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Calon Atlet Sepak Bola</h1>
        <form action="atlet.php" method="POST">
            <label for="name">Nama:</label>
            <input type="text" id="name" name="name" required>
            <label for="speed">Speed:</label>
            <input type="number" id="speed" name="speed" required min="0" max="100">
            <label for="technical">Technical:</label>
            <input type="number" id="technical" name="technical" required min="0" max="100">
            <label for="intelligence">Intelligence:</label>
            <input type="number" id="intelligence" name="intelligence" required min="0" max="100">
            <label for="shooting">Shooting:</label>
            <input type="number" id="shooting" name="shooting" required min="0" max="100">
            <label for="passing">Passing:</label>
            <input type="number" id="passing" name="passing" required min="0" max="100">
            <label for="defending">Defending:</label>
            <input type="number" id="defending" name="defending" required min="0" max="100">
            <button type="submit" name="add">Tambah Atlet</button>
        </form>

        <h2>Daftar Atlet</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Speed</th>
                    <th>Technical</th>
                    <th>Intelligence</th>
                    <th>Shooting</th>
                    <th>Passing</th>
                    <th>Defending</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($dataAtlet)): ?>
                    <?php foreach ($dataAtlet as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['speed']) ?></td>
                            <td><?= htmlspecialchars($row['technical']) ?></td>
                            <td><?= htmlspecialchars($row['intelligence']) ?></td>
                            <td><?= htmlspecialchars($row['shooting']) ?></td>
                            <td><?= htmlspecialchars($row['passing']) ?></td>
                            <td><?= htmlspecialchars($row['defending']) ?></td>
                            <td>
                                <a href="atlet.php?delete=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                <button type="button" onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Tidak ada data atlet yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Edit -->
    <div class="overlay" id="overlay"></div>
    <div class="modal" id="editModal">
        <h2>Edit Data Atlet</h2>
        <form action="atlet.php" method="POST">
            <input type="hidden" id="edit-id" name="id">
            <label for="edit-name">Nama:</label>
            <input type="text" id="edit-name" name="name" required>
            <label for="edit-speed">Speed:</label>
            <input type="number" id="edit-speed" name="speed" required min="0" max="100">
            <label for="edit-technical">Technical:</label>
            <input type="number" id="edit-technical" name="technical" required min="0" max="100">
            <label for="edit-intelligence">Intelligence:</label>
            <input type="number" id="edit-intelligence" name="intelligence" required min="0" max="100">
            <label for="edit-shooting">Shooting:</label>
            <input type="number" id="edit-shooting" name="shooting" required min="0" max="100">
            <label for="edit-passing">Passing:</label>
            <input type="number" id="edit-passing" name="passing" required min="0" max="100">
            <label for="edit-defending">Defending:</label>
            <input type="number" id="edit-defending" name="defending" required min="0" max="100">
            <button type="submit" name="edit">Simpan Perubahan</button>
            <button type="button" onclick="closeEditModal()">Batal</button>
        </form>
    </div>

    <script>
        function openEditModal(data) {
            document.getElementById('edit-id').value = data.id;
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-speed').value = data.speed;
            document.getElementById('edit-technical').value = data.technical;
            document.getElementById('edit-intelligence').value = data.intelligence;
            document.getElementById('edit-shooting').value = data.shooting;
            document.getElementById('edit-passing').value = data.passing;
            document.getElementById('edit-defending').value = data.defending;
            document.getElementById('overlay').classList.add('active');
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('overlay').classList.remove('active');
            document.getElementById('editModal').classList.remove('active');
        }
    </script>
</body>
</html>

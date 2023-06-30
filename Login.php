<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

// Menerima data dari request
$data = json_decode(file_get_contents("php://input"));

// Menghubungkan ke database
$host = "localhost";
$dbname = "ticki_taka";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Memeriksa keberadaan pengguna dengan email dan password yang cocok
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Pengguna ditemukan, mengirim response status success
        $response = array("status" => "success");
        echo json_encode($response);
    } else {
        // Pengguna tidak ditemukan, mengirim response status error
        $response = array("status" => "error", "message" => "Invalid email or password");
        echo json_encode($response);
    }
} catch (PDOException $e) {
    // Jika terjadi error, mengirim response dengan pesan error
    $response = array("status" => "error", "message" => $e->getMessage());
    echo json_encode($response);
}

// Menutup koneksi
$conn = null;
?>

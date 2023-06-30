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

// Memasukkan data ke tabel user
try {
    $stmt = $conn->prepare("INSERT INTO users (user_name, email, password) VALUES (:user_name, :email, :password)");
    $stmt->bindParam(":user_name", $data->user_name);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
    $stmt->execute();

    // Mengirim response status success
    $response = array("status" => "success", "user_name" => $data->user_name);
    echo json_encode($response);
} catch (PDOException $e) {
    // Jika terjadi error, mengirim response dengan pesan error
    $response = array("status" => "error", "message" => $e->getMessage());
    echo json_encode($response);
}

// Menutup koneksi
$conn = null;
?>

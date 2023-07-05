<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

// Mendapatkan data dari request
$data = json_decode(file_get_contents("php://input"));

// Menghubungkan ke database
include "./connection.php";

// Memasukkan data ke tabel user
try {
    $stmt = $conn->prepare("INSERT INTO users (user_name, email, password) VALUES (:user_name, :email, :password)");
    $stmt->bindParam(":user_name", $data->user_name);
    $stmt->bindParam(":email", $data->email);
    $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
    $stmt->bindParam(":password", $hashedPassword);
    $stmt->execute();

    // Mengirim response status success
    $response = array("status" => "success");
    echo json_encode($response);
} catch (PDOException $e) {
    // Jika terjadi error, mengirim response dengan pesan error
    $response = array("status" => "error", "message" => $e->getMessage());
    echo json_encode($response);
}

// Menutup koneksi
$conn = null;
?>

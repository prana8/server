<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");

// Mendapatkan data dari request
$data = json_decode(file_get_contents("php://input"));

// Menghubungkan ke database
include "./connection.php";

// Memeriksa keberadaan pengguna dengan email yang cocok
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $data->email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Pengguna ditemukan, mengambil data pengguna dari hasil query
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Memeriksa kecocokan password
        if (password_verify($data->password, $user["password"])) {
            // Password cocok
            // Mengirim response status success beserta user_name
            $response = array("status" => "success", "user_name" => $user["user_name"], "userStatus" => $user['status'] );
            echo json_encode($response);
        } else {
            // Password tidak cocok
            // Mengirim response status error
            $response = array("status" => "error", "message" => "Invalid email or password");
            echo json_encode($response);
        }
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

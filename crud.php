<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");


include "./connection.php";

// Menggunakan method GET untuk mendapatkan data dari database
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Query untuk mendapatkan data dari tabel movies
        $sql = 'SELECT * FROM movies';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Memeriksa apakah terdapat data yang ditemukan
        if ($stmt->rowCount() > 0) {
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mengembalikan data dalam format JSON
            echo json_encode($movies);
        } else {
            // Jika tidak ada data yang ditemukan
            http_response_code(404);
            echo json_encode(array('message' => 'No movies found.'));
        }
    } catch (PDOException $e) {
        // Jika terjadi kesalahan dalam query
        http_response_code(500);
        echo json_encode(array('message' => 'Error: ' . $e->getMessage()));
    }
}

// Menggunakan method POST untuk menambahkan data ke database
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Menerima data dari request
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        // Query untuk menambahkan data ke tabel movies
        $stmt = $conn->prepare("INSERT INTO movies (movie_cover, tittle, rating, detail, realase_date) VALUES (:movie_cover, :tittle, :rating, :detail, :realase_date)");
        $stmt->bindParam(":movie_cover", $data['movie_cover']);
        $stmt->bindParam(":tittle", $data['tittle']);
        $stmt->bindParam(":rating", $data['rating']);
        $stmt->bindParam(":detail", $data['detail']);
        $stmt->bindParam(":realase_date", $data['realase_date']);
        $stmt->execute();

        $response = array("status" => "success");
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = array("status" => "error", "message" => $e->getMessage());
        echo json_encode($response);
    }
}

// Menggunakan method DELETE untuk menghapus data dari database
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Menerima data dari request
    $id = $_GET['id'];

    try {
        // Query untuk menghapus data dari tabel movies berdasarkan id
        $sql = 'DELETE FROM movies WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Jika data berhasil dihapus
        http_response_code(200);
        echo json_encode(array('message' => 'Movie deleted successfully.'));
    } catch (PDOException $e) {
        // Jika terjadi kesalahan dalam query
        http_response_code(500);
        echo json_encode(array('message' => 'Error deleting movie: ' . $e->getMessage()));
    }
}
// Menggunakan method PUT untuk memperbarui data di database
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Menerima data dari request
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        $stmt = $conn->prepare("UPDATE movies (movie_cover, tittle, rating, detail, realase_date) VALUES (:movie_cover, :tittle, :rating, :detail, :realase_date) WHERE id =:id");
        $stmt->bindParam(":movie_cover", $data['movie_cover']);
        $stmt->bindParam(":tittle", $data['tittle']);
        $stmt->bindParam(":rating", $data['rating']);
        $stmt->bindParam(":detail", $data['detail']);
        $stmt->bindParam(":realase_date", $data['realase_date']);
        $stmt->execute();

        // Jika data berhasil diperbarui
        http_response_code(200);
        echo json_encode(array('message' => 'Movie updated successfully.'));
    } catch (PDOException $e) {
        // Jika terjadi kesalahan dalam query
        http_response_code(500);
        echo json_encode(array('message' => 'Error updating movie: ' . $e->getMessage()));
    }
}
?>

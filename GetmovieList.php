<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include "./connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {

        $sql = 'SELECT * FROM movies';
        $stmt = $conn->prepare($sql);
        $stmt->execute();


        if ($stmt->rowCount() > 0) {
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($movies);
        } else {

            http_response_code(404);
            echo json_encode(array('message' => 'No movies found.'));
        }
    } catch (PDOException $e) {
        // Jika terjadi kesalahan dalam query
        http_response_code(500);
        echo json_encode(array('message' => 'Error: ' . $e->getMessage()));
    }
}

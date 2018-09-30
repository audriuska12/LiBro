<?php
$access = $_SERVER["REQUEST_METHOD"];

if ($access) {
    switch ($access) {
        case 'GET':
            {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if (filter_var($id, FILTER_VALIDATE_INT)) {
                        include 'connection.php';
                        $query = "SELECT series.id, series.name, series.description, authors.id as authorID, authors.name as authorName FROM series LEFT JOIN authors ON series.author = authors.id WHERE series.id = ?";
                        if (! ($sql = mysqli_prepare($conn, $query))) {
                            http_response_code(503);
                            die();
                        }
                        if (! ($sql->bind_param('i', $id))) {
                            http_response_code(503);
                            die();
                        }
                        if (! ($sql->execute())) {
                            http_response_code(503);
                            die();
                        }
                        if (! ($sql->bind_result($seriesID, $name, $description, $authorID, $authorName))) {
                            http_response_code(503);
                            die();
                        }
                        if ($sql->fetch()) {
                            $data = [
                                "id" => $seriesID,
                                "name" => $name,
                                "description" => $description,
                                "authorID" => $authorID,
                                "authorName" => $authorName
                            ];
                            http_response_code(200);
                        } else {
                            http_response_code(404);
                            die();
                        }
                        $conn->close();
                    } else {
                        http_response_code(400);
                        die();
                    }
                } else {
                    include 'connection.php';
                    if (isset($_GET['author'])) {
                        $author = $_GET['author'];
                        if (! filter_var($author, FILTER_VALIDATE_INT)) {
                            http_response_code(400);
                            $conn->close();
                            die();
                        }
                        $query = "SELECT series.id, series.name, series.description, authors.id as authorID, authors.name as authorName FROM series LEFT JOIN authors ON series.author = authors.id where authors.id = ?";
                        $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                        $sql->bind_param('i', $author);
                    } else {
                        $query = "SELECT series.id, series.name, series.description, authors.id as authorID, authors.name as authorName FROM series LEFT JOIN authors ON series.author = authors.id";
                        $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                    }
                    $sql->execute();
                    $sql->bind_result($seriesID, $name, $description, $authorID, $authorName);
                    $data = [];
                    while ($sql->fetch()) {
                        $data[] = [
                            "id" => $seriesID,
                            "name" => $name,
                            "description" => $description,
                            "authorID" => $authorID,
                            "authorName" => $authorName
                        ];
                    }
                    http_response_code(200);
                    $conn->close();
                }
                header('Content-Type: application/json');
                echo json_encode($data);
                break;
            }
        case 'POST':
            {
                if (! isset($_POST['name'])) {
                    http_response_code(400);
                    die();
                } else {
                    $name = $_POST['name'];
                }
                if (isset($_POST['author'])) {
                    $author = $_POST['author'];
                } else
                    $author = null;
                if (isset($_POST['description'])) {
                    $description = $_POST['description'];
                } else
                    $description = null;
                include 'connection.php';
                $query = "INSERT INTO `series`(`name`, `author`, `description`) VALUES (?, ?, ?)";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('sis', $name, $author, $description);
                $sql->execute();
                $id = $conn->insert_id;
                if ($id == 0) {
                    http_response_code(400);
                    $conn->close();
                    die();
                }
                http_response_code(201);
                header("Location: /api/series/$id");
                $conn->close();
                break;
            }
        case 'PUT':
            {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if (! filter_var($id, FILTER_VALIDATE_INT)) {
                        http_response_code(400);
                        die();
                    }
                } else {
                    http_response_code(400);
                    die();
                }
                $json = json_decode(file_get_contents("php://input"), true);
                if (! isset($json['name'])) {
                    http_response_code(400);
                    die();
                } else {
                    $name = $json['name'];
                }
                if (isset($json['author'])) {
                    $author = $json['author'];
                } else
                    $author = null;
                if (isset($json['description'])) {
                    $description = $json['description'];
                } else
                    $description = null;
                include 'connection.php';
                $query = "UPDATE `series` SET `name` = ?,  `author` = ?, `description` = ? WHERE `id` = ?";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('sisi', $name, $author, $description, $id);
                $sql->execute();
                if ($sql->affected_rows == 0) {
                    if (sqlmatches($conn) == 1) {
                        http_response_code(200);
                        $conn->close();
                        die();
                    }
                    http_response_code(404);
                    $conn->close();
                    die();
                } else if ($sql->affected_rows == - 1) {
                    http_response_code(400);
                    $conn->close();
                    die();
                }
                http_response_code(200);
                $conn->close();
                break;
            }

        case 'DELETE':
            {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if (filter_var($id, FILTER_VALIDATE_INT)) {
                        include 'connection.php';
                        $query = "DELETE FROM series WHERE id=?";
                        $sql = mysqli_prepare($conn, $query);
                        $sql->bind_param('i', $id);
                        $sql->execute();
                        if ($sql->affected_rows > 0) {
                            http_response_code(200);
                        } else {
                            http_response_code(404);
                        }
                        $sql->close();
                    } else {
                        http_response_code(400);
                    }
                } else {
                    http_response_code(400);
                }
                break;
            }
    }
} else {
    http_response_code(405);
}
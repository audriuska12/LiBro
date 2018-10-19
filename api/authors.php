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
                        $query = "SELECT authors.id, authors.name, authors.bio FROM authors WHERE authors.id = ?";
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
                        if (! ($sql->bind_result($authorID, $name, $bio))) {
                            http_response_code(503);
                            die();
                        }
                        if ($sql->fetch()) {
                            $data = [
                                "id" => $authorID,
                                "name" => $name,
                                "bio" => $bio
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
                    $query = "SELECT authors.id, authors.name, authors.bio FROM authors";
                    $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                    $sql->execute();
                    $sql->bind_result($authorID, $name, $bio);
                    $data = [];
                    while ($sql->fetch()) {
                        $data[] = [
                            "id" => $authorID,
                            "name" => $name,
                            "bio" => $bio
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
                $headers = apache_request_headers();
                if (! isset($headers['Authorization'])) {
                    http_response_code(403);
                    die();
                } else {
                    include "jwt.php";
                    $tok = TokenEngine::readToken($headers['Authorization']);
                    if (!$tok) {
                        http_response_code(403);
                        die;
                    } else {
                        if (!isset($tok->user) || !isset($tok->expires) || strtotime($tok->expires) < time()){
                            http_response_code(403);
                            
                            die;
                        }
                    }
                }
                if ((! isset($_POST['name'])) || empty($_POST['name'])) {
                    http_response_code(400);
                    die();
                } else {
                    $name = $_POST['name'];
                }
                if (isset($_POST['bio'])) {
                    $bio = $_POST['bio'];
                } else
                    $bio = null;
                include 'connection.php';
                $query = "INSERT INTO `authors`(`name`, `bio`) VALUES (?, ?)";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('ss', $name, $bio);
                $sql->execute();
                $id = $conn->insert_id;
                if ($id == 0) {
                    http_response_code(400);
                    $conn->close();
                    die();
                }
                http_response_code(201);
                header("Location: /api/authors/$id");
                $conn->close();
                break;
            }
        case 'PUT':
            {
                $headers = apache_request_headers();
                if (! isset($headers['Authorization'])) {
                    http_response_code(403);
                    die();
                } else {
                    include "jwt.php";
                    $tok = TokenEngine::readToken($headers['Authorization']);
                    if (!$tok) {
                        http_response_code(403);
                        die;
                    } else {
                        if (!isset($tok->user) || !isset($tok->expires) || strtotime($tok->expires) < time()){
                            http_response_code(403);
                            
                            die;
                        }
                    }
                }
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
                if (! isset($json['name']) || empty($json['name'])) {
                    http_response_code(400);
                    die();
                } else {
                    $name = $json['name'];
                }
                if (isset($json['bio'])) {
                    $bio = $json['bio'];
                } else
                    $bio = null;
                include 'connection.php';
                $query = "UPDATE `authors` SET `name` = ?,  `bio` = ? WHERE `id` = ?";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('ssi', $name, $bio, $id);
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
                $headers = apache_request_headers();
                if (! isset($headers['Authorization'])) {
                    http_response_code(403);
                    die();
                } else {
                    include "jwt.php";
                    $tok = TokenEngine::readToken($headers['Authorization']);
                    if (!$tok) {
                        http_response_code(403);
                        die;
                    } else {
                        if (!isset($tok->user) || !isset($tok->expires) || strtotime($tok->expires) < time()){
                            http_response_code(403);
                            
                            die;
                        }
                    }
                }
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    if (filter_var($id, FILTER_VALIDATE_INT)) {
                        include 'connection.php';
                        $query = "DELETE FROM authors WHERE id=?";
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
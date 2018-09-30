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
                        $query = "SELECT books.id, books.title, books.description, books.published, authors.id as authorID, authors.name, series.id as seriesID, series.name as seriesName FROM `books` LEFT JOIN `authors` ON books.author = authors.id LEFT JOIN `series` ON books.series = series.ID WHERE books.id = ?";
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
                        if (! ($sql->bind_result($bookID, $bookTitle, $bookDesc, $bookPublished, $authorID, $authorName, $seriesID, $seriesName))) {
                            http_response_code(503);
                            die();
                        }
                        if ($sql->fetch()) {
                            $data = [
                                "id" => $bookID,
                                "title" => $bookTitle,
                                "description" => $bookDesc,
                                "published" => $bookPublished,
                                "authorID" => $authorID,
                                "authorName" => $authorName,
                                "seriesID" => $seriesID,
                                "seriesName" => $seriesName
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
                    if (isset($_GET['series'])) {
                        $series = $_GET['series'];
                        if (! filter_var($series, FILTER_VALIDATE_INT)) {
                            http_response_code(400);
                            $conn->close();
                            die();
                        }
                        $query = "SELECT books.id, books.title, books.description, books.published, authors.id as authorID, authors.name, series.id as seriesID, series.name as seriesName FROM `books` LEFT JOIN `authors` ON books.author = authors.id LEFT JOIN `series` ON books.series = series.ID WHERE series.id = ?";
                        $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                        $sql->bind_param('i', $series);
                    } else if (isset($_GET['author'])) {
                        $author = $_GET['author'];
                        if (! filter_var($author, FILTER_VALIDATE_INT)) {
                            http_response_code(400);
                            $conn->close();
                            die();
                        }
                        $query = "SELECT books.id, books.title, books.description, books.published, authors.id as authorID, authors.name, series.id as seriesID, series.name as seriesName FROM `books` LEFT JOIN `authors` ON books.author = authors.id LEFT JOIN `series` ON books.series = series.ID WHERE authors.id = ?";
                        $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                        $sql->bind_param('i', $author);
                    } else {
                        $query = "SELECT books.id, books.title, books.description, books.published, authors.id as authorID, authors.name, series.id as seriesID, series.name as seriesName FROM `books` LEFT JOIN `authors` ON books.author = authors.id LEFT JOIN `series` ON books.series = series.ID";
                        $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                    }
                    $sql->execute();
                    $sql->bind_result($bookID, $bookTitle, $bookDesc, $bookPublished, $authorID, $authorName, $seriesID, $seriesName);
                    $data = [];
                    while ($sql->fetch()) {
                        $data[] = [
                            "id" => $bookID,
                            "title" => $bookTitle,
                            "description" => $bookDesc,
                            "published" => $bookPublished,
                            "authorID" => $authorID,
                            "authorName" => $authorName,
                            "seriesID" => $seriesID,
                            "seriesName" => $seriesName
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
                if (! isset($_POST['title'])) {
                    http_response_code(400);
                    die();
                } else {
                    $title = $_POST['title'];
                }
                if (! isset($_POST['description'])) {
                    http_response_code(400);
                    die();
                } else {
                    $desc = $_POST['description'];
                }
                if (! isset($_POST['published'])) {
                    http_response_code(400);
                    die();
                } else {
                    $published = $_POST['published'];
                }
                if (isset($_POST['author'])) {
                    $author = $_POST['author'];
                } else
                    $author = null;
                if (isset($_POST['series'])) {
                    $series = $_POST['series'];
                } else
                    $series = null;
                include 'connection.php';
                $query = "INSERT INTO `books`(`title`, `author`, `description`, `series`, `published`) VALUES (?, ?, ?, ?, ?)";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('sisis', $title, $author, $desc, $series, $published);
                $sql->execute();
                $id = $conn->insert_id;
                if ($id == 0) {
                    http_response_code(400);
                    $conn->close();
                    die();
                }
                http_response_code(201);
                header("Location: /api/books/$id");
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
                if (! isset($json['title'])) {
                    http_response_code(400);
                    die();
                } else {
                    $title = $json['title'];
                }
                if (! isset($json['description'])) {
                    http_response_code(400);
                    die();
                } else {
                    $desc = $json['description'];
                }
                if (! isset($json['published'])) {
                    http_response_code(400);
                    die();
                } else {
                    $published = $json['published'];
                }
                if (isset($json['author'])) {
                    $author = $json['author'];
                } else
                    $author = null;
                if (isset($json['series'])) {
                    $series = $json['series'];
                } else
                    $series = null;
                include 'connection.php';
                $query = "UPDATE `books` SET `title` = ?,  `author` = ?, `description` = ?, `series` = ?, `published` = ? WHERE `id` = ?";
                $sql = mysqli_prepare($conn, $query) or die("Error preparing connection");
                $sql->bind_param('sisisi', $title, $author, $desc, $series, $published, $id);
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
                        $query = "DELETE FROM books WHERE id=?";
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
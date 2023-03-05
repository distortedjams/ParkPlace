<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_uploads";
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $ip_address = $_SERVER["REMOTE_ADDR"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $ip_address);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: display.php?user_id=" . $user_id);
        exit();
    } else {
        $stmt = $conn->prepare(
        "INSERT INTO users (username, password, latitude, longitude) VALUES (?,?,?,?)"
        );
        $stmt->bind_param("ssdd", $ip_address, $ip_address, $latitude, $longitude);
        if ($stmt->execute()) {
            $user_id = $conn->insert_id;
            $stmt->close();
            $target_dir = "uploads/";
            $target_file =
                $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(
                pathinfo($target_file, PATHINFO_EXTENSION)
            );
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            if (
                $imageFileType != "jpg" &&
                $imageFileType != "png" &&
                $imageFileType != "jpeg" &&
                $imageFileType != "gif"
            ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (
                    move_uploaded_file(
                        $_FILES["fileToUpload"]["tmp_name"],
                        $target_file
                    )
                ) {
                    echo "The file " .
                        htmlspecialchars(
                            basename($_FILES["fileToUpload"]["name"])
                        ) .
                        " has been uploaded.";
                    $stmt = $conn->prepare(
                        "INSERT INTO files (user_id, file_name, file_type) VALUES (?,?,?)"
                    );
                    $stmt->bind_param(
                        "iss",
                        $user_id,
                        $target_file,
                        $imageFileType
                    );
                    $stmt->execute();
		    header("Location: display.php?user_id=" . $user_id);
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "Error creating user. Please try again.";
        }
    }
    $conn->close();
} ?>
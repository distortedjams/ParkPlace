<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="swipe-container">
      <div class="content">
        <?php
        // Get the user ID from the query string
        $user_id = $_GET["user_id"];

        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "file_uploads");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Select the file record for the user with the given ID
        $sql = "SELECT file_name FROM files WHERE user_id = $user_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output the image file
            $row = $result->fetch_assoc();
            $file_name = $row["file_name"];
            echo '<img src="' . $file_name . '" alt="User file">';
        } else {
            echo "No file found for user with ID: $user_id";
        }

        // Close the database connection
        $conn->close();
        ?>
      </div>
      <div class="description">
        <h2>Park Place Memo App</h2>
        <p>The Park Place Memo App is a digital note-taking tool that helps users remember where they've parked their car in large outdoor parking areas, with a history function that allows users to track their parking habits and patterns over time. The app also includes tools to help users navigate and explore the parking area, such as custom maps and information about nearby facilities. Overall, the Park Place Memo App is a useful tool for anyone who struggles to remember where they've parked their car in outdoor parking areas.</p>
      </div>
    </div>

   <script src="script.js"></script>
  </body>
</html>
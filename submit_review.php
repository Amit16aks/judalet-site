<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $review = $conn->real_escape_string($_POST['review']);

    $sql = "INSERT INTO reviews (name, review, created_at) VALUES ('$name', '$review', NOW())";
    if ($conn->query($sql) === TRUE) {
        echo "Review submitted successfully! 5% off code: FIRE5OFF (Use at checkout)";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
<?php

include 'config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];

// Check if the post belongs to the logged-in user
$sql = "SELECT * FROM posts WHERE id = $post_id AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "You don't have permission to delete this post.";
    exit();
}

$sql = "DELETE FROM posts WHERE id = $post_id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

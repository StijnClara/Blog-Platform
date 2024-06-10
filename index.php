<?php
include 'config.php';
include 'includes/header.php';

//Fetch posts from the database
$sql = "SELECT posts.id, posts.title, posts.created_at,users.username FROM posts INNER JOIN users ON posts.user_id = users.id order by posts.created_at DESC";
$result = $conn->query($sql);
?>
<h2>Recent Posts</h2>
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<article>";
        echo "<h3><a href='post.php?id=" . $row["id"] . "'>" .$row["title"] . "</a></h3>";
        echo "<p> by " . $row["username"] . " on " . $row["created_at"] . "</p>";
        echo "</article>";
    }
} else {
    echo "<p> No posts found</p>";
}

include 'includes/footer.php';
?>


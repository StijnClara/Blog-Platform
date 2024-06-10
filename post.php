<?php
include 'config.php';
include 'includes/header.php';

$post_id = $_GET['id'];

//fetch post
$sql = "SELECT posts.title, posts.content, posts.created_at, users.username FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = $post_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
    echo "<h2>" . $post['title'] . "</h2>";
    echo "<p>by " . $post['username'] . " on " . $post['created_at'] .  "</p>";
    echo "<div>" . nl2br($post['content']) . "</div>";
} else {
    echo "<p> Post not found </p>";
}

// Fetch Comments
$sql = "SELECT comments.content, comments.created_at, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = $post_id ORDER BY comments.created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Comments</h3>";
    while($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p>" . $row['content'] . "</p>";
        echo "<p>by " . $row['username'] . " on " . $row['created_at'] .  "</p>";
        echo "</div>";
    }

} else {
    echo "<p> No comments yet </p>";
}
// Include this in post.php where comments are displayed
if (isset($_SESSION['user_id'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $comment_content = $_POST['content'];
        $user_id = $_SESSION['user_id'];

        $sql = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$comment_content')";
        if ($conn->query($sql) === TRUE) {
            header("Location: post.php?id=$post_id");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>
    <h3>Add a Comment</h3>
    <form method="post" action="post.php?id=<?php echo $post_id; ?>">
        <textarea id="content" name="content" required></textarea>
        <button type="submit">Submit Comment</button>
    </form>
<?php
} else {
    echo "<p><a href='login.php'>Login</a> to add a comment.</p>";
}
include 'includes/footer.php';
?>

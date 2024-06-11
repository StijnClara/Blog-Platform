<?php
include 'config.php';
include 'includes/header.php';


$post_id = $_GET['id'];
$sql = "SELECT posts.title, posts.content, users.username, categories.name AS category, posts.user_id 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        JOIN post_categories ON posts.id = post_categories.post_id 
        JOIN categories ON post_categories.category_id = categories.id 
        WHERE posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($title, $content, $username, $category, $post_user_id);
$stmt->fetch();
$stmt->close();
?>

    <h2><?php echo $title; ?></h2>
    <p><?php echo $content; ?></p>
    <p><em>Posted by <?php echo $username; ?> in <?php echo $category; ?></em></p>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post_user_id) { ?>
    <a href="edit_post.php?id=<?php echo $post_id; ?>">Edit Post</a> |
    <a href="delete_post.php?id=<?php echo $post_id; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post</a>
<?php } ?>

    <h3>Comments</h3>
<?php
$sql = "SELECT comments.comment, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = ? ORDER BY comments.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$stmt->bind_result($comment, $username);
while ($stmt->fetch()) {
    echo "<p><strong>$username:</strong> $comment</p>";
}
$stmt->close();
?>

<?php if (isset($_SESSION['user_id'])) { ?>
    <form method="post" action="post.php?id=<?php echo $post_id; ?>">
        <label for="comment">Add a comment:</label>
        <textarea id="comment" name="comment" required></textarea>
        <button type="submit">Submit</button>
    </form>
<?php } else { ?>
    <p><a href="login.php">Log in</a> to add a comment.</p>
<?php } ?>

<?php include 'includes/footer.php'; ?>
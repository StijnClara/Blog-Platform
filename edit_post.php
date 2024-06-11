<?php
include 'config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$post_id = $_GET['id'];

// Check if the post belongs to the logged-in user
$sql = "SELECT * FROM posts WHERE id = $post_id AND user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "You don't have permission to edit this post.";
    include 'includes/footer.php';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category'];

    $sql = "UPDATE posts SET title = '$title', content = '$content' WHERE id = $post_id";
    if ($conn->query($sql) === TRUE) {
        // Update the category in the post_categories table
        $category_sql = "UPDATE post_categories SET category_id = '$category_id' WHERE post_id = $post_id";
        if ($conn->query($category_sql) === TRUE) {
            header("Location: post.php?id=$post_id");
        } else {
            echo "Error: " . $category_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $post = $result->fetch_assoc();

    // Fetch categories for the dropdown
    $categories_sql = "SELECT * FROM categories";
    $categories_result = $conn->query($categories_sql);

    // Fetch current category
    $current_category_sql = "SELECT category_id FROM post_categories WHERE post_id = $post_id";
    $current_category_result = $conn->query($current_category_sql);
    $current_category = $current_category_result->fetch_assoc()['category_id'];
}
?>

    <h2>Edit Post</h2>
    <form method="post" action="edit_post.php?id=<?php echo $post_id; ?>">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo $post['title']; ?>" required>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required><?php echo $post['content']; ?></textarea>
        <label for="category">Category:</label>
        <select id="category" name="category">
            <?php while($category = $categories_result->fetch_assoc()) { ?>
                <option value="<?php echo $category['id']; ?>" <?php if ($category['id'] == $current_category) echo 'selected'; ?>><?php echo $category['name']; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Save Changes</button>
    </form>

<?php include 'includes/footer.php'; ?>
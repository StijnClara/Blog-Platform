<?php
include 'config.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category'];

    // Insert the post into the posts table
    $sql = "INSERT INTO posts (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
    if ($conn->query($sql) === TRUE) {
        $post_id = $conn->insert_id;

        // Insert the category into the post_categories table
        $category_sql = "INSERT INTO post_categories (post_id, category_id) VALUES ('$post_id', '$category_id')";
        if ($conn->query($category_sql) === TRUE) {
            header("Location: index.php");
        } else {
            echo "Error: " . $category_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // Fetch categories for the dropdown
    $categories_sql = "SELECT * FROM categories";
    $categories_result = $conn->query($categories_sql);
}
?>

    <h2>Create Post</h2>
    <form method="post" action="create_post.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required></textarea>
        <label for="category">Category:</label>
        <select id="category" name="category">
            <?php while($category = $categories_result->fetch_assoc()) { ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
            <?php } ?>
        </select>
        <button type="submit">Create Post</button>
    </form>

<?php include 'includes/footer.php'; ?>
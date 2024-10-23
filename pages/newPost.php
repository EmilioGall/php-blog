<?php

// Start SESSION
session_start();

// Include the database connection script
require '../partials/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../index.php");

    exit();
};

// Checks if the request method is POST, meaning the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Form field values
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    $target_dir = "../img/posts-images/";

    // Create full path for the uploaded image
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    // Move uploaded image from its temporary location to the target directory
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Prepares a SQL statement to insert a new post
    $stmt = $connection->prepare(

        "INSERT INTO posts (title, content, user_id, category_id, image) VALUES (?, ?, ?, ?, ?)"

    );

    $stmt->execute([$title, $content, $user_id, $category_id, $target_file]);

    header("Location: ./dashboard.php");

    exit();
};

// Fetch categories for the select input
$category_stmt = $connection->query("SELECT * FROM categories");

$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link fav Icon -->
    <link rel="icon" type="image/svg+xml" href="../img/favicon.ico" />

    <!-- Link Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Link Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Link Custom Style -->
    <link rel="stylesheet" href="../css/style.css">

    <title>PHP MyBlog - New Post</title>

</head>

<body>

    <!-- Header -->
    <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

        <nav class="row justify-content-between align-items-center">

            <li class="col-4 d-flex align-items-center">

                <h1 class="text-light m-0">MyBlog</h1>

            </li>

            <li class="col-4 d-flex align-items-center justify-content-end">

                <a href="./dashboard.php" class="btn btn-light">Dashboard</a>

            </li>

        </nav>

    </header>
    <!-- /Header -->

    <!-- Main -->
    <main class="container">

        <h2>Create New Post</h2>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">

                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>

            </div>

            <div class="mb-3">

                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" required></textarea>

            </div>

            <div class="mb-3">

                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>

                    <?php foreach ($categories as $category): ?>

                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="mb-3">

                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">

            </div>

            <button type="submit" class="btn btn-primary">Create Post</button>

        </form>

    </main>
    <!-- /Main -->

</body>

</html>
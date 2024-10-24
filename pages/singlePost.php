<?php

// Start SESSION
session_start();

// Include the database connection script
require '../partials/database.php';

// Create direction for the images
$target_dir = "../img/posts-images/";

// Check if an ID is provided
if (isset($_GET['id'])) {

   // Set id from GET
   $post_id = $_GET['id'];

   // Prepare the SQL query statement for getting the specific post
   $sql = "SELECT posts.*, users.username, categories.name AS category_name 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            JOIN categories ON posts.category_id = categories.id 
            WHERE posts.id = :post_id";

   // Prepare the statement
   $stmt = $connection->prepare($sql);

   // Bind the post ID parameter
   $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

   // Execute the prepared statement
   $stmt->execute();

   // Fetch the single post
   $post = $stmt->fetch(PDO::FETCH_ASSOC);

   

   // Check if the post exists
   if (!$post) {

      die('Post not found');
   };
} else {

   die('Invalid request');
};

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

   <title>PHP MyBlog - <?= htmlspecialchars($post['title']) ?></title>

</head>

<body>

   <!-- Header -->
   <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

      <nav class="row justify-content-between align-items-center">

         <li class="col-4 d-flex align-items-center">

            <h1 class="text-light m-0">

               <a href="../index.php">MyBlog</a>

            </h1>

         </li>

         <li class="col-4 d-flex gap-2 align-items-center justify-content-end">

            <!-- Dashboard-Logout / Registration-Login Links -->
            <?php if ($_SESSION['user_id']): ?> <!-- Check if user is logged in -->

               <a href="./dashboard.php" class="btn btn-light">Dashboard</a>
               <a href="./logout.php" class="btn btn-light">Logout</a>

            <?php else: ?>

               <a href="./register.php" class="btn btn-light">Register</a>
               <a href="./login.php" class="btn btn-light">Login</a>

            <?php endif; ?>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <div class="row justify-content-center">

         <div class="col-8">

            <!-- Post Title -->
            <h1><?= htmlspecialchars($post['title']) ?></h1>

            <!-- Post Category & Author & Date -->
            <p class="text-muted">Category: <?= htmlspecialchars($post['category_name']) ?> | Author: <?= htmlspecialchars($post['username']) ?> | Date: <?= htmlspecialchars($post['created_at']) ?></p>

            <!-- Post Image -->
            <?php if ($post['image']): ?> <!-- Check if the post has an image -->

               <img src="<?= $target_dir . htmlspecialchars($post['image']) ?>" class="img-fluid mb-3" alt="<?= htmlspecialchars($post['title']) ?>">

            <?php else: ?> <!-- Placeholder image if no image exists -->

               <img src="https://placehold.co/600x400?text=No Image Available" class="img-fluid mb-3" alt="No Image Available">

            <?php endif; ?>

            <!-- Post Content -->
            <div class="post-content">
               <p><?= nl2br(htmlspecialchars($post['content'])) ?></p> <!-- nl2br() converts newlines to <br> -->
            </div>

         </div>

      </div>

   </main>
   <!-- /Main -->

</body>

</html>
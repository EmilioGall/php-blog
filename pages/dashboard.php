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

// Create direction for the images
$target_dir = "../img/posts-images/";

// Fetch user's posts //

// Retrieves the user ID from the session
$user_id = $_SESSION['user_id'];

// Use a JOIN query to get the related category names from the categories table.
$stmt = $connection->prepare(
   "SELECT posts.*, categories.name AS category_name 
                        FROM posts 
                        JOIN categories ON posts.category_id = categories.id 
                        WHERE posts.user_id = ?" // Filters posts by the logged-in user ID.
);

// Execute the query by passing the user ID as a parameter
$stmt->execute([$user_id]);

// Define variable of the associative array of all posts of the user
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

   <title>PHP MyBlog - Dashboard</title>

</head>

<body>

   <!-- Header -->
   <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

      <nav class="row justify-content-between align-items-center">

         <li class="col-4 d-flex align-items-center">

            <!-- Blog title -->
            <h1 class="text-light m-0">

               <a href="../index.php">MyBlog</a>

            </h1>

         </li>

         <li class="col-4 d-flex align-items-center justify-content-end">

            <!-- Logout Button -->
            <a href="./logout.php" class="btn btn-light">Logout</a>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <!-- Header for user's posts section -->
      <h2>Your Posts</h2>

      <!-- Button to create new post -->
      <a href="./newPost.php" class="btn btn-primary mb-3">Create New Post</a>

      <div class="row">

         <!-- Loop through each post to create a card for each one -->
         <?php foreach ($posts as $post): ?>

            <div class="col-12 mb-4">

               <div class="card">

                  <?php if ($post['image']): ?> <!-- Check if the post has an image -->

                     <!-- Display post image -->
                     <img src="<?= $target_dir . htmlspecialchars($post['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">

                  <?php elseif (!$post['image']): ?> <!-- Placeholder image if no image exists -->

                     <img src="https://placehold.co/600x400?text=Placeholder\nImage" class="card-img-top" alt="Placeholder">

                  <?php endif; ?>

                  <div class="card-body">

                     <!-- Post Title -->
                     <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>

                     <!-- Post Content -->
                     <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 100)) . '...' ?></p>

                     <!-- Post Category -->
                     <p class="card-text"><small class="text-muted">Category: <?= htmlspecialchars($post['category_name']) ?></small></p>

                     <!-- Edit button -->
                     <a href="./updatePost.php?id=<?= $post['id'] ?>" class="btn btn-warning">Edit</a>

                     <!-- Delete button -->
                     <a href="./deletePost.php?id=<?= $post['id'] ?>" class="btn btn-danger">Delete</a>

                  </div>

               </div>

            </div>

         <?php endforeach; ?>

      </div>

   </main>
   <!-- /Main -->

</body>

</html>
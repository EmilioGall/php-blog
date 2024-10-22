<?php

session_start();

require '../partials/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {

   header("Location: ../index.php");

   exit();
};

// Fetch user's posts
$user_id = $_SESSION['user_id'];

$stmt = $connection->prepare(
   "SELECT posts.*, categories.name AS category_name 
                        FROM posts 
                        JOIN categories ON posts.category_id = categories.id 
                        WHERE posts.user_id = ?"
);

$stmt->execute([$user_id]);

// Define variable of the array of all posts of the user
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

            <h1 class="text-light m-0">MyBlog</h1>

         </li>

         <li class="col-4 d-flex align-items-center justify-content-end">

            <a href="./logout.php" class="btn btn-light">Logout</a>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <h2>Your Posts</h2>

      <a href="./newPost.php" class="btn btn-primary mb-3">Create New Post</a>

      <div class="row">

         <?php foreach ($posts as $post): ?>

            <div class="col-12 mb-4">

               <div class="card">

                  <?php if ($post['image']): ?>

                     <img src="<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">

                  <?php elseif (!$post['image']): ?>

                     <img src="https://placehold.co/600x400?text=Placeholder\nImage" class="card-img-top" alt="Placeholder">

                  <?php endif; ?>

                  <div class="card-body">

                     <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>

                     <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 100)) . '...' ?></p>

                     <p class="card-text"><small class="text-muted">Category: <?= htmlspecialchars($post['category_name']) ?></small></p>

                     <a href="./updatePost.php?id=<?= $post['id'] ?>" class="btn btn-warning">Edit</a>

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
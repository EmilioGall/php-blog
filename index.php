<?php

session_start();

require './partials/database.php';

// Fetch all posts
$stmt = $connection->prepare(
   "SELECT posts.*, users.username, categories.name AS category_name 
                        FROM posts 
                        JOIN users ON posts.user_id = users.id 
                        JOIN categories ON posts.category_id = categories.id"
);

$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);


// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($posts);
// echo '</pre>';

?>

<!DOCTYPE html>
<html lang="en">

<head>

   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Link fav Icon -->
   <link rel="icon" type="image/svg+xml" href="./img/favicon.ico" />

   <!-- Link Bootstrap -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Link Fontawesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

   <!-- Link Custom Style -->
   <link rel="stylesheet" href="./css/style.css">

   <title>PHP MyBlog</title>

</head>

<body>

   <!-- Header -->
   <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

      <nav class="row justify-content-between align-items-center">

         <li class="col-4 d-flex align-items-center">

            <h1 class="text-light m-0">MyBlog</h1>

         </li>

         <li class="col-4 d-flex align-items-center justify-content-end">

            <a href="login.php" class="btn btn-light">Log In</a>

            <a href="register.php" class="btn btn-light">Register</a>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <div class="row justify-content-center">

         <?php foreach ($posts as $post): ?>

            <div class="col-12 mb-4">

               <div class="card">

                  <?php if ($post['image']): ?>

                     <img src="<?= htmlspecialchars($post['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">

                  <?php endif; ?>

                  <div class="card-body">

                     <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>

                     <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 100)) . '...' ?></p>

                     <p class="card-text"><small class="text-muted">Category: <?= htmlspecialchars($post['category_name']) ?> | Author: <?= htmlspecialchars($post['username']) ?></small></p>

                     <a href="view_post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>

                  </div>

               </div>

            </div>

         <?php endforeach; ?>

      </div>

   </main>
   <!-- /Main -->

   <!-- Footer -->
   <footer class="container p-4 mb-3 rounded rounded-4">

      <h5 class="text-light m-0">Footer</h5>

   </footer>
   <!-- /Footer -->
</body>

</html>
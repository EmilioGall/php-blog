<?php

// Start SESSION
session_start();

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($_SESSION);
// echo '</pre>';

// Include the database connection script
require './partials/database.php';

// Initialize filter variables
$authorFilter = isset($_POST['author']) ? $_POST['author'] : 'all';
$categoryFilter = isset($_POST['category']) ? $_POST['category'] : 0;

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($_POST);
// echo '</pre>';

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($_POST['category']);
// echo '</pre>';

// Prepare the SQL query statement
$sql = "SELECT *, users.username, categories.name AS category_name 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        JOIN categories ON posts.category_id = categories.id";

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($sql);
// echo '</pre>';

// Add conditions for Author and Category Filter
$conditions = [];

if ($authorFilter === 'mine' && isset($_SESSION['user_id'])) {

   $conditions[] = "posts.user_id = " . intval($_SESSION['user_id']);
};

if ($categoryFilter != 0) {

   $conditions[] = "posts.category_id = " . intval($categoryFilter);
};

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($conditions);
// echo '</pre>';

// If there are conditions, append them to the SQL query.
if (!empty($conditions)) {

   $sql .= " WHERE " . implode(' AND ', $conditions);
};

// Prepare the statement
$stmt = $connection->prepare($sql);

// Execute the prepared statement for all posts
$stmt->execute();

// Fetch all results as an associative array of all posts
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($posts);
// echo '</pre>';

// Prepare the SQL query statement
$category_stmt = $connection->prepare(
   "SELECT * FROM categories"
);

// Execute the prepared statement for categories
$category_stmt->execute();

$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre style="background: #DEDEDE; color: #484848;">';
// var_dump($categories);
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

   <title>PHP MyBlog - Home</title>

</head>

<body>

   <!-- Header -->
   <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

      <nav class="row justify-content-between align-items-center">

         <li class="col-4 d-flex align-items-center">

            <!-- Blog Title -->
            <h1 class="text-light m-0">

               <a href="./index.php">MyBlog</a>

            </h1>

         </li>

         <li class="col-4 d-flex gap-2 align-items-center justify-content-end">

            <!-- Registration / Dashboard Link -->
            <?php if ($_SESSION['user_id']): ?> <!-- Check if user is logged in -->

               <a href="./pages/dashboard.php" class="btn btn-light">Dashboard</a>

            <?php elseif (!$_SESSION['user_id']): ?> <!-- Print LogIn Link if user is not logged -->

               <a href="./pages/register.php" class="btn btn-light">Register</a>

            <?php endif; ?>
            <!-- Registration Link -->

            <!-- Log In / Log Out Link -->
            <?php if ($_SESSION['user_id']): ?> <!-- Check if user is logged in -->

               <a href="./pages/logout.php" class="btn btn-light">Logout</a>

            <?php elseif (!$_SESSION['user_id']): ?> <!-- Print LogIn Link if user is not logged -->

               <a href="./pages/login.php" class="btn btn-light">Login</a>

            <?php endif; ?>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <div class="row g-3 justify-content-center mx-5">

         <!-- Filters -->
         <?php if ($_SESSION['user_id']): ?> <!-- Print Filter Select if user is logged -->

            <form method="POST" action="" class="col-12 d-flex justify-content-between">

               <!-- Author Filter -->
               <div>

                  <div class="d-flex gap-2">

                     <input type="radio" class="btn-check" name="author" id="all-author" value="all" onchange="this.form.submit();" autocomplete="off" <?= $authorFilter == 'all' ? 'checked' : '' ?>>
                     <label class="btn" for="all-author">All Author</label>

                     <input type="radio" class="btn-check" name="author" id="mine" value="mine" onchange="this.form.submit();" autocomplete="off" <?= $authorFilter == 'mine' ? 'checked' : '' ?>>
                     <label class="btn" for="mine">Only Mine</label>

                  </div>

               </div>
               <!-- /Author Filter -->

               <!-- Category Filter -->
               <div>

                  <div class="input-group mb-3">

                     <label class="input-group-text" for="inputGroupSelect01">Category Filter</label>
                     <select class="form-select" id="inputGroupSelect01" name="category" onchange="this.form.submit();">

                        <option value="0" <?= $categoryFilter == 0 ? 'selected' : '' ?>>All Categories</option>

                        <?php foreach ($categories as $category): ?>

                           <option value="<?= $category['id'] ?>" <?= $categoryFilter == $category['id'] ? 'selected' : '' ?>> <?= htmlspecialchars($category['name']) ?> </option>

                        <?php endforeach; ?>

                     </select>

                  </div>

               </div>
               <!-- /Category Filter -->

            </form>

         <?php endif; ?>
         <!-- /Filters -->

         <?php foreach ($posts as $post): ?> <!-- Loop through each post -->

            <div class="col-12 d-flex justify-content-center">

               <div class="card w-50">

                  <?php if ($post['image']): ?> <!-- Check if the post has an image -->

                     <img src="<?= $post['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">

                  <?php elseif (!$post['image']): ?> <!-- Placeholder image if no image exists -->

                     <img src="https://placehold.co/600x400?text=Placeholder\nImage" class="card-img-top" alt="Placeholder">

                  <?php endif; ?>

                  <div class="card-body">

                     <!-- Post Title -->
                     <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>

                     <!-- Post Content -->
                     <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 50)) . '...' ?></p>

                     <!-- Post Category & Author -->
                     <p class="card-text"><small class="text-muted">Category: <?= htmlspecialchars($post['category_name']) ?> | Author: <?= htmlspecialchars($post['username']) ?></small></p>

                     <!-- Post Details Link -->
                     <a href="view_post.php?id=<?= $post['id'] ?>" class="btn btn-primary">Read More</a>

                  </div>

               </div>

            </div>

         <?php endforeach; ?>

      </div>

   </main>
   <!-- /Main -->

</body>

</html>
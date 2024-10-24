<?php

session_start();

require '../partials/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {

   header("Location: ../index.php");

   exit();
};

// Create direction for the images
$target_dir = "../img/posts-images/";

// Fetch the post and categories
$post_id = $_GET['id'];

$stmt = $connection->prepare("SELECT * FROM posts WHERE id = ?");

$stmt->execute([$post_id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post || $post['user_id'] != $_SESSION['user_id']) {

   header("Location: ./dashboard.php");

   exit();
}

// Fetch categories for the select input
$category_stmt = $connection->query("SELECT * FROM categories");

$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   $title = $_POST['title'];
   $content = $_POST['content'];
   $category_id = $_POST['category_id'];

   $image_changed = false;

   // Handle image upload, if a new image is provided
   $target_name = $post['image'];

   echo '<pre style="background: #DEDEDE; color: #484848;">';
   var_dump($post['image']);
   echo '</pre>';

   if (!empty($_FILES['image']['name'])) {

      // Create direction for the uploaded image
      $target_dir = "../img/posts-images/";

      // Create name for the uploaded image
      $target_name = basename($_FILES["image"]["name"]);

      // Create full path for the uploaded image
      $target_file = $target_dir . $target_name;

      move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

      $image_changed = true;
   };

   // Prepares a SQL statement to update post
   $stmt = $connection->prepare(
      "UPDATE posts SET title = ?, content = ?, category_id = ?, image = ? WHERE id = ?"
   );

   // Execute the query by passing the updated post parameters
   $stmt->execute([$title, $content, $category_id, $target_name, $post_id]);

   echo '<pre style="background: #DEDEDE; color: #484848;">';
   var_dump('Post Update', $_POST['update_post']);
   echo '</pre>';

   // Determine where to redirect based on whether the image changed
   if ($image_changed) {

      // Form submitted due to image change
      header("Location: updatePost.php?id=" . $post_id);
   } else {

      // Form submitted by button click
      header("Location: dashboard.php");
   };
   
   exit();
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

   <title>PHP MyBlog - Update Post</title>

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

   <main class="container">

      <h2>Update Post</h2>

      <form method="POST" enctype="multipart/form-data">

         <div class="mb-3">

            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

         </div>

         <div class="mb-3">

            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>

         </div>

         <div class="mb-3">

            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id" required>

               <?php foreach ($categories as $category): ?>
                  <option value="<?= $category['id'] ?>" <?= $category['id'] == $post['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($category['name']) ?></option>
               <?php endforeach; ?>

            </select>

         </div>

         <div class="mb-3">

            <label for="image" class="form-label">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="this.form.submit();">
            <img src="<?= $post['image'] ? $target_dir . htmlspecialchars($post['image']) : 'https://placehold.co/600x400?text=Placeholder\nImage' ?>" alt="Current Image" class="img-fluid mt-2" style="max-width: 200px;">

         </div>

         <button type="submit" name="update_post" class="btn btn-primary">Update Post</button>

      </form>

   </main>

</body>

</html>
<?php

session_start();

require '../partials/database.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {

   header("Location: ../index.php");

   exit();
};

if (isset($_GET['id'])) {

   $post_id = $_GET['id'];

   // Ensure the user owns the post before deleting
   $stmt = $connection->prepare("SELECT * FROM posts WHERE id = ?");

   $stmt->execute([$post_id]);

   $post = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($post && $post['user_id'] == $_SESSION['user_id']) {

      // Delete the post
      $stmt = $connection->prepare("DELETE FROM posts WHERE id = ?");

      $stmt->execute([$post_id]);
      
   };

};

header("Location: ./dashboard.php");

exit();

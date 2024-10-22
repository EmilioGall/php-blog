<?php

session_start();

require '../partials/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   $username = $_POST['username'];
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];

   // Check if username already exists
   $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username");

   $stmt->execute(['username' => $username]);

   $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($existingUser) {

      $error = "Username already exists. Please choose another one.";
   } elseif ($password !== $confirm_password) {

      $error = "Passwords do not match.";
   } else {

      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Insert the new user into the database
      $stmt = $connection->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

      $stmt->execute([$username, $hashedPassword]);

      // Automatically log the user in
      $_SESSION['user_id'] = $connection->lastInsertId();

      header("Location: ./dashboard.php");

      exit();
   }
}
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

   <title>PHP MyBlog - Register</title>

</head>

<body>

   <!-- Header -->
   <header class="container p-4 mt-3 rounded rounded-4 fixed-top">

      <nav class="row justify-content-between align-items-center">

         <li class="col-4 d-flex align-items-center">

            <h1 class="text-light m-0">MyBlog</h1>

         </li>

         <li class="col-4 d-flex align-items-center justify-content-end">

            <a href="../index.php" class="btn btn-light">Home</a>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <h2>Create Account</h2>

      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

      <form method="POST">

         <div class="mb-3">

            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>

         </div>

         <div class="mb-3">

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>

         </div>

         <div class="mb-3">

            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>

         </div>

         <button type="submit" class="btn btn-primary">Register</button>

      </form>

   </main>
   <!-- /Main -->

</body>

</html>
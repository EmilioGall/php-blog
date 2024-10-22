<?php

session_start();

require '../partials/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

   $username = $_POST['username'];
   $passwordInput = $_POST['password'];

   $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username");

   $stmt->execute(['username' => $username]);

   $user = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($user && password_verify($passwordInput, $user['password'])) {

      $_SESSION['user_id'] = $user['id'];

      header("Location: dashboard.php");

      exit();

      // -
   } else {

      $error = "Invalid credentials.";

      // -
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

            <a href="../index.php" class="btn btn-light">Home</a>

         </li>

      </nav>

   </header>
   <!-- /Header -->

   <!-- Main -->
   <main class="container">

      <div class="row mx-5">

         <h2>Login</h2>

         <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

         <form method="POST">

            <div class="form-floating mb-3">

               <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>

               <label for="username">Username</label>

            </div>

            <div class="form-floating mb-3">

               <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
               <label for="password">Password</label>
            </div>

            <button type="submit" class="btn btn-primary">Log In</button>

         </form>

      </div>

   </main>
   <!-- /Main -->

</body>

</html>
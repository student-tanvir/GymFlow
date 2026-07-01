<?php
session_start();
$_SESSION['admin'] = true;

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if($username == "Tanvir" && $password == "Tanvir@123"){
        $_SESSION['admin'] = "loggedin";
        header("Location: curd1.php");
    } else {
        $error = "Only admin can login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    
    <div class="card shadow p-4" style="width: 350px;">
        
        <h3 class="text-center mb-4">Admin Login</h3>

        <?php if($error != ""): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button name="login" class="btn btn-primary w-100">Login</button>
        
        </form>

    </div>

</div>

</body>
</html>
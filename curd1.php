<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
error_reporting(0);

$conn = mysqli_connect("localhost","root","","mydb1");

$errors = [];
$editData = null;

/* INSERT */
if(isset($_POST['add'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $plan = $_POST['plan'];
    $join_date = $_POST['join_date'];
    $fees = $_POST['fees'];
    $trainer = trim($_POST['trainer']);

    // VALIDATION
    if($name == "") $errors[] = "Name required";
    if(!preg_match("/^[0-9]{10}$/",$phone)) $errors[] = "Phone must be 10 digits";
    if($age < 10 || $age > 100) $errors[] = "Age must be between 10-100";
    if($fees < 0) $errors[] = "Fees must be positive";
    if($join_date == "") $errors[] = "Join date required";

    if(empty($errors)){
        mysqli_query($conn,"INSERT INTO members(name,phone,age,gender,plan,join_date,fees,trainer)
        VALUES('$name','$phone','$age','$gender','$plan','$join_date','$fees','$trainer')");
    }
}

/* DELETE */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM members WHERE id=$id");
}

/* EDIT FETCH */
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM members WHERE id=$id");
    $editData = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $plan = $_POST['plan'];
    $join_date = $_POST['join_date'];
    $fees = $_POST['fees'];
    $trainer = trim($_POST['trainer']);

    // VALIDATION
    if($name == "") $errors[] = "Name required";
    if(!preg_match("/^[0-9]{10}$/",$phone)) $errors[] = "Phone must be 10 digits";
    if($age < 10 || $age > 100) $errors[] = "Age must be between 10-100";
    if($fees < 0) $errors[] = "Fees must be positive";
    if($join_date == "") $errors[] = "Join date required";

    if(empty($errors)){
        mysqli_query($conn,"UPDATE members SET 
        name='$name', phone='$phone', age='$age', gender='$gender',
        plan='$plan', join_date='$join_date', fees='$fees', trainer='$trainer'
        WHERE id=$id");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Gym</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h2 class="text-center mb-4">Forge&Grind</h2>

<!-- ERRORS -->
<?php if(!empty($errors)){ ?>
<div class="alert alert-danger">
<?php foreach($errors as $e){ echo "<div>$e</div>"; } ?>
</div>
<?php } ?>

<!-- FORM -->
<div class="card p-3 mb-4">
<form method="POST" class="row g-3">

<input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

<div class="col-md-6">
<input type="text" name="name" class="form-control" placeholder="Enter Name"
value="<?php echo $editData['name'] ?? ''; ?>">
</div>

<div class="col-md-6">
<input type="text" name="phone" class="form-control" placeholder="Enter Phone"
value="<?php echo $editData['phone'] ?? ''; ?>">
</div>

<div class="col-md-4">
<input type="number" name="age" class="form-control" placeholder="Enter Age"
value="<?php echo $editData['age'] ?? ''; ?>">
</div>

<div class="col-md-4">
<select name="gender" class="form-control">
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>
</div>

<div class="col-md-4">
<select name="plan" class="form-control">
<option>1 Month</option>
<option>3 Months</option>
<option>6 Months</option>
<option>1 Year</option>
</select>
</div>

<div class="col-md-4">
<input type="date" name="join_date" class="form-control"
value="<?php echo $editData['join_date'] ?? ''; ?>">
</div>

<div class="col-md-4">
<input type="number" name="fees" class="form-control" placeholder="Fees"
value="<?php echo $editData['fees'] ?? ''; ?>">
</div>

<div class="col-md-4">
<input type="text" name="trainer" class="form-control" placeholder="Trainer"
value="<?php echo $editData['trainer'] ?? ''; ?>">
</div>

<div class="col-md-12">
<?php if($editData){ ?>
<button class="btn btn-warning w-100" name="update">Update</button>
<?php } else { ?>
<button class="btn btn-success w-100" name="add">Add</button>
<?php } ?>
</div>

</form>
</div>

<!-- TABLE -->
<table class="table table-bordered table-striped">
<tr class="table-dark">
<th>ID</th>
<th>Name</th>
<th>Phone</th>
<th>Age</th>
<th>Gender</th>
<th>Plan</th>
<th>Date</th>
<th>Fees</th>
<th>Trainer</th>
<th>Action</th>
</tr>

<?php
$result = mysqli_query($conn,"SELECT * FROM members");

while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td><?php echo $row['age']; ?></td>
<td><?php echo $row['gender']; ?></td>
<td><?php echo $row['plan']; ?></td>
<td><?php echo $row['join_date']; ?></td>
<td><?php echo $row['fees']; ?></td>
<td><?php echo $row['trainer']; ?></td>

<td>
<a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>
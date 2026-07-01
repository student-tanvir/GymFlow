<?php
$conn = mysqli_connect("localhost","root","","mydb");

if(!$conn){
die("Connection Failed");
}

/* INSERT */
if(isset($_POST['add'])){
$name = $_POST['name'];
$course = $_POST['course'];

mysqli_query($conn,"INSERT INTO students(name,course) VALUES('$name','$course')");
}

/* DELETE */
if(isset($_GET['delete'])){
$id = $_GET['delete'];
mysqli_query($conn,"DELETE FROM students WHERE id=$id");
}

/* EDIT FETCH */
$editData = null;
if(isset($_GET['edit'])){
$id = $_GET['edit'];
$res = mysqli_query($conn,"SELECT * FROM students WHERE id=$id");
$editData = mysqli_fetch_assoc($res);
}

/* UPDATE */
if(isset($_POST['update'])){
$id = $_POST['id'];
$name = $_POST['name'];
$course = $_POST['course'];

mysqli_query($conn,"UPDATE students SET name='$name',course='$course' WHERE id=$id");
}

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
$search = $_GET['search'];
}

/* PAGINATION */
$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM students WHERE name LIKE '%$search%' LIMIT $start,$limit";
$result = mysqli_query($conn,$query);

/* TOTAL PAGES */
$totalQuery = mysqli_query($conn,"SELECT COUNT(*) as total FROM students WHERE name
LIKE '%$search%'");
$totalRow = mysqli_fetch_assoc($totalQuery);
$total = $totalRow['total'];
$pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
<title>CRUD Bootstrap</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">
</head>

<body class="container mt-4">

<h2 class="text-center mb-4">Student CRUD System</h2>

<!-- FORM -->
<div class="card p-3 mb-4">
<form method="POST" class="row g-3">
<input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">

<div class="col-md-5">
<input type="text" name="name" class="form-control" placeholder="Enter Name"
value="<?php echo $editData['name'] ?? ''; ?>" required>
</div>

<div class="col-md-5">
<input type="text" name="course" class="form-control" placeholder="Enter Course"
value="<?php echo $editData['course'] ?? ''; ?>" required>
</div>

<div class="col-md-2">
<?php if($editData){ ?>
<button name="update" class="btn btn-warning w-100">Update</button>

<?php } else { ?>
<button name="add" class="btn btn-success w-100">Add</button>
<?php } ?>
</div>
</form>
</div>

<!-- SEARCH -->
<form method="GET" class="mb-3 d-flex">
<input type="text" name="search" class="form-control me-2" placeholder="Search by name"
value="<?php echo $search; ?>">
<button class="btn btn-primary">Search</button>
</form>

<!-- TABLE -->
<table class="table table-bordered table-striped">
<tr class="table-dark">
<th>ID</th>
<th>Name</th>
<th>Course</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['course']; ?></td>
<td>
<a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
<a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"

onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php } ?>

</table>

<!-- PAGINATION -->
<nav>
<ul class="pagination">

<?php for($i=1; $i<=$pages; $i++){ ?>
<li class="page-item <?php if($i==$page) echo 'active'; ?>">
<a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
<?php echo $i; ?>
</a>
</li>
<?php } ?>

</ul>
</nav>

</body>
</html>
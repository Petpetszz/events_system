<?php include 'db.php'; ?>

<?php
$error = "";
$success = "";

if(isset($_POST['add'])){

    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $discount = $_POST['discount'];
    $event = $_POST['event'];

    // ✅ BACKEND VALIDATION
    if(empty($fname) || empty($lname)){
        $error = "First name and last name are required!";
    } else {
        $conn->query("INSERT INTO participants(evCode,partFName,partLName,partDRate)
        VALUES('$event','$fname','$lname','$discount')");

        $success = "Participant added successfully!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Participants</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<a href="index.php" class="btn btn-secondary mb-3">← Back Home</a>

<h2>Add Participant</h2>

<!-- ✅ ERROR MESSAGE -->
<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- ✅ SUCCESS MESSAGE -->
<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">

<select name="event" class="form-control mb-2" required>
<?php
$res = $conn->query("SELECT * FROM events");
while($row = $res->fetch_assoc()){
    echo "<option value='{$row['evCode']}'>{$row['evName']}</option>";
}
?>
</select>

<!-- ✅ REQUIRED FIELDS -->
<input type="text" name="fname" placeholder="First Name" class="form-control mb-2" required>
<input type="text" name="lname" placeholder="Last Name" class="form-control mb-2" required>

<input type="number" step="0.01" name="discount" placeholder="Discount Rate" class="form-control mb-2">

<button name="add" class="btn btn-success">Add</button>
</form>

<hr>

<h3>Participants</h3>
<table class="table table-bordered">
<tr><th>Name</th><th>Discount</th></tr>

<?php
$res = $conn->query("SELECT * FROM participants");
while($row = $res->fetch_assoc()){
    echo "<tr>
        <td>{$row['partFName']} {$row['partLName']}</td>
        <td>{$row['partDRate']}</td>
    </tr>";
}
?>

</table>

</body>
</html>
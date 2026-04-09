<?php include 'db.php'; ?>

<?php
$error = "";
$success = "";

// ADD
if(isset($_POST['add'])){
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);

    if(empty($fname) || empty($lname)){
        $error = "First name and last name are required!";
    } else {
        $conn->query("INSERT INTO participants(evCode,partFName,partLName,partDRate)
        VALUES('{$_POST['event']}','$fname','$lname','{$_POST['discount']}')");
        $success = "Participant added!";
    }
}

// UPDATE
if(isset($_POST['update'])){
    $fullname = explode(" ", $_POST['fullname'], 2);

    $fname = $fullname[0];
    $lname = isset($fullname[1]) ? $fullname[1] : "";

    $conn->query("UPDATE participants SET
        evCode='{$_POST['event']}',
        partFName='$fname',
        partLName='$lname',
        partDRate='{$_POST['discount']}'
        WHERE partID={$_POST['id']}
    ");
}

// DELETE
if(isset($_GET['del'])){
    $conn->query("DELETE FROM participants WHERE partID={$_GET['del']}");
    header("Location: participants.php");
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

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

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

<input type="text" name="fname" placeholder="First Name" class="form-control mb-2" required>
<input type="text" name="lname" placeholder="Last Name" class="form-control mb-2" required>
<input type="number" step="0.01" name="discount" placeholder="Discount Rate" class="form-control mb-2">

<button name="add" class="btn btn-success">Add</button>
</form>

<hr>

<h3>Participants</h3>
<table class="table table-bordered">
<tr>
<th>Name</th><th>Event</th><th>Discount</th><th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT p.*, e.evName FROM participants p 
JOIN events e ON p.evCode=e.evCode");

while($row = $res->fetch_assoc()){
?>

<tr>
<form method="POST">
<td>
<input type="text" name="fullname" 
value="<?= $row['partFName'].' '.$row['partLName'] ?>" 
class="form-control">
</td>

<td>
<select name="event" class="form-control">
<?php
$events = $conn->query("SELECT * FROM events");
while($e = $events->fetch_assoc()){
    $selected = ($e['evCode'] == $row['evCode']) ? "selected" : "";
    echo "<option value='{$e['evCode']}' $selected>{$e['evName']}</option>";
}
?>
</select>
</td>

<td>
<input type="number" step="0.01" name="discount" value="<?= $row['partDRate'] ?>" class="form-control">
</td>

<td>
<input type="hidden" name="id" value="<?= $row['partID'] ?>">
<button name="update" class="btn btn-success btn-sm">Update</button>
<a href="?del=<?= $row['partID'] ?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete this participant?')">Delete</a>
</td>
</form>
</tr>

<?php } ?>

</table>

</body>
</html>
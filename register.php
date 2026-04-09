<?php include 'db.php'; ?>

<?php
$success = "";

// ✅ ADD REGISTRATION
if(isset($_POST['reg'])){
    $q = $conn->query("
    SELECT e.evRFee, p.partDRate 
    FROM participants p 
    JOIN events e ON p.evCode=e.evCode 
    WHERE p.partID={$_POST['partID']}
    ");
    
    $data = $q->fetch_assoc();
    $fee = $data['evRFee'] - $data['partDRate'];

    $conn->query("INSERT INTO registration(partID,regDate,regFeePaid,regPMode)
    VALUES('{$_POST['partID']}','{$_POST['date']}','$fee','{$_POST['mode']}')");

    $success = "Registered successfully!";
}

// ✅ UPDATE REGISTRATION
if(isset($_POST['update'])){
    $conn->query("UPDATE registration SET
        regDate='{$_POST['date']}',
        regPMode='{$_POST['mode']}'
        WHERE regCode={$_POST['id']}
    ");
    header("Location: register.php");
}

// ✅ DELETE REGISTRATION
if(isset($_GET['del'])){
    $conn->query("DELETE FROM registration WHERE regCode={$_GET['del']}");
    header("Location: register.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<a href="index.php" class="btn btn-secondary mb-3">← Back Home</a>

<h2>Register Participant</h2>

<!-- ✅ SUCCESS MESSAGE -->
<?php if($success): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- ✅ FORM -->
<form method="POST">
<select name="partID" class="form-control mb-2" required>
<?php
$res = $conn->query("SELECT p.*, e.evName FROM participants p 
JOIN events e ON p.evCode=e.evCode");

while($row = $res->fetch_assoc()){
    echo "<option value='{$row['partID']}'>
    {$row['partFName']} {$row['partLName']} - {$row['evName']}
    </option>";
}
?>
</select>

<input type="date" name="date" class="form-control mb-2" required>

<select name="mode" class="form-control mb-2">
    <option>Cash</option>
    <option>Card</option>
</select>

<button name="reg" class="btn btn-warning">Register</button>
</form>

<hr>

<!-- ✅ REGISTRATION LIST -->
<h3>Registrations</h3>
<table class="table table-bordered">
<tr>
<th>Name</th>
<th>Event</th>
<th>Date</th>
<th>Fee Paid</th>
<th>Payment Mode</th>
<th>Action</th>
</tr>

<?php
$res = $conn->query("
SELECT r.*, p.partFName, p.partLName, e.evName 
FROM registration r
JOIN participants p ON r.partID=p.partID
JOIN events e ON p.evCode=e.evCode
ORDER BY r.regDate DESC
");

while($row = $res->fetch_assoc()){
?>

<tr>
<form method="POST">

<td><?= $row['partFName']." ".$row['partLName'] ?></td>
<td><?= $row['evName'] ?></td>

<td>
<input type="date" name="date" value="<?= $row['regDate'] ?>" class="form-control">
</td>

<td><?= $row['regFeePaid'] ?></td>

<td>
<select name="mode" class="form-control">
    <option <?= ($row['regPMode']=='Cash')?'selected':'' ?>>Cash</option>
    <option <?= ($row['regPMode']=='Card')?'selected':'' ?>>Card</option>
</select>
</td>

<td>
<input type="hidden" name="id" value="<?= $row['regCode'] ?>">
<button name="update" class="btn btn-success btn-sm">Update</button>
<a href="?del=<?= $row['regCode'] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this registration?')">
Delete
</a>
</td>

</form>
</tr>

<?php } ?>

</table>

<!-- AUTO HIDE SUCCESS -->
<script>
setTimeout(() => {
    let alert = document.querySelector('.alert');
    if(alert){
        alert.style.display = 'none';
    }
}, 3000);
</script>

</body>
</html>
<?php include 'db.php'; ?>

<?php
$success = "";

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
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $success ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<form method="POST">

<select name="partID" class="form-control mb-2" required>
<?php
$res = $conn->query("SELECT p.*, e.evRFee FROM participants p 
JOIN events e ON p.evCode=e.evCode");

while($row = $res->fetch_assoc()){
    echo "<option value='{$row['partID']}'>
    {$row['partFName']} {$row['partLName']}
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

<!-- OPTIONAL AUTO HIDE -->
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
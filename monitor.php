<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
<title>Monitoring</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<a href="index.php" class="btn btn-secondary mb-3">← Back Home</a>

<form method="GET">
<select name="event" class="form-control mb-2">
<?php
$res = $conn->query("SELECT * FROM events");
while($row = $res->fetch_assoc()){
    echo "<option value='{$row['evCode']}'>{$row['evName']}</option>";
}
?>
</select>

<button class="btn btn-info">Filter</button>
</form>

<hr>

<?php
if(isset($_GET['event'])){

$q = $conn->query("
SELECT e.evName,
CONCAT(p.partFName,' ',p.partLName) AS fullname,
r.regDate,
r.regFeePaid,
e.evRFee
FROM registration r
JOIN participants p ON r.partID=p.partID
JOIN events e ON p.evCode=e.evCode
WHERE e.evCode={$_GET['event']}
ORDER BY r.regDate DESC
");

$count = 0;
$total = 0;

echo "<table class='table table-bordered'>
<tr><th>Event</th><th>Name</th><th>Date</th><th>Fee Paid</th></tr>";

while($row = $q->fetch_assoc()){
    $count++;
    $total += $row['regFeePaid'];

    echo "<tr>
        <td>{$row['evName']}</td>
        <td>{$row['fullname']}</td>
        <td>{$row['regDate']}</td>
        <td>{$row['regFeePaid']}</td>
    </tr>";
}

echo "</table>";

echo "<h5>Total Records: $count</h5>";
echo "<h5>Total Fees: $total</h5>";
}
?>

</body>
</html>
<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
<title>Events</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<a href="index.php" class="btn btn-secondary mb-3">← Back Home</a>

<h2>Add Event</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Event Name" class="form-control mb-2" required>
    <input type="date" name="date" class="form-control mb-2" required>
    <input type="text" name="venue" placeholder="Venue" class="form-control mb-2">
    <input type="number" step="0.01" name="fee" placeholder="Fee" class="form-control mb-2">
    <button name="add" class="btn btn-primary">Add</button>
</form>

<?php
if(isset($_POST['add'])){
    $conn->query("INSERT INTO events(evName,evDate,evVenue,evRFee)
    VALUES('{$_POST['name']}','{$_POST['date']}','{$_POST['venue']}','{$_POST['fee']}')");
}
?>

<hr>

<h3>Event List</h3>
<table class="table table-bordered">
<tr>
    <th>Name</th><th>Date</th><th>Venue</th><th>Fee</th><th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM events");
while($row = $res->fetch_assoc()){
    echo "<tr>
        <td>{$row['evName']}</td>
        <td>{$row['evDate']}</td>
        <td>{$row['evVenue']}</td>
        <td>{$row['evRFee']}</td>
        <td><a href='?del={$row['evCode']}' class='btn btn-danger btn-sm'>Delete</a></td>
    </tr>";
}

if(isset($_GET['del'])){
    $conn->query("DELETE FROM events WHERE evCode={$_GET['del']}");
    header("Location: events.php");
}
?>

</table>

</body>
</html>
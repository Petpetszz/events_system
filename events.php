<?php include 'db.php'; ?>

<?php
// ADD EVENT
if(isset($_POST['add'])){
    $conn->query("INSERT INTO events(evName,evDate,evVenue,evRFee)
    VALUES('{$_POST['name']}','{$_POST['date']}','{$_POST['venue']}','{$_POST['fee']}')");
}

// DELETE EVENT
if(isset($_GET['del'])){
    $conn->query("DELETE FROM events WHERE evCode={$_GET['del']}");
    header("Location: events.php");
}

// UPDATE EVENT
if(isset($_POST['update'])){
    $conn->query("UPDATE events SET 
        evName='{$_POST['name']}',
        evDate='{$_POST['date']}',
        evVenue='{$_POST['venue']}',
        evRFee='{$_POST['fee']}'
        WHERE evCode={$_POST['id']}
    ");
    header("Location: events.php");
}
?>

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

<hr>

<h3>Event List</h3>
<table class="table table-bordered">
<tr>
    <th>Name</th>
    <th>Date</th>
    <th>Venue</th>
    <th>Fee</th>
    <th>Action</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM events");

while($row = $res->fetch_assoc()){
?>

<tr>
<form method="POST">
    <td>
        <input type="text" name="name" value="<?= $row['evName'] ?>" class="form-control">
    </td>
    <td>
        <input type="date" name="date" value="<?= $row['evDate'] ?>" class="form-control">
    </td>
    <td>
        <input type="text" name="venue" value="<?= $row['evVenue'] ?>" class="form-control">
    </td>
    <td>
        <input type="number" step="0.01" name="fee" value="<?= $row['evRFee'] ?>" class="form-control">
    </td>

    <td>
        <input type="hidden" name="id" value="<?= $row['evCode'] ?>">
        <button name="update" class="btn btn-success btn-sm">Update</button>
        <a href="?del=<?= $row['evCode'] ?>" class="btn btn-danger btn-sm"
           onclick="return confirm('Delete this event?')">Delete</a>
    </td>
</form>
</tr>

<?php } ?>

</table>

</body>
</html>
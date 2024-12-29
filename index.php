<?php
$conn = new mysqli('localhost', 'root', '', 'car_dealership');

// Create (Insert)
if (isset($_POST['add'])) {
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $conn->query("INSERT INTO cars (make, model, year, price) VALUES ('$make', '$model', $year, $price)");
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM cars WHERE id=$id");
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $conn->query("UPDATE cars SET make='$make', model='$model', year=$year, price=$price WHERE id=$id");
}

// Fetch All Records
$result = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Dealership CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .form-group { margin-bottom: 15px; }
        .form-group input { padding: 8px; width: 100%; }
        button { padding: 10px 15px; }
    </style>
</head>
<body>
    <h1>Car Dealership</h1>

    <form method="POST">
        <div class="form-group">
            <label>Make:</label>
            <input type="text" name="make" required>
        </div>
        <div class="form-group">
            <label>Model:</label>
            <input type="text" name="model" required>
        </div>
        <div class="form-group">
            <label>Year:</label>
            <input type="number" name="year" required>
        </div>
        <div class="form-group">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" required>
        </div>
        <button type="submit" name="add">Add Car</button>
    </form>

    <h2>Car List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['make'] ?></td>
                    <td><?= $row['model'] ?></td>
                    <td><?= $row['year'] ?></td>
                    <td><?= $row['price'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        <button onclick="editCar(<?= $row['id'] ?>, '<?= $row['make'] ?>', '<?= $row['model'] ?>', <?= $row['year'] ?>, <?= $row['price'] ?>)">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <form method="POST" id="editForm" style="display:none;">
        <input type="hidden" name="id" id="editId">
        <div class="form-group">
            <label>Make:</label>
            <input type="text" name="make" id="editMake" required>
        </div>
        <div class="form-group">
            <label>Model:</label>
            <input type="text" name="model" id="editModel" required>
        </div>
        <div class="form-group">
            <label>Year:</label>
            <input type="number" name="year" id="editYear" required>
        </div>
        <div class="form-group">
            <label>Price:</label>
            <input type="number" step="0.01" name="price" id="editPrice" required>
        </div>
        <button type="submit" name="update">Update Car</button>
    </form>

    <script>
        function editCar(id, make, model, year, price) {
            document.getElementById('editId').value = id;
            document.getElementById('editMake').value = make;
            document.getElementById('editModel').value = model;
            document.getElementById('editYear').value = year;
            document.getElementById('editPrice').value = price;
            document.getElementById('editForm').style.display = 'block';
        }
    </script>
</body>
</html>
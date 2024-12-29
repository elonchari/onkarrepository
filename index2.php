<?php
$conn = new mysqli("localhost", "root", "", "restaurant_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $item_id = $_POST['item_id'] ?? null;
    $name = $_POST['name'] ?? null;
    $price = $_POST['price'] ?? null;
    $category_id = $_POST['category_id'] ?? null;

    if ($action === 'create' && $name && $price && $category_id) {
        $conn->query("INSERT INTO menu_items (name, price, category_id) VALUES ('$name', $price, $category_id)");
    } elseif ($action === 'update' && $item_id && $name && $price && $category_id) {
        $conn->query("UPDATE menu_items SET name='$name', price=$price, category_id=$category_id WHERE id=$item_id");
    } elseif ($action === 'delete' && $item_id) {
        $conn->query("DELETE FROM menu_items WHERE id=$item_id");
    }
}

// Fetch Categories and Menu Items
$categories = $conn->query("SELECT * FROM menu_categories");
$menu_items = $conn->query("SELECT m.id, m.name, m.price, c.name AS category 
                            FROM menu_items m 
                            LEFT JOIN menu_categories c ON m.category_id = c.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-bottom: 20px; }
        input, select, button { padding: 8px; margin: 5px; }
    </style>
</head>
<body>
    <h1>Restaurant Management</h1>
    <form method="POST" action="index.php">
        <input type="hidden" name="item_id" id="item_id">
        <label>Name: <input type="text" name="name" id="name" required></label>
        <label>Price: <input type="number" name="price" id="price" step="0.01" required></label>
        <label>Category: 
            <select name="category_id" id="category_id" required>
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
        </label>
        <button type="submit" name="action" value="create">Add Item</button>
        <button type="submit" name="action" value="update">Update Item</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $menu_items->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td>$<?= $row['price'] ?></td>
                    <td><?= $row['category'] ?></td>
                    <td>
                        <button onclick="editItem(<?= $row['id'] ?>, <?= $row['name'] ?>, <?= $row['price'] ?>, <?= $row['category_id'] ?>)">Edit</button>
                        <form method="POST" action="index.php" style="display:inline;">
                            <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editItem(id, name, price, category_id) {
            document.getElementById('item_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('category_id').value = category_id;
        }
    </script>
</body>
</html>
<?php
session_start();
include("config.php");

if(isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, price, quantity) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sdi", $name, $price, $quantity);
    mysqli_stmt_execute($stmt);
}

if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

if(isset($_POST['edit_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, price=?, quantity=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sdii", $name, $price, $quantity, $id);
    mysqli_stmt_execute($stmt);
}

$result = mysqli_query($conn, "SELECT * FROM products");
?>

<h2>Products CRUD</h2>
<h3>Yangi mahsulot qoshish</h3>
<form method="POST">
    Name:<br>
    <input type="text" name="name" required><br>
    Price:<br>
    <input type="number" step="0.01" name="price" required><br>
    Quantity:<br>
    <input type="number" name="quantity" required><br>
    <button type="submit" name="add_product">Add</button>
</form>

<hr>
<h3>Barcha mahsulotlar</h3>
<table>
    <th>Id</th>
    <th>name</th>
    <th>price</th>
    <th>quantity</th>
    <th>Actions</th>
    <?php 
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['price']}</td>";
        echo "<td>{$row['quantity']}</td>";
        echo "<td>";
        echo "<a href='?edit={$row['id']}'>Edit</a> | ";        
        echo "<a href='?delete={$row['id']}'>Delete</a>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
<hr>

<?php
if(isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $edit_product = mysqli_fetch_assoc($res);
    ?>
    <h3>Edit Product</h3>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
        Name:<br>
        <input type="text" name="name" value="<?php echo $edit_product['name']; ?>" required><br>
        Price:<br>
        <input type="number" step="0.01" name="price" value="<?php echo $edit_product['price']; ?>" required><br>
        Quantity:<br>
        <input type="number" name="quantity" value="<?php echo $edit_product['quantity']; ?>" required><br>
        <button type="submit" name="edit_product">Saqlash</button>
    </form>
<?php } ?>
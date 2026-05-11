<?php 
include '../config/config.php';
checkLogin();

$conn = new mysqli("localhost", "root", "", "ordering_system");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);


$limit = 10; // orders per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$totalRows = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$orders = $conn->query("
    SELECT orderID, customer_name, address, phone
    FROM orders 
    ORDER BY order_date DESC
    LIMIT $limit OFFSET $offset
");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Customers</title>
    <link rel="stylesheet" href="<?= $base_url ?>admin/assets/css/customer.css">
    <link rel="stylesheet" href="<?= $base_url ?>admin/assets/css/sidebar.css">
    <link rel="stylesheet" href="<?= $base_url ?>admin/assets/icon/css/all.min.css">
</head>

<body>
    <?php include '../include/sidebar.php'; ?>

    <div class="content">
        <h1>Customer List</h1>
        <p>View all customers who have placed orders.</p>

        <div class="orders-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Name"><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td data-label="Address"><?= htmlspecialchars($row['address']) ?></td>
                            <td data-label="Phone"><?= htmlspecialchars($row['phone']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== PAGINATION ===== -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="page-btn">Prev</a>
            <?php else: ?>
                <span class="page-btn disabled">Prev</span>
            <?php endif; ?>

            <span class="page-info"><?= $page ?> of <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="page-btn">Next</a>
            <?php else: ?>
                <span class="page-btn disabled">Next</span>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>Manage Products</h2>
<a href="<?php echo base_url('admin/products/create'); ?>" class="btn btn-success mb-3">Add New Product</a>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($products->num_rows > 0): ?>
            <?php while($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="<?php echo base_url('admin/products/edit?id=' . $row['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form action="<?php echo base_url('admin/products/delete'); ?>" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No products found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

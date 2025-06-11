<?php include __DIR__ . '/../layouts/header.php'; ?>

<h2>Edit Product</h2>

<form action="<?php echo base_url('admin/products/edit'); ?>" method="POST">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

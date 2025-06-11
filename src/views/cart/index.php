<?php include __DIR__ . '/../layouts/header.php'; ?>

<h1>Shopping Cart</h1>

<?php if ($cart_items->num_rows > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th width="15%">Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $cart_items->fetch_assoc()): ?>
                <?php
                    $subtotal = $row['price'] * $row['quantity'];
                    $total_price += $subtotal;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <form action="<?php echo base_url('cart/update'); ?>" method="POST" class="d-flex">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                        </form>
                    </td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td>
                        <form action="<?php echo base_url('cart/remove'); ?>" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong>$<?php echo number_format($total_price, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
<?php else: ?>
    <div class="alert alert-info">
        Your cart is currently empty.
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

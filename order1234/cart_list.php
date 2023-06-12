<?php
include 'config.php';

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE product_id = '$remove_id'");
    header('location:index.php?page=cart_list');
    exit();
}


if (isset($_POST['update'])) {
    foreach ($_POST['quantity'] as $key => $value) {
        $productId = $key;
        $quantity = $value;
        mysqli_query($conn, "UPDATE cart SET qty = '$quantity' WHERE id = '$productId'");
    }
    header('location:index.php?page=cart_list');
    exit();
}

if (isset($_GET['delete_all'])) {
    mysqli_query($conn, "DELETE FROM cart");
    header('location:index.php?page=cart_list');
    exit();
}


if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $existingCartItem = mysqli_query($conn, "SELECT * FROM cart WHERE product_id = '$productId' LIMIT 1");

    if ($existingCartItem->num_rows > 0) {
        $existingCartItemRow = $existingCartItem->fetch_assoc();
        $existingCartItemId = $existingCartItemRow['id'];
        $existingCartItemQty = $existingCartItemRow['qty'];
        $updatedCartItemQty = $existingCartItemQty + $quantity;
        mysqli_query($conn, "UPDATE cart SET qty = '$updatedCartItemQty' WHERE id = '$existingCartItemId'");
    } else {
        mysqli_query($conn, "INSERT INTO cart (product_id, qty) VALUES ('$productId', '$quantity')");
    }

    header('location:index.php?page=cart_list');
    exit();
}

?>


<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4 page-title">
                <h3 class="text-white">Cart List</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>
<section class="page-section" id="menu">
    <form method="post" action="index.php?page=cart_list">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="sticky">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8"><b>Cart</b></div>
                                    <div class="col-md-4 text-right"><b>Total</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION['login_user_id'])) {
                        $data = "WHERE c.user_id = '" . $_SESSION['login_user_id'] . "' ";
                    } else {
                        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
                        $data = "WHERE c.client_ip = '" . $ip . "' ";
                    }

                    $cart_items = array();
                    $get = $conn->query("SELECT *, SUM(c.qty) AS total_qty FROM cart c INNER JOIN product_list p ON p.id = c.product_id " . $data . "GROUP BY c.product_id");

                    $total = 0;

                    while ($row = $get->fetch_assoc()) {
                        $itemTotal = $row['total_qty'] * $row['price'];
                        $total += $itemTotal;

                        $cart_items[] = array(
                            'product_id' => $row['product_id'],
                            'qty' => $row['total_qty'],
                            'itemTotal' => $itemTotal,
                            'name' => $row['name'],
                            'description' => $row['description'],
                            'price' => $row['price'],
                            'img_path' => $row['img_path'],
                        );
                    }
                    ?>

                    <?php foreach ($cart_items as $item) : ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4" style="text-align: -webkit-center">
                                        <a href="#" class="rem_cart btn btn-sm btn-outline-danger" data-product-id="<?php echo $item['product_id'] ?>"><i class="fa fa-trash"></i></a>
                                        <img src="assets/img/<?php echo $item['img_path'] ?>" alt="">
                                    </div>
                                    <div class="col-md-4">
                                        <p><b><large><?php echo $item['name'] ?></large></b></p>
                                        <p class="truncate"> <b><small>Desc: <?php echo $item['description'] ?></small></b></p>
                                        <p> <b><small>Unit Price: ₱<?php echo number_format($item['price'], 2) ?></small></b></p>
                                        <p><b>AMOUNT : <span style="color: black;"><?php echo $item['qty'] ?></span></b></p>

                                    </div>
                                    <div class="col-md-4 text-right">
                                        <b><large>₱<span class="item-total"><?php echo number_format($item['itemTotal'], 2) ?></span></large></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="sticky">
                        <div class="card">
                            <div class="card-body">
                                <p><large>Total Amount</large></p>
                                <hr>
                                <p class="text-right"><b>₱<span id="total-amount"><?php echo number_format($total, 2) ?></span></b></p>
                                <hr>
                                <div class="text-center">
                                    <button class="btn btn-block btn-outline-primary" type="button" id="checkout">Proceed to Checkout</button>
                                    <a href="index.php?page=cart_list&delete_all" class="btn btn-block btn-outline-danger mt-2">Delete All</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="total_amount" id="hidden-total-amount" value="<?php echo $total ?>">
        <?php
        $totalAmount = $total;
        ?>
    </form>
</section>

<style>
    .card p {
        margin: unset;
    }

    .card img {
        max-width: calc(100%);
        max-height: calc(59%);
    }

    div.sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 4.7em;
        z-index: 10;
        background: white;
    }

    .rem_cart {
        position: absolute;
        left: 0;
    }

    .confirmation-popup {
        position: fixed;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        padding: 10px 15px;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 14px;
        z-index: 9999;
    }

</style>

<script>
    $(document).ready(function() {
        $('.rem_cart').click(function(e) {
        e.preventDefault();
        var confirmation = confirm('Are you sure you want to delete this item?');
        if (confirmation) {
            var removeId = $(this).data('product-id');
            window.location.href = 'index.php?page=cart_list&remove=' + removeId;
        }
    });

        $('#checkout').click(function() {
            var totalAmount = parseFloat($('#hidden-total-amount').val());

            if (totalAmount === 0) {
                alert('Please add items to your cart before proceeding to checkout.');
            } else {
                if ('<?php echo isset($_SESSION['login_user_id']) ?>' == 1) {
                    location.replace('index.php?page=checkout');
                } else {
                    uni_modal('Checkout', 'login.php?page=checkout');
                }
            }
        });
    });
</script>
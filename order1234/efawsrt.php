<?php
include 'config.php';

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    echo "<script>
            var confirmation = confirm('Are you sure you want to delete this item?');
            if (confirmation) {
                window.location.href = 'index.php?page=cart_list&confirmed_remove=' + $remove_id;
            } else {
                var popup = document.createElement('div');
                popup.innerHTML = 'Item not deleted.';
                popup.classList.add('confirmation-popup');
                document.body.appendChild(popup);
            }
          </script>";
    exit();
}

if (isset($_GET['confirmed_remove'])) {
    $remove_id = $_GET['confirmed_remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id'");
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

if (isset($_POST['save_cart_amount'])) {
    if (isset($_SESSION['login_user_id'])) {
        $userId = $_SESSION['login_user_id'];
        $data = (isset($_SESSION['login_user_id'])) ? "WHERE c.user_id = '" . $_SESSION['login_user_id'] . "'" : "WHERE c.client_ip = '" . $_SERVER['REMOTE_ADDR'] . "'";
        $totalAmount = 0;
        $get = $conn->query("SELECT *, c.id as cid FROM cart c INNER JOIN product_list p ON p.id = c.product_id " . $data);
        while ($row = $get->fetch_assoc()) {
            $itemTotal = $row['qty'] * $row['price'];
            $totalAmount += $itemTotal;
        }

        mysqli_query($conn, "UPDATE users SET cart_total = '$totalAmount' WHERE id = '$userId'");

        echo 'Cart amount saved successfully!';
        exit();
    }
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
        <div class="mt-3 text-center">
            <button type="submit" name="update" class="btn btn-primary">Save Cart Amount</button>
        </div>

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
                        $data = "where c.user_id = '" . $_SESSION['login_user_id'] . "' ";
                    } else {
                        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
                        $data = "where c.client_ip = '" . $ip . "' ";
                    }
                    $total = 0;
                    $get = $conn->query("SELECT *, c.id as cid FROM cart c INNER JOIN product_list p ON p.id = c.product_id " . $data);
                    while ($row = $get->fetch_assoc()) {
                        $itemTotal = $row['qty'] * $row['price'];
                        $total += $itemTotal;
                        ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4" style="text-align: -webkit-center">
                                        <a href="#" class="rem_cart btn btn-sm btn-outline-danger" data-id="<?php echo $row['cid'] ?>"><i class="fa fa-trash"></i></a>
                                        <img src="assets/img/<?php echo $row['img_path'] ?>" alt="">
                                    </div>
                                    <div class="col-md-4">
                                        <p><b><large><?php echo $row['name'] ?></large></b></p>
                                        <p class="truncate"> <b><small>Desc: <?php echo $row['description'] ?></small></b></p>
                                        <p> <b><small>Unit Price: ₱<?php echo number_format($row['price'], 2) ?></small></b></p>
                                        <p><small>QTY:</small></p>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary qty-minus" type="button" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-minus"></button>
                                            </div>
                                            <input type="number" name="quantity[<?php echo $row['cid'] ?>]" value="<?php echo $row['qty'] ?>" min="1" class="form-control text-center qty-input" data-price="<?php echo $row['price'] ?>" data-id="<?php echo $row['cid'] ?>">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary qty-plus" type="button" data-id="<?php echo $row['cid'] ?>"><span class="fa fa-plus"></span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <b><large>₱<span class="item-total"><?php echo number_format($itemTotal, 2) ?></span></large></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
    $(document).ready(function () {
        $('.qty-input').each(function () {
            var qtyInput = $(this);
            qtyInput.data('initial-qty', qtyInput.val());
        });

        $('.qty-input').change(function () {
            var qtyInput = $(this);
            var initialQty = parseInt(qtyInput.data('initial-qty'));
            var qty = parseInt(qtyInput.val());
            var price = parseFloat(qtyInput.data('price'));
            var itemTotal = qty * price;
            var itemTotalFormatted = itemTotal.toFixed(2);
            qtyInput.closest('.card-body').find('.item-total').text(itemTotalFormatted);

            calculateTotalAmount();
        });

        $('.qty-minus').click(function () {
            var qtyInput = $(this).parent().siblings('.qty-input');
            var qty = parseInt(qtyInput.val());
            if (qty > 1) {
                qtyInput.val(qty - 1);
                qtyInput.change();
            }
        });

        $('.qty-plus').click(function () {
            var qtyInput = $(this).parent().siblings('.qty-input');
            var qty = parseInt(qtyInput.val());
            qtyInput.val(qty + 1);
            qtyInput.change();
        });

        $('.rem_cart').click(function () {
            var confirmation = confirm('Are you sure you want to delete this item?');
            if (confirmation) {
                var removeId = $(this).data('id');
                window.location.href = 'index.php?page=cart_list&confirmed_remove=' + removeId;
            } else {
                var popup = document.createElement('div');
                popup.innerHTML = 'Item not deleted.';
                popup.classList.add('confirmation-popup');
                document.body.appendChild(popup);
            }
        });

        $('#checkout').click(function () {
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

        function calculateTotalAmount() {
  var totalAmount = 0;
  $('.item-total').each(function() {
    var itemTotal = parseFloat($(this).text().replace(/,/g, ''));
    totalAmount += itemTotal;
  });
  $('#total-amount').text(totalAmount.toFixed(2));
  $('#hidden-total-amount').val(totalAmount.toFixed(2));
}

    });
</script>
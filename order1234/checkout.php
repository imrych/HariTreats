<header class="masthead">
<div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4 page-title">
                <h3 class="text-white">Checkout</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="" id="checkout-frm">
                <h4>Confirm Delivery Information</h4>
                <div class="form-group">
                    <label for="first_name" class="control-label">FIRST NAME</label>
                    <input type="text" name="first_name" required="" class="form-control" value="<?php echo $_SESSION['login_first_name'] ?>">
                </div>

                <div class="form-group">
                    <label for="last_name" class="control-label">LAST NAME</label>
                    <input type="text" name="last_name" required="" class="form-control" value="<?php echo $_SESSION['login_last_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="mobile" class="control-label">CONTACT</label>
                    <input type="text" name="mobile" required="" class="form-control" value="<?php echo $_SESSION['login_mobile'] ?>">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">EMAIL</label>
                    <input type="email" name="email" required="" class="form-control" value="<?php echo $_SESSION['login_email'] ?>">
                </div>
                <div class="form-group">
                    <label for="payment_method" class="control-label">Payment Method</label>
                    <?php
// Assuming you have established a database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the payment method value from the form submission
    $paymentMethod = $_POST['payment_method'];

    // Insert the payment method into the database table
    $stmt = $conn->prepare("INSERT INTO orders (payment_method) VALUES (?)");
    $stmt->bind_param("s", $paymentMethod);
    if ($stmt->execute()) {
        echo "Payment method saved successfully.";
    } else {
        echo "Error saving payment method: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

                </div>
                <div class="text-center">
                <ul class="pm_options">
                    <li>
                        <input type="radio" id="gcash" value='gcash' name="payment_method" />
                        <label for="gcash">Gcash</label>
                    </li>
                    <li>
                        <input type="radio" id="cash_on_delivery" value='cash_on_delivery' name="payment_method" />
                        <label for="cash_on_delivery">Cash On Delivery</label>
                    </li>
                </ul>
                </div>
                <div class="form-group">
                <div id="gcashdiv">
                    <img src="gcash1.png"/>
                </div>

                <div class="form-group" id="address">
                    <label for="address" class="control-label">ADDRESS</label>
                    <textarea cols="30" rows="3" name="address" required="" class="form-control"><?php echo $_SESSION['login_address'] ?></textarea>
                </div>
                </div>
                <div class="text-center">
                    <button class="btn btn-block btn-outline-primary" id="checkout-frm">PLACE ORDER</button>
                    <a class="btn btn-block btn-outline-primary" href="index.php?page=cart_list">CANCEL</a>
                </div>


            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#gcashdiv").hide();
        $("#address").hide();
        
        $('#checkout-frm').submit(function(e) {
            e.preventDefault();

            start_load();
            $.ajax({
                url: "admin/ajax.php?action=save_order",
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp == 1) {
                        alert_toast("Order successfully placed 1.");
                        setTimeout(function() {
                            location.replace('index.php?page=home');
                        }, 1500);
                    }
                }
            });
        });

        $("input[name$='payment_method']").click(function(e) {
            console.log($("input[name='payment_method']:checked").val());
            if($("input[name='payment_method']:checked").val()=="gcash"){
                $("#gcashdiv").show();
                $("#address").hide();
            }else{
                $("#gcashdiv").hide();
                $("#address").show();
            }
        });
    });
</script>

<style>
.pm_options {
  list-style-type: none;
  margin: 25px 0 0 0;
  padding: 0;
}

.pm_options li {
  float: left;
  margin: 0 5px 0 0;
  width: 500px;
  height: 40px;
  position: relative;
}

.pm_options label,
.pm_options input {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.pm_options input[type="radio"] {
  opacity: 0.01;
  z-index: 100;
}

.pm_options input[type="radio"]:checked+label,
.Checked+label {
  background: blueviolet;
}

.pm_options label {
  padding: 5px;
  border: 1px solid #CCC;
  cursor: pointer;
  z-index: 90;
}

.pm_options label:hover {
  background: red;
}
</style>
    
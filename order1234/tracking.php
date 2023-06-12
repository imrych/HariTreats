<div class="container-fluid">
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
          <div class="card card-stepper text-black" style="border-radius: 16px;">
            <div class="card-body p-5">
            <div class="table-responsive" style="height: 300px; overflow-y: scroll; scrollbar-color: red yellow; scrollbar-width: thin;">

                <h2>ORDER SUMMARY</h2>
                <table class="table">
                <thead class="sticky-top" style="background: #51050F; color: #F7FFA8;">
  <tr>
    <th>DATE</th>
    <th>ORDER NO.</th>
    <th>PRODUCT</th>
    <th>TOTAL PRICE</th>
    <th>PAYMENT METHOD</th>
    <th>ORDER STATUS</th>
  </tr>
</thead>

                  <tbody>
                    <?php
                    $i = 1;
                    include 'admin/db_connect.php';
                    $qrynew = $conn->query("SELECT * from orders where name = '" . $_SESSION['login_first_name'] . ' ' . $_SESSION['login_last_name'] . "' order by id DESC");
                    while ($rownew = $qrynew->fetch_assoc()):
                    ?>
                      <tr>
                        <td>
                          <p class="mb-0"><span><?php echo $rownew['date'] ?></span></p>
                        </td>
                        <td>
                          <h5 class="mb-0"><span class="text-primary font-weight-bold"><?php echo $rownew['id'] ?></span></h5>
                        </td>
                        <td>
                          <table>
                            <tbody>
                              <?php 
                              $total = 0;
                              $orderid = $rownew['id'];
                              $qry = $conn->query("SELECT pll.name, orl.qty, pll.price FROM order_list orl JOIN product_list pll ON orl.product_id = pll.id where orl.order_id = " . $orderid);
                              while($row=$qry->fetch_assoc()):
                              ?>
                                <tr>
                                  <td style="border:none; text-align: left;"><p style="color:#51050F;"> Name:</p> <?php echo $row['name'] ?></td>
                                  <td style="border:none; text-align: left;"><p style="color:#51050F;"> Price:</p> &#8369;<?php echo $row['price'] ?></td>
                                  <td style="border:none; text-align: left;"><p style="color:#51050F;">Qty:</p> <?php echo $row['qty'] ?></td>
                                </tr>
                              <?php 
                                $total += $row['price'];
                                endwhile;
                              ?>
                              
                            </tbody>
                          </table>
                        </td>
                        <td>
                                <h5 colspan="3" style="border:none; text-align: left;">&#8369;<?php echo $total ?></h5>
                              </td>
                        <td>
                          <h5 class="mb-0"><span><?php echo $rownew['payment_method'] ?></span></h5>
                        </td>
                        <td>
                          <h5 class="mb-0">
                            <?php if($rownew['status'] == 0): ?>
                              <span class="badge badge-secondary">For Verification</span>
                            <?php elseif($rownew['status'] == 1): ?>
                              <span class="badge badge-danger">Order Confirmed</span>
                            <?php elseif($rownew['status'] == 2):?>
                              <span class="badge badge-info">Out For Delivery</span>
                            <?php elseif($rownew['status'] == 3): ?>
                              <span class="badge badge-warning">Order Arrived</span>
                            <?php else: ?>
                              <span class="badge badge-success">Payment Received</span>
                            <?php endif; ?>
                          </h5>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                  <tfoot>
                    <!-- Footer content here if needed -->
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

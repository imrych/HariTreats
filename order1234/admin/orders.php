
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="sticky-header">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Payment Method</th>
                            <th>Order Confirmation</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        include 'db_connect.php';
                        $qry = $conn->query("SELECT * FROM orders ");
                        while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo $i++ ?></td>
                            <td><?php echo $row['date'] ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['address'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['mobile'] ?></td>
                            <td><?php echo $row['payment_method']?></td>
                            <?php if($row['status'] == 0): ?>
                                <td class="text-center"><span class="badge badge-secondary">For Verification</span></td>
                            <?php elseif($row['status'] == 1): ?>
                                <td class="text-center"><span class="badge badge-primary">Order Confirmed</span></td>
                            <?php elseif($row['status'] == 2): ?>
                                <td class="text-center"><span class="badge badge-info">Out For Delivery</span></td>
                            <?php elseif($row['status'] == 3): ?>
                                <td class="text-center"><span class="badge badge-warning">Order Arrived</span></td>
                            <?php else: ?>
                                <td class="text-center"><span class="badge badge-success">HariTreats Received</span></td>
                            <?php endif; ?>
                            <td>
                                <button class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id'] ?>">View Order</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('.view_order').click(function(){
        uni_modal('Order','view_order.php?id='+$(this).attr('data-id'))
    })
</script>

<style>
    /* Responsive CSS */
    @media screen and (max-width: 767px) {
        .table-responsive {
            overflow-x: auto;
        }
    }
        .sticky-header th {
            position: sticky;
            top: 0;
            background-color: #fff;
        }
    }
</style>

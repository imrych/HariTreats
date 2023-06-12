<div class="container-fluid">
	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Qty</th>
				<th>Order</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$total = 0;
			$status = 0;
			include 'db_connect.php';
			$qry = $conn->query("SELECT * FROM order_list o inner join product_list p on o.product_id = p.id  where order_id =".$_GET['id']);
			while($row=$qry->fetch_assoc()):
				$total += $row['qty'] * $row['price'];
			?>
			<?php $status=$row['status'] ?>
			<tr>
				<td><?php echo $row['qty'] ?></td>
				<td><?php echo $row['name'] ?></td>
				<td><?php echo number_format($row['qty'] * $row['price'],2) ?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2" class="text-right">TOTAL</th>
				<th ><?php echo number_format($total,2) ?></th>
			</tr>

		</tfoot>
	</table>
	<?php if($status != 0):?>
			<tr><td colspan="3">
				<ul class="status_options">
				<li>
                        <input type="radio" id="order_confirmed" value='1' name="status" style="background: #AB6D23"/>
                        <label for="out_for_delivery">Confirm Order</label>
                    </li>
                    <li>
                        <input type="radio" id="out_for_delivery" value='2' name="status" />
                        <label for="out_for_delivery">Out for Deliver</label>
                    </li>
                    <li>
                        <input type="radio" id="order_arrived" value='3' name="status" />
                        <label for="order_arrived">Order Arrived</label>
                    </li>
					<li>
                        <input type="radio" id="haritreats_received" value='4' name="status" />
                        <label for="haritreats_received">HariTreats Received</label>
                    </li>
                </ul></td>
			</tr>
			<?php endif; ?>
	
		
	<div class="text-center">
		<?php if($status == 0):?>
			<button class="btn btn-primary" id="confirm" type="button" onclick="confirm_order()">Confirm</button>
		<?php else: ?>
			<button class="btn btn-primary" id="confirm" type="button" onclick="update_order_status()">Update</button>
		<?php endif; ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

	</div>
	
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
</style>
<script>
	function confirm_order(){
		start_load()
		$.ajax({
			url:'ajax.php?action=confirm_order',
			method:'POST',
			data:{id:'<?php echo $_GET['id'] ?>'},
			success:function(resp){
				if(resp == 1){
					alert_toast("Order confirmed.")
                        setTimeout(function(){
                            location.reload()
                        },1500)
				}
			}
		})
	}

	function update_order_status(){
		start_load()
		$.ajax({
			url:'ajax.php?action=update_order_status',
			method:'POST',
			data:{id:'<?php echo $_GET['id'] ?>',
			status:1},
			success:function(resp){
				if(resp == 2){
					alert_toast("Order status updated.")
                        setTimeout(function(){
                            location.reload()
                        },1500)
				}
			}
		})
	}
	function update_order_status(){
		start_load()
		$.ajax({
			url:'ajax.php?action=update_order_status',
			method:'POST',
			data:{id:'<?php echo $_GET['id'] ?>',
			status:2},
			success:function(resp){
				if(resp == 3){
					alert_toast("Order status updated.")
                        setTimeout(function(){
                            location.reload()
                        },1500)
				}
			}
		})
	}
	function update_order_status(){
		console.log($("input[name='status']:checked").val());
		start_load()
		$.ajax({
			url:'ajax.php?action=update_order_status',
			method:'POST',
			data:{id:'<?php echo $_GET['id'] ?>',
			status:$("input[name='status']:checked").val()},
			success:function(resp){
				if(resp == 1){
					alert_toast("Order status updated.")
                        setTimeout(function(){
                            location.reload()
                        },1500)
				}
			}
		})
	}
</script>
<style>
.status_options {
  list-style-type: none;
  margin: 25px 0 0 0;
  padding: 0;
}

.status_options li {
  float: left;
  margin: 0 5px 0 0;
  width: 125px;
  height: 75px;
  position: relative;
}

.status_options label,
.status_options input {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.status_options input[type="radio"] {
  opacity: 0.01;
  z-index: 100;
}

.status_options input[type="radio"]:checked+label,
.Checked+label {
  background: blueviolet;
}

.status_options label {
  padding: 5px;
  border: 1px solid #CCC;
  cursor: pointer;
  z-index: 90;
}

.status_options label:hover {
  background: red;
}
</style>
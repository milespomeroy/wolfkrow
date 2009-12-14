<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Review Your Orders</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
	
	<script type="text/javascript" src="/scripts/jquery.js"></script>
	<script type="text/javascript" src="/scripts/ui.core.min.js"></script>
	<script type="text/javascript" src="/scripts/ui.stars.min.js"></script>
	<script type="text/javascript" src="/scripts/review-orders.js"></script>

</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>

	<h1>Review Your Orders</h1>
	
	<h2>Vendor Selections</h2>
	<table>
		<?php foreach ($orders as $order): ?>
		<tr>
			<th><?=ucwords($order->vendor_type)?></th>
			<td><?=$order->vendor_name?></td>
			<td>$<?=$order->price?></td>
			<td><?php
			if ($order->activated_date == NULL)
			{
				echo "Selected";
			}
			elseif ($order->filled == NULL)
			{
				echo "Active";
			}
			else
			{
				echo "Filled";
			}
			?></td>
			<td>
				<form action="/meal/rate" method="post" >
				    <div class="stars" id="<?=$order->vendor_type?>">
						<?php $rating = $order->rating; ?>
				        <input type="radio" name="newrate" value="1" 
							title="Poor" <?=is_rated(1, $rating)?>>
				        <input type="radio" name="newrate" value="2" 
							title="Meh" <?=is_rated(2, $rating)?>>
				        <input type="radio" name="newrate" value="3" 
							title="Average" <?=is_rated(3, $rating)?>>
				        <input type="radio" name="newrate" value="4" 
							title="Good" <?=is_rated(4, $rating)?>>
				        <input type="radio" name="newrate" value="5" 
							title="Awesome" <?=is_rated(5, $rating)?>>
						<input type="hidden" name="order_id" 
							id="order_id-<?=$order->vendor_type?>" 
							value="<?=$order->order_id?>">
						<input type="hidden" name="vendor_id" 
							id="vendor_id-<?=$order->vendor_type?>"
							value="<?=$order->vendor_id?>">
						<input type="submit" value="Rate" 
							<?=is_rated('submit', $rating)?>>
				    </div>
				</form>
				<div id="loader-<?=$order->vendor_type?>">wait&hellip;</div>
				<span class="test"></span>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	
	<h2>Ahh…</h2>
	<p>
		Now, sit back and enjoy your meal at Wolfkrow Diner. Don’t forget to
		rate your vendor selections.
	</p>
	
	<h2>Demo “Feature”</h2>

	<p>
		<a href="/meal/fillem">Mark all orders filled and start a new one</a>. <br>
		A new meal will not be created for this user until all the orders for
		this meal are filled.
	</p>

	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>
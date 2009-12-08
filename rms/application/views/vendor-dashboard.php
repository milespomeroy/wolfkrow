<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Vendor Dashboard</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<h1><?=$vendor_name?>’s Dashboard</h1>
	
	<ul>
		<li><a href="/meal/vendor/<?=$vendor_id?>">View Listing</a></li>
		<li><a href="/vendor/edit/<?=$vendor_id?>">Edit Listing</a></li>
	</ul>
	
	<h3>Status: Available</h3>
	<!--
		TODO Availability status of vendors
	-->
	<a href="">become unavailable</a>
	
	<h3>Avg Rating: ★★★★★</h3>
	
	<h2>Orders Up</h2>
	
	<form action="/vendor/fill_orders" method="post">
		<input type="submit" value="Update Orders">
	<table>
		<tr>
			<th>Name</th>
			<th>Time Active</th>
			<th>Filled</th>
		</tr>
	
	<!--
		TODO Refresh this periodically with javascript
	-->
	<?php foreach ($orders as $order): ?>
		<tr>
			<td><?=$order['name']?></td>
			<td class="center"><?=$order['mins_active']?> minutes</td>
			<td class="center"><input type="checkbox" 
				id="order-<?=$order['order_id']?>" name="orders[]" 
				value="<?=$order['order_id']?>"></td>
		</tr>
	<?php endforeach; ?>

	</table>
	</form>
	
	<h2>Net Revenue (10% of your gross goes to the restaurant)</h2>
	
	<p>
		Today: $20<br>
		Yesterday: $50<br>
		Week to date: $1000<br>
		Month to date: $1234
	</p>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
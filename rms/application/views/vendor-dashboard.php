<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Vendor Dashboard</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
	
	<script type="text/javascript" src="/scripts/jquery.js"></script>
	<script type="text/javascript" src="/scripts/ui.core.min.js"></script>
	<script type="text/javascript" src="/scripts/ui.stars.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$(".stars").stars();
		});
	</script>
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1><?=$vendor_name?>’s Dashboard</h1>
	
	<ul id="nav">
		<li><a href="/meal/vendor/<?=$vendor_id?>">View Listing</a></li>
		<!--
			TODO Edit vendor listing info
		-->
	</ul>
	
	<!--
		TODO Availability status of vendors
	-->
	<p>
		<form class="stars">
			<?php $rating = round($avg_rating); ?>
	        <input type="radio" name="newrate" value="1" 
				title="Poor" <?=is_rated(1, $rating)?> disabled >
	        <input type="radio" name="newrate" value="2" 
				title="Meh" <?=is_rated(2, $rating)?> disabled >
	        <input type="radio" name="newrate" value="3" 
				title="Average" <?=is_rated(3, $rating)?> disabled >
	        <input type="radio" name="newrate" value="4" 
				title="Good" <?=is_rated(4, $rating)?> disabled >
	        <input type="radio" name="newrate" value="5" 
				title="Awesome" <?=is_rated(5, $rating)?> disabled >
	    </form>
	</p>
	
	<h2>Orders Up</h2>
	
	<form action="/vendor/fill_orders" method="post" id="ordersup">

	<?php if (count($orders) > 0): ?>
	<table id="ordersup">
		<tr>
			<th>Name</th>
			<th>Time Active</th>
			<th>Filled</th>
		</tr>
	
	<!--
		TODO Refresh this periodically with javascript
		TODO Submit filled orders via javascript
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
	<?php else: ?>
			<p>No orders found.</p>
	<?php endif; ?>
	<input type="submit" value="Update Orders">
	
	</form>
	
	<h2>Revenue</h2>
	
	<p>
		Today (so far): 
		$<?php echo $revenue['today'] == NULL ? '0.00' : $revenue['today'] ?>
		<br>
		Yesterday: $
		<?php echo $revenue['yesterday'] == NULL ? '0.00' : $revenue['yesterday']?>
		<br>
	</p>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
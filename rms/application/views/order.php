<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title><?=$vendor_name?> will be your <?=$vendor_type?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	
	<h1>You’ve Selected <?=$vendor_name?></h1>
	
	<img src="/images/<?=$vendor_id?>.jpg" alt="<?=$vendor_name?>" style="float:right;">
	<p>Thank you for selecting <?=$vendor_name?> as your <?=$vendor_type?> 
		for tonight. <?=$vendor_name?> will be with you momentarily.</p>
	
	<input type="submit" value="Cancel Order" disabled>
	
	<h2>Services you ordered:</h2>
	
	<ul>
	<?php foreach ($services as $service): ?>
		<li><?=$service['name']?></li>
	<?php endforeach; ?>
	</ul>
	
	<h3>Total: $<?=$total_price?></h3>
	
	<p class="small">This amount will be deducted directly from your account.</p>
	
	<form action="/meal/rate/<?=$vendor_id?>" method="post">
		Rate <?=$vendor_name?>:
	    <div id="stars-wrapper">
	        <input type="radio" name="newrate" value="1" title="Poor" />
	        <input type="radio" name="newrate" value="2" title="Meh" />
	        <input type="radio" name="newrate" value="3" title="Average" />
	        <input type="radio" name="newrate" value="4" title="Good" />
	        <input type="radio" name="newrate" value="5" title="Awesome" />
			<input type="hidden" name="order_id" value="<?=$order_id?>">
			<input type="submit" value="Rate">
	    </div>
	</form>
	
	<?php if ($vendor_type == 'busboy'): ?>
	<p>
		You have made all your vendor selections. 
		<a href="/meal">Review your orders.</a>
	</p>
	
	<?php else: ?>
	<p><a href="/meal">Next &raquo;</a></p>
	<?php endif; ?>

	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
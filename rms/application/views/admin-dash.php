<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Wolfkrow Admin Dashboard</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
	
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1>Wolfkrow Admin Dashboard</h1>
	
	<ul id="nav">
		<li><a href="/admin/applications">Review Vendor Applications</a></li>
		<li><a href="/admin/fees">Edit Transaction Fees</a></li>
	</ul>
	
	<h2>Revenue</h2>
	
	<p>
		Today (so far): 
		$<?php echo $rev['today'] == NULL ? '0.00' : $rev['today'] ?>
		<br>
		Yesterday: $<?php echo $rev['yesterday'] == NULL ? '0.00' : $rev['yesterday'] ?>
		<br>
	</p>
	
	<h2>Guest Meal Status</h2>
	
		<ul class="barGraph">
			<?php foreach ($meal_stats as $stat): ?>
			<li style="height: <?=$stat['height']?>px; 
				left: <?=$stat['left']?>px;">
				<?=$stat['value']?></li>
			<?php endforeach; ?>
		</ul>
		<ul class="legend">
			<li style="left: 18px;">None</li>
			<li style="left: 108px;">Host</li>
			<li style="left: 198px;">Waiter</li>
			<li style="left: 288px;">Cook</li>
			<li style="left: 378px;">Busboy</li>
		</ul>
		<p class="htitle"># of Active Orders</p>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
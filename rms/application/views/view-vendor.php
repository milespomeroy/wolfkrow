<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>View <?=$name?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<?php if($this->session->userdata('logged_in')): ?>
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>
	<?php endif; ?>
	
	<a href="/" id="logo">
		<img src="/images/logo.png" alt="Wolfkrow Diner">
	</a>
	
	<h1><?=$name?> — <?=ucwords($type)?></h1>
	
	<?php if ($this->session->userdata('user_type') == 'vendor'): ?>
	<p><a href="/vendor">&laquo; Back to Dashboard</a></p>
	<?php else: ?>
	<p><a href="/meal">&laquo; Back to Vendor List</a></p>
	<?php endif; ?>

	<p><img src="/images/<?=$id?>.jpg" alt="<?=$name?>"></p>

	Price: $<?=$price?><br>
	Avg Rating: ★★★☆☆<br> <!--
		TODO Rating system
	-->
	Your Rating: ★★★★☆<br>
	Currently Serving: <?=$orders?><br>
	<h3>Qualifications:</h3>
	<p><?=nl2br($qualifications)?></p>
	

	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
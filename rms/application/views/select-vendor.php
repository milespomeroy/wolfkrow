<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Select a <?=ucwords($type)?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>
	
	<div class="logout" >
	<?=$this->session->userdata('full_name')?> | <a href="/login/logout">Logout</a>
	</div>

	<h1>Select a <?=ucwords($type)?></h1>

	<?php if ($type == 'host'): ?>
	<p>Thank you for choosing Wolfkrow Diner. It’s the only choice in town.</p>
	
	<p>Begin your experience with us by selecting one of the hosts below to 
		seat you. You can stick with the standard package or select additional
		services.</p>
	<?php endif; ?>
		
		
	<div id="standard">
		<h2><?=ucwords($type)?> Standard Package</h2>
	
		<ul class="package">
		<?php foreach ($package as $item): ?>
			<li><?=$item->name?></li>
		<?php endforeach; ?>
		</ul>
	</div>	
	
	<div id="additional">
		<h2>Additional Services</h2>
		
		<?php foreach ($add_services as $serv): ?>
	
		<input type="checkbox" id="serv-<?=$serv->id?>" name="serv-<?=$serv->id?>">
		<label for="serv-<?=$serv->id?>">
		<?=$serv->name?> ($<?=$serv->price?>)
		</label>
		
		<?php endforeach; ?>
	</div>	
	
	<h2><?=ucwords($type)?></h2>
	
	<!-- TODO add ability to sort by factors
	<label for="sort-by">Sort by:</label>
	<select id="sort-by">
		<option>Rating</option>
		<option>Price</option>
	</select> 
	-->
	<?php foreach ($vendors as $vendor): ?>
	<div class="vendor">
		<img src="/images/<?=$vendor->id?>.jpg" alt="<?=$vendor->name?>">
		<h3><a href="/meal/vendor/<?=$vendor->id?>"><?=$vendor->name?></a></h3>
		<p>Rating: ★★★☆☆</p>
		<p>Price: $<?=$vendor->price?></p>
		
		<form action="/meal/order" method="post">
			<input type="hidden" name="vendor-id" value="<?=$vendor->id?>">
			<!--
				TODO Add additional services as hidden inputs using javascript
			-->
			<input type="submit" name="submit" value="Select <?=$vendor->name?>">
		</form>
	</div>
	<?php endforeach; ?>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>
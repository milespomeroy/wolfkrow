<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>Select a <?=ucwords($type)?></title>
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
	
	<h1>Select a <?=ucwords($type)?></h1>

	<?php if ($type == 'host'): ?>
	<p>Thank you for choosing Wolfkrow Diner. It’s the only choice in town.</p>
	
	<p>Begin your experience with us by selecting one of the hosts below to 
		seat you. You can stick with the standard package or select additional
		services.</p>
	<?php endif; ?>
		
		
	<div id="standard">
		<h2>Standard Package</h2>
	
		<ul class="package">
		<?php foreach ($package as $item): ?>
			<li><?=$item->name?></li>
		<?php endforeach; ?>
		</ul>
	</div>	
	
	<div id="additional">
		<h2>Additional Services</h2>
		
		<?php foreach ($add_services as $serv): ?>
	
		<input type="checkbox" id="serv-<?=$serv->id?>" name="serv-<?=$serv->id?>" disabled>
		<label for="serv-<?=$serv->id?>">
		<?=$serv->name?> ($<?=$serv->price?>)
		</label>
		
		<?php endforeach; ?>
	</div>	
	
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
		
		<form action="/meal/order" method="post" class="v-select">
			<input type="hidden" name="vendor-id" value="<?=$vendor->id?>">
			<!--
				TODO Add additional services as hidden inputs using javascript
			-->
			<input type="submit" name="submit" value="Select <?=$vendor->name?>">
		</form>
		
		<h3><?=$vendor->name?></h3>
		<p>			
			<form class="stars">
				<?php $rating = round($vendor->avg_rating); ?>
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
		    </form>
		</p>
		<p class="qualifications"><?=nl2br($vendor->qualifications)?></p>
		<p class="price"><strong>$<?=$vendor->price?></strong></p>
		
		
	</div>
	<?php endforeach; ?>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>

</body>
</html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<title>View <?=$name?></title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="/css/style.css" media="all">
</head>
<body>

	<h1><?=$name?> — <?=ucwords($type)?></h1>
	
	<p><a href="/meal">&laquo; Back to Vendor List</a></p>

	<p><img src="images/<?=$id?>.jpg" alt="<?=$name?>"></p>

	Price: $<?=$price?><br>
	Avg Rating: ★★★☆☆<br>
	Your Rating: ★★★★☆<br>
	Currently Serving: <?=$orders?><br>
	<h3>Qualifications:</h3>
	<p><?=nl2br($qualifications)?></p>
	

	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
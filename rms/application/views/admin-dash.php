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
	
	<h1>Wolfkrow Admin Dashboard</h1>
	
	<ul>
		<li><a href="/admin/applications">Review Vendor Applications</a></li>
		<li><a href="/admin/fees">Edit Transaction Fees</a></li>
	</ul>
	
	<h2>Revenue</h2>
	
	<p>
		Today (so far): 
		$0.00
		<br>
		Yesterday: $0.00
		<br>
	</p>
	
	<div id="footer">
		&copy; Wolfkrow Diner 2009 | Software Project for AJE by Miles Pomeroy
	</div>
	
</body>
</html>
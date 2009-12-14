<?php

// is_rated() 
// new function to check if rating and value are the same
function is_rated($value, $rating)
{
	if ($value == $rating)
	{
		return " disabled='disabled' checked ";
	}
	elseif ($rating != NULL) // if rating has a value than all radios disabled
	{
		return "disabled='disabled'";
	}
}
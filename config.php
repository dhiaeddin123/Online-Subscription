<?php
require_once('vendor/autoload.php');
$stripe = new \Stripe\StripeClient(
  'sk_test_51JRarhHo9V59lkqwcWpqsClbQ8CO6iRNhj686G3A6eJc22SqituyWD2Z0rBtJaFeLxYT0aIXozt410ALmVs3ilPJ00GjWAsSR8'
  );
  $stripe_plans=$stripe->plans->all(['limit' => 3]);
  
  
  $plans=$stripe_plans["data"];
$currency = "USD";
define('STRIPE_API_KEY', 'sk_test_51JRarhHo9V59lkqwcWpqsClbQ8CO6iRNhj686G3A6eJc22SqituyWD2Z0rBtJaFeLxYT0aIXozt410ALmVs3ilPJ00GjWAsSR8');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51JRarhHo9V59lkqw1DamQi0VqQJpcFMnaAQNSe13a09g46GAUNsLtwK9UuwP4WipkpqYsVnUZJkPFZq6IzR72xOO00adga5zkt');
// Database configuration  
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'subscription');
?>
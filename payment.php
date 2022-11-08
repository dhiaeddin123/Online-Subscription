<?php 
 // Include Stripe PHP library 

use Stripe\Stripe;

require_once 'vendor/autoload.php';

// Include configuration file  
require_once 'config.php'; 
// Retrieve stripe token and user info from the submitted form data 
$token  = $_POST['stripeToken']; 
$email = $_POST['email']??''; 
$first_name = $_POST['first_name']??''; 
$last_name = $_POST['last_name']??'';
	
      
// Get user ID from current SESSION 

$payment_id = $statusMsg = $api_error = ''; 
$ordStatus = 'error'; 

// Check whether stripe token is not empty 
if(($_POST['subscr_plan'])&&($_POST['stripeToken'])){ 
     
   
     
    // Plan info 
    $planID = $_POST['subscr_plan']; 
    $planInfo = $plans[$planID-1];
    $plan=$planInfo['plan_stripe_id'];
    $planName = $planInfo['name']; 
    $planPrice = $planInfo['price']; 
    $planInterval = $planInfo['interval']; 
    $product=$planInfo['product_stripe_id'];
    
   
    // Set API key 
    \Stripe\Stripe::setApiKey(STRIPE_API_KEY); 
   
    // Add customer to stripe 
    try {  
        $customer = \Stripe\Customer::create(array( 
            'email' => $email, 
            'source'  => $token 
        )); 
    }catch(Exception $e) {  
        $api_error = $e->getMessage();  
    } 
    if(empty($api_error) && $customer){  
     
        // Convert price to cents 
        $priceCents = round($planPrice*100); 

         
        if(empty($api_error) && $plan){ 
            // Creates a new subscription 
            try { 
                $subscription = \Stripe\Subscription::create(array( 
                    "customer" => $customer->id, 
                    "items" => array( 
                        array( 
                            "plan" => $plan, 
                        ), 
                    ),
                    'trial_period_days'  => 7
                )); 
            }catch(Exception $e) { 
                $api_error = $e->getMessage(); 
            } 
             
            if(empty($api_error) && $subscription) { 
                // Retrieve subscription data 
                $subsData = $subscription->jsonSerialize(); 
                // Check whether the subscription activation is successful 
               if($subsData['status'] == 'trialing'){ 
                    // Subscription info 
                    $subscrID = $subsData['id']; 
                    $custID = $subsData['customer']; 
                    $planID = $subsData['plan']['id']; 
                    $planAmount = ($subsData['plan']['amount']/100); 
                    $planCurrency = $subsData['plan']['currency']; 
                    $planinterval = $subsData['plan']['interval']; 
                    $planIntervalCount = $subsData['plan']['interval_count']; 
                    $created = date("Y-m-d H:i:s", $subsData['created']); 
                    $current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']); 
                    $current_period_end = date("Y-m-d H:i:s", $subsData['current_period_end']); 
                    $status = $subsData['status']; 
                     
                    // Include database connection file  
                 
                     
                    $ordStatus = 'success'; 
                    $statusMsg = 'Your Subscription Trial Period has been Started!'; 
                }
                else{ 
                    $statusMsg = "Subscription Trialing failed!"; 
                } 
            }else{ 
                $statusMsg = "Subscription creation failed! ".$api_error; 
            } 
        }else{ 
            $statusMsg = "Plan creation failed! ".$api_error; 
        } 
    }else{  
        $statusMsg = "Invalid card details! $api_error";  
    } 
}else{ 
    $statusMsg = "Error on form submission, please try again."; 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Payment</title>
</head>
<body>
   
<div class="container pt-4">
    <div class="status">
        <h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>
        <?php if(!empty($subscrID)){ ?>
            
            <h4>Subscription Information</h4>
            <p><b>Plan Name:</b> <?php echo $planName; ?></p>
            <p><b>Amount:</b> <?php echo $planPrice.' '.$currency; ?></p>
            <p><b>Plan Interval:</b> <?php echo $planInterval; ?></p>
            <p><b>Trial Period Start:</b> <?php echo $current_period_start; ?></p>
            <p><b>Trial Period End:</b> <?php echo $current_period_end; ?></p>
            <p><b>Status:</b> <?php echo $status; ?></p>
        <?php } ?>
    </div>
    <div class="row">
    <a href="index.php" class="btn-link">Back to Subscription Page</a>
    <a href="paymentinfo.php?subscription_id=<?=$subscrID?>&plan_name=<?=$planName?>&sub_id=<?=$subscription_id?>&plan_price=<?=$planPrice?>&plan_interval=<?=$planInterval?>" class="btn-link float-right">End Trial</a>
    </div>
   
</div>
</body>
</html>

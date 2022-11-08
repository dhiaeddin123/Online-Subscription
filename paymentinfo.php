<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

//set api key
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);
//end trial 
$subscription_id=$_GET['subscription_id']??'';
$subscription=\Stripe\Subscription::update($subscription_id, [
    'trial_end' => 'now',
]);
$ordStatus = 'error'; 
$statusMsg='';
// Retrieve subscription data 
$subsData = $subscription->jsonSerialize(); 
// Check whether the subscription activation is successful 

if($subsData['status'] == 'active'){ 
    // Subscription info 
    include_once 'dbConnect.php'; 
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
    $update = $db->query("UPDATE user_subscriptions SET `status` = {$status} WHERE stripe_subscription_id = {$subscrID}");
    $ordStatus = 'success'; 
    $statusMsg = 'Your Subscription Payment has been Successful!'; 
}else{ 
    $statusMsg = "Subscription activation failed!"; 
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
<div class="container">
    <div class="status">
        <h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>
        <?php if(!empty($subscrID)){ ?>
            <h4>Payment Information</h4>
            <p><b>Reference Number:</b> <?php echo $_GET['sub_id']; ?></p>
            <p><b>Transaction ID:</b> <?php echo $subscrID; ?></p>
            <p><b>Amount:</b> <?php echo $planAmount.' '.$planCurrency; ?></p>
			
            <h4>Subscription Information</h4>
            <p><b>Plan Name:</b> <?php echo $_GET['plan_name']; ?></p>
            <p><b>Amount:</b> <?php echo $_GET['plan_price'].' '.$currency; ?></p>
            <p><b>Plan Interval:</b> <?php echo $_GET['plan_interval']; ?></p>
            <p><b>Period Start:</b> <?php echo $current_period_start; ?></p>
            <p><b>Period End:</b> <?php echo $current_period_end; ?></p>
            <p><b>Status:</b> <?php echo $status; ?></p>
        <?php } ?>
    </div>
    <a href="index.php" class="btn-link">Back to Subscription Page</a>
</div>
</body>
</html>
<?php
require_once 'config.php'; 
$firstname=$_POST["Username"];
$email=$_POST["Email"];
$customer=$stripe->customers->create([
    'email' => $email,      //demo@gmail.com


'name' =>  $firstname,    //Deepak
  ]);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/plans.css">
    <title>Product Selection</title>
</head>
<body>
<div class="container">
    
        
<div class="d-flex justify-content-center container mt-5">
<?php
        foreach ($plans as $id => $plan) {
            ?>
        
    <div class="card p-3 bg-white mr-3 "><i class="fa fa-apple"></i>
        <div class="about-product text-center mt-2">
            <div>
                <h4><?=$plan['interval_count'] ?> <?=$plan['interval'] ?></h4>
                <h6 class="mt-0 text-black-50">Discord Nitro</h6>
            </div>
        </div>
        
        <div class="d-flex justify-content-between total font-weight-bold mt-4"><span>Price</span><span><?=substr_replace($plan['amount'], ",", strpos($plan['amount'], "999")+1 , 0).' '.$currency?></span></div>
    <div><a class="button-link" href="create_subscription.php?priceid=<?= $plan['id'] ?>&customerid=<?= $customer['id'] ?>">Select</a></div>
    </div>
    <?php } ?>
</div>
        

    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFIT LAB</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="homepage/homepage.css">
    
</head>
<body>
    <?php
session_start();

// 🔒 Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username']; 

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "logedin_users";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escape username for safety
$username = mysqli_real_escape_string($conn, $username);

// ✅ Always fetch balance fresh from DB
$sql = "SELECT balance , joiningcode , plan , statefordeposit FROM users_list WHERE Name = '$username'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $balance = $row['balance'];
    $joiningcode = $row['joiningcode'];
    $plan = $row['plan'];
   $feestate = $row['statefordeposit'];
} else {
    $balance = 0;
}

// 🟢 Handle withdraw POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['withdraw'])) {
    $amount = (float) $_POST['amount'];
    $account = trim($_POST['account']);
    $method  = trim($_POST['method']);
    $state   = trim($_POST['state']);  // ✅ fixed missing semicolon

    if ($balance < 100) {
        echo "<script>alert('Balance is less than 100. Minimum required is 100.');
          window.location.href = 'myaccount.php';
        </script>";
        exit();
    } else {
        if ($balance < $amount) {
            echo "<script>alert('Your entered amount is more than your balance.');
              window.location.href = 'myaccount.php';
            </script>";
        } else {
            $balance = $balance - $amount;

            // ✅ Update balance in users_list
            $update_sql = "UPDATE users_list SET balance = '$balance' WHERE Name = '$username'";
            
            if (mysqli_query($conn, $update_sql)) {

                // ✅ Insert withdrawal request into withdraw table
                $insert_sql = "INSERT INTO withdraw (`User_Name`, `Withdraw amount`, `Account NO`, `Account type`, `State`) 
                               VALUES ('$username', '$amount', '$account', '$method', '$state')";
                
                if (mysqli_query($conn, $insert_sql)) {
                    echo "<script>alert('Withdrawal is pending and will be received in your account shortly. Your new balance is $balance');</script>";
                } else {
                    echo "<script>alert('Error inserting withdrawal record: " . mysqli_error($conn) . "');</script>";
                }

                echo "<script>window.location.href = 'myaccount.php';</script>";

            } else {
                echo "<script>alert('Error updating balance: " . mysqli_error($conn) . "');
                window.location.href = 'myaccount.php';
                </script>";
            }
        }
        exit();
    }
}


?>
<?php
date_default_timezone_set("Asia/Karachi"); // Pakistan timezone

$username = $_SESSION['username'];


// Fetch user data
$sql = "SELECT balance, plan, last_update, next_update FROM users_list WHERE Name='$username'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$balance = $user['balance'];
$last_update = $user['last_update'];
$next_update = $user['next_update'];
 $plan = $user['plan'];
 if ($plan == 'plane1'&&$feestate=="Approved") {
    $increment = 25;
      echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('plane1')?.classList.remove('hidden');
    });</script>";
} elseif ($plan == 'plane2'&&$feestate=="Approved") {
    $increment = 50;
    echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('plane2')?.classList.remove('hidden');
    });</script>";
} elseif ($plan == 'plane3'&&$feestate=="Approved") {
    $increment = 100;
      echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('plane3')?.classList.remove('hidden');
    });</script>";
} elseif ($plan == 'plane4'&&$feestate=="Approved") {
    $increment = 400;
      echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('plane4')?.classList.remove('hidden');
    });</script>";
} elseif ($plan == 'plane5'&&$feestate=="Approved") {
    $increment = 800;
      echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('plane5')?.classList.remove('hidden');
    });</script>";
} else {
    $increment = 0; // Default if no plan selected
      echo "<script>window.addEventListener('DOMContentLoaded', function() {
        document.getElementById('planbtn')?.classList.remove('hidden');
    });</script>";
}

$today = date("l"); // Day name e.g. Sunday
$now = date("Y-m-d H:i:s");

// ---- Update balance logic ---- //
if ($today != "Sunday") {
    if ($next_update == null || $now >= $next_update) {
        // Add balance
        $balance += $increment;

        // Save back
        $last_update = $now;
        $next_update = date("Y-m-d H:i:s", strtotime("+24 hours"));

        $update_sql = "UPDATE users_list 
                       SET balance='$balance', last_update='$last_update', next_update='$next_update' 
                       WHERE Name='$username'";
        mysqli_query($conn, $update_sql);
    }
} else {
    // If Sunday → do not update, but schedule next update tomorrow 00:00
    if ($next_update == null || $now >= $next_update) {
        $next_update = date("Y-m-d 00:00:00", strtotime("tomorrow"));
        $update_sql = "UPDATE users_list 
                       SET next_update='$next_update' 
                       WHERE Name='$username'";
        mysqli_query($conn, $update_sql);
    }
}

// ---- Calculate remaining seconds ---- //
$remainingSeconds = max(0, strtotime($next_update) - time());
?>


  
   

     <script>
    document.addEventListener("DOMContentLoaded", () => {
      let remaining = <?php echo $remainingSeconds; ?>;

      function formatTime(seconds) {
          let h = Math.floor(seconds / 3600);
          let m = Math.floor((seconds % 3600) / 60);
          let s = seconds % 60;
          return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
      }

      function updateCountdown() {
          const el = document.getElementById("countdown");
          if (!el) return; // prevent null error

          if (remaining > 0) {
              el.innerText = formatTime(remaining);
              remaining--;
          } else {
              // reload when countdown ends
              location.reload();
          }
      }

      setInterval(updateCountdown, 1000);
      updateCountdown();
    });
  </script>
    <!-- Navbar -->
    <nav class="navbar nav navbar-expand-lg navbar-light ">
        <a class="navbar-brand logo text-light" href="#">PROFIT LAB</a>
        <div class=" justify-content-end">
            <button class="btn btn-outline-light my-2 my-sm-0" type="button">My_Account</button>
                  <a href="https://whatsapp.com/channel/0029VbAupDQ3wtbAoKuj4N3S" target="_blank" class="btn btn-outline-light my-2 my-sm-0">
    <i class="fas fa-headset"></i>
</a>
        </div>
    </nav>



       <div class="text-center  marqueeline">
        <marquee> <p>
    This web is Launch on 24 Aug 2025 <span style="margin-left:8em; padding:0%; margin:0%;"></span> kindly make 1 account in one device<span style="margin-left:8em;"></span> Make sure to not shere your password with any one <span style="margin-left:8em;"></span> In the case of any confusion contect with the sppourt manger of our team<span style="margin-left:8em;"></span> Withdraw once in a day donot submit 2nd withdrawal request until 1st approved
  </p></marquee>
    </div>
    <div class="col-12 col-md-12 col-lg-12">
        <!--card-->
<div class="card p-0 m-0 mt-2 mb-3" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">PROFIT LAB</h5>
    <h6 class="card-subtitle mb-2 text-body-secondary"><u>USER DETAILS</u></h6>
    <p class="card-text">  <h6> <ul id="userdet" class="p-3 "> 
            <li>User Name : <?php echo htmlspecialchars($username) ?> </li>
           
        </ul>
        </h6></p>
   
  </div>
</div>
<div class="card my-4">
      <div class="card-body">  
         <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center">
            <h3>Account Balance</h3>
        </div>
         <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center">
            <h3><?php echo $balance ?></h3>
  </div>
</div>
     
        </div>
        <!--cards--><div class="row">
           
            <div class="col cardhead " id="cd1">
                 <h6 class="card-title">Invite</h6>
             </div>
           
            <div class="col cardhead " id="earnbtn">
             <h6 class="card-title">Earning</h6>   
                    </div>
                
            <div class="col cardhead " id ="withdrawbutton">
                        <h6 class="card-title">Withdrawal</h6>
            </div>
            
        </div>
        <!--end of cards-->
        
<div id="invitetext" class=" col-12 col-md-12 col-lg-8 m-auto">
<div class="card p-3" style="width: auto">
<div class="card-body ">
    <h5 class="card-title">Invite Members</h5>
    <h6  class=" card-subtitle mb-2 text-body-secondary">Your refral code is <div class="card mx-5 bg-light text-info refcode">
      <div class="card-body d-flex refcode">  
      <div id="refcode" ><?php echo $joiningcode ?></div>
      <div id="buttonofcopyrefcode"  style="cursor:pointer;">
        <i class="fa-solid fa-copy"></i>
      </div>
    </div>
    </div>
    </div></h6>
    <img src="chainnetwork.webp" alt="">
    <p class="card-text"> <ul>
             <li>If you invite a person you will recive <b>10%</b> commision of his activation fee </li>
            </ul></p>
</div>
</div>
</div>
 <!----daily earning-->
 <div  id="dailyearn" class="dnone m-auto">
<div class="card" style="width: 18rem;">
  <img src="teamwork.jpeg" class="card-img-top" alt="...">
  <div class="card-body">
    <h4 class="card-title"><u>Your Daily Earning</u></h4>
    <p class="card-text text-center"> <div class="timercard ">
                       <h5> <div class="card-title"  id="countdown">00:00:00</div></h5>
            </div></p>
             <p class="card-text text-center"> <div class="timercard ">
                        <h5 class="card-title">Increment :<?php echo $increment ?></h5>
            </div></p>
            <div>Sunday will be off (According to Asian time zone)</div>

  </div>
</div>
 
</div>
 <!--end of daily earning-->
    <!--start of withdraw-->
     <div  id="withdraw" class="dnone ">
<div class="card" style="width: 18rem;">
  <img src="withdrawal.jpeg" class="card-img-top" alt="...">
  <div class="card-body">
    <h4 class="card-title"><u>Withdrawable Amount</u></h4>
    <p class="card-text text-center"> <div class="timercard ">
                        <h5  class="card-title"><?php 
                        if($balance>99){
                        echo $balance;
                   }else{
                            echo "0";
                        } ?></h5>
            </div></p>
            <form method="POST" class="form-container">


    <div class="form-group">
      <label for="amount">Withdrawal Amount</label>
      <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
    </div>

    <div class="form-group">
      <label for="account">Account No</label>
      <input type="text" id="account" name="account" placeholder="Enter account number" required>
    </div>
                        <input id="state" type="hidden" name="state" value="pending">
    <div class="form-group">
      <label for="method">Select Method</label>
      <select id="method" name="method" class="paymentmehtodwth" required>
        <option value="JazzCash">JazzCash</option>
        <option value="Easypaisa">Easypaisa</option>
      </select>
    </div>

   
  <button type="submit" name="withdraw">Submit</button>
  </form>

    
  </div>
</div>
 
</div>
                    </div>
    <!--end of withdraw-->
    <div id="wf" class="withdrawpage lg-6 col-12 md-12 dnone">
                         <style>
    .form-container {
      background: transparent;
      
      border-radius: 12px;
      width: 90%;
      max-width: 350px;
     
      margin-top:20px;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 15px;
      color: white;
      
    }
    .paymentmehtodwth{
      margin-left:-3px;
    }
    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-size: 14px;
      margin-bottom: 5px;
      color: white;
    }
  

    input, select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #aaa;
      font-size: 16px;
    }

    form button {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      background: #4CAF50;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    form button:hover {
      background: #45a049;
    }

    @media (max-width: 768px) {
      .form-container {
        width: 95%;
      }
    }
  </style>
  
</div>

        <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center mt-3">
            <h3 ><u>Active Plan</u></h3>
    </div>

<div class="card my-3 hidden" id="plane1">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li>Activation Fee : 400 Rs</li>
        <li>Daily Earning : 25 RS</li>
        <li>Validity : 180 days</li>
        <li class="mb-3">Total Profit : 4,500</li>
        <div class="m-auto bg-info text-center p-2 "><?php echo $feestate; ?></div>
    </ul>
    </p></div></div></div>

  <div class="card my-3 hidden" id="plane2">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li>Activation Fee : 800 Rs</li>
        <li>Daily Earning : 50 RS</li>
        <li>Validity : 180 days</li>
        <li class="mb-3">Total Profit : 9000</li>
        <div class="m-auto bg-info text-center p-2 "><?php echo $feestate; ?></div>
    </ul>
    </p></div></div></div>


  <div class="card my-3 hidden" id="plane3">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li>Activation Fee : 1600 Rs</li>
        <li>Daily Earning : 100 RS</li>
        <li>Validity : 180 days</li>
        <li class="mb-3">Total Profit : 18000</li>
        <div class="m-auto bg-info text-center p-2 "><?php echo $feestate; ?></div>
    </ul>
    </p></div></div></div>

    
  <div class="card my-3 hidden" id="plane4">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li>Activation Fee : 6400 Rs</li>
        <li>Daily Earning : 400 RS</li>
        <li>Validity : 180 days</li>
        <li class="mb-3">Total Profit : 72000</li>
        <div class="m-auto bg-info text-center p-2 "><?php echo $feestate; ?></div>
    </ul>
    </p></div></div></div>


  <div class="card my-3 hidden" id="plane5">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li>Activation Fee : 12800 Rs</li>
        <li>Daily Earning : 800 RS</li>
        <li>Validity : 180 days</li>
        <li class="mb-3">Total Profit : 144000</li>
        <div class="m-auto bg-info text-center p-2 "><?php echo $feestate; ?></div>
    </ul>
    </p></div></div></div>

     <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center  ">
          
    </div>
     <div class="col-12 col-md-12 col-lg-12 d-flex justify-content-center  mb-1">
     <!-- active plane--->
      
<!---------------------end of active plane----->
     <button type="submit" id="planbtn" class="btn btn-success mb-4 hidden" onclick="openplan()">Buy Plan</button>
    </div>
   
   <!-- Footer -->
        <div class="footer">
       <p style="text-align:center; font-size:14px; margin-top:20px;">
    © 2025 <strong>Zee Coders</strong>. All rights reserved. <br>
    Designed & Developed with ❤️ by <strong>Zeeshan Jameel</strong>.
</p>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="homepage/homepage.js"></script>
</body>
</html>

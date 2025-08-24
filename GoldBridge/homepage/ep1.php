<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFIT LAB </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="homepage.css">
  
</head>
<body>


    <?php
    $conn = new mysqli("localhost", "root", "", "logedin_users");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    session_start(); //n 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["myValue"])) {
        $receivedValue = $_POST["myValue"];
            $_SESSION["myValue"] = $receivedValue;
            if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Get from session
            }

          
}

//data base file uploding
if (isset($_POST['upload'])) {
    if (!isset($_SESSION['username'])) {
        die("No username in session.");
    }
    $username = $_SESSION['username'];

    // Get plan value from the session
    $receivedValue = $_SESSION['myValue'] ?? '';

    // Check if both plan and image are provided
    if (empty($receivedValue)) {
        die("No plan selected. Please select a plan first.");
    }
  $trxtid = $_POST['TrxtID'] ?? '';
   $state   = trim($_POST['statefordeposit']);
        if (empty($trxtid)) {
            die("No Trxt ID provided.");
        }
    if (empty($_FILES['image']['name'])) {
        die("No image uploaded. Please upload an image.");
    }

    $imageName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $targetPath = "images/" . basename($imageName);

    if (move_uploaded_file($tmpName, $targetPath)) {
        // Only update if both conditions are met
        $sql = "UPDATE users_list 
                SET images = '$targetPath', plan = '$receivedValue' , trxtID = '$trxtid', Statefordeposit ='$state'
                WHERE Name = '$username'";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../myaccount.php");
            exit();
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}

//end of file uploading


        if ($receivedValue == "plane1") {
           
        echo "<script>
    document.addEventListener(`DOMContentLoaded`, function() {
       eps1(); // Call your function here
   });
   
</script>";
    
        }
         else if  ($receivedValue == "plane2") {
           
        echo "<script>
    document.addEventListener(`DOMContentLoaded`, function() {
       ep2(); // Call your function here
   });
   
</script>";
    
        } else if  ($receivedValue == "plane3") {
           
        echo "<script>
    document.addEventListener(`DOMContentLoaded`, function() {
       ep3(); // Call your function here
   });
   
</script>";
    
        } 
         else if  ($receivedValue == "plane4") {
          
        echo "<script>
    document.addEventListener(`DOMContentLoaded`, function() {
       ep4(); // Call your function here
   });
   
</script>";
    } 
     else if  ($receivedValue == "plane5") {
          
        echo "<script>
    document.addEventListener(`DOMContentLoaded`, function() {
       ep5(); // Call your function here
   });
   
</script>";
    } 
    }
 else {
    
echo "<script>
    let a = 1;
    if (a === 1) {
        if (confirm('You did not  select any plan. Kindly go to the plans section and select a page')) {
            a++;
        }
    }
    document.getElementsByTagName('body')[0].classList.add('hidden');
</script>";


}
?>  
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg nav navbar-light ">
        <a class="navbar-brand text-light" href="#">PROFIT LAB</a>
        <div class=" justify-content-end">
            <button class="btn btn-outline-light my-2 my-sm-0" type="button" onclick="openmyaccount()">My_account</button>
            <a href="https://whatsapp.com/channel/0029VbAupDQ3wtbAoKuj4N3S" class="btn btn-outline-light my-2 my-sm-0">
                <i class="fas fa-headset"></i> 
</a>
        </div>
    </nav>
   
    <!-- Marquee -->
    <div class="text-center  marqueeline">
          <marquee> <p>
    This web is Launch on 24 Aug 2025 <span style="margin-left:8em; padding:0%; margin:0%;"></span> kindly make 1 account in one device<span style="margin-left:8em;"></span> Make sure to not shere your password with any one <span style="margin-left:8em;"></span> In the case of any confusion contect with the sppourt manger of our team<span style="margin-left:8em;"></span> Withdraw once in a day donot submit 2nd withdrawal request until 1st approved
  </p></marquee>
    </div>

    <!------------ep1------------->
   <div class="card">
  <h5 class="card-header">PROFIT LAB</h5>
  <div class="card-body ">
    <div id="cardbody" class="visibility">
    <h3 class="card-title "><span class="epheading" id="plane_name"></span></h3>
    <p class="card-text">
    <ul class="ep1ul">
        <li id="dpositpay"></li>
        <li id="dayearn"></li>
        <li id="validation"></li>
    </ul>
    </p>
    <h3 class="card-title "><span class="epheading">Payment Process</span></h3>
    <p>Send Your Activation Fee to  </p>
    
    <!--card foradid--->
    
<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h4>JazzCash</h4>
    
    <div class="d-flex justify-content-between align-items-center">
      <span>Account NO</span> 
      <div id="accountno">03030271955</div>
      <div id="buttonofcopyaccountno" class="ms-2" style="cursor:pointer;">
        <i class="fa-solid fa-copy"></i>
      </div>
    </div>

    <br>
    <p class="card-text">Account Holder: Zeeshan Malik</p>
  </div>
</div>
    <!---card end-->
   
    <form action="" method="post" enctype="multipart/form-data">
    <input type="file" accept=".png, .jpg, .jpeg" name="image" id="payproof" required>
    <input type="text" id="trxt" name="TrxtID" placeholder="Enter Trxt ID Please" required>
    <input id="statefordeposit" type="hidden" name="statefordeposit" value="pending">
    <button type="submit" name="upload" class="btn epbtn btn-primary">submit</button>
    </form>
   </div>
  </div>
   
</div>

    <!-- end of ep-->
    <!-- Footer -->
        <div class="footer ft">
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
    <script src="homepage.js" defer></script>
    
</body>
</html>

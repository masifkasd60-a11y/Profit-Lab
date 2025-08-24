<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PROFIT LAB</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="homepage.css">
    
</head>
<body>
    <?php

?>
    <!-- Navbar -->
    <nav class="navbar nav navbar-expand-lg navbar-light ">
        <a class="navbar-brand text-light" href="#">PROFIT LAB</a>
        <div class=" justify-content-end">
            <button class="btn btn-outline-light my-2 my-sm-0" type="button"onclick="openmyaccount()">My_Account</button>
             <a href="https://whatsapp.com/channel/0029VbAupDQ3wtbAoKuj4N3S" target="_blank" class="btn btn-outline-light my-2 my-sm-0">
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

    <!-- Cards Section -->
    <div class="container">
        

        <!-- Earning Plans Section -->
       <div class="containerp"> <div class="row my-5 ">
            <div class="col-md-6">
                <h3>Earning Plans</h3>
                <p>Choose from our various earning plans that suit your needs. and click this ot activate </p>
            </div>
            <hr>
            <!--start of plane-->
            <div class="col-12 ep py-3 my-3 row">
                <form action="ep1.php" method="Post">
                    <input type="hidden" name="myValue" value="plane1">
                <button id="epbtn" onclick="epshow()" class="earningplanebtn row">
                 <div class="circle"></div>
                <div class="col-6">
                   
               <h4>   Starter Plan</h4>
               <h6>Deposit 400 RS <br> Daily Profit :25Rs<br>Days:180</h6>
               </div>
               <div class="col-5 float-end img">
                <img src="bnimg.webp" class="img-fluid img ">
               </div>
                </button></form>
            </div>
            <!----plane end---->
         <!--start of plane 2-->
            <div class="col-12 ep py-3 my-3 row">
                <form action="ep1.php" method="Post">
                    <input type="hidden" name="myValue" value="plane2">
                <button id="epbtn" onclick="epshow()" class="earningplanebtn row">
                 <div class="circle"></div>
                <div class="col-6">
                   
               <h4>   Basic Plan</h4>
               <h6>Deposit 800 Rs <br> Daily Profit :50 RS<br>Days:180</h6>
               </div>
               <div class="col-5 float-end img">
                <img src="bnimg.webp" class="img-fluid img ">
               </div>
                </button></form>
            </div>
            <!----plane end---->
            <!--start of plane 3-->
            <div class="col-12 ep py-3 my-3 row">
                <form action="ep1.php" method="Post">
                    <input type="hidden" name="myValue" value="plane3">
                <button id="epbtn" onclick="epshow()" class="earningplanebtn row">
                 <div class="circle"></div>
                <div class="col-6">
                   
               <h4>   Bronze Plan</h4>
               <h6>Deposit 1600 RS <br> Daily Profit :100 RS<br>Days:180</h6>
               </div>
               <div class="col-5 float-end img">
                <img src="bnimg.webp" class="img-fluid img ">
               </div>
                </button></form>
            </div>
            <!----plane end---->
             <!--start of plane 4-->
            <div class="col-12 ep py-3 my-3 row">
                <form action="ep1.php" method="Post">
                    <input type="hidden" name="myValue" value="plane4">
                <button id="epbtn" onclick="epshow()" class="earningplanebtn row">
                 <div class="circle"></div>
                <div class="col-6">
                   
               <h4>   Ultimate Plan</h4>
               <h6>Deposit 6400 RS <br> Daily Profit :400 RS<br>Days:180</h6>
               </div>
               <div class="col-5 float-end img">
                <img src="bnimg.webp" class="img-fluid img ">
               </div>
                </button></form>
            </div>
            <!----plane end---->
             <!--start of plane 5-->
            <div class="col-12 ep py-3 my-3 row">
                <form action="ep1.php" method="Post">
                    <input type="hidden" name="myValue" value="plane5">
                <button id="epbtn" onclick="epshow()" class="earningplanebtn row">
                 <div class="circle"></div>
                <div class="col-6">
                   
               <h4>   Standard Plan</h4>
               <h6>Deposit 12800 RS <br> Daily Profit :800 RS<br>Days:180</h6>
               </div>
               <div class="col-5 float-end img">
                <img src="bnimg.webp" class="img-fluid img ">
               </div>
                </button></form>
            </div>
            <!----plane end---->
            
            
        </div>
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
    <script src="homepage.js"></script>
</body>
</html>

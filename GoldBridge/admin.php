<?php
session_start();

// Database connection (self-contained)
$servername = "localhost";  // usually 'localhost'
$db_username = "root";      // your DB username
$db_password = "";          // your DB password
$db_name = "logedin_users"; // your DB name

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle login
$correctPassword = "Awanstech#1646";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'] ?? '';

    if ($password === $correctPassword) {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "❌ Password does not match!";
    }
}

// Fetch pending withdrawals ONLY after login
$result = null;
if (isset($_SESSION['logged_in'])) {
    $sql = "SELECT * FROM withdraw WHERE state = 'pending'";
    $stmt = $conn->prepare($sql);
    
    $stmt->execute();
    $result = $stmt->get_result();
}
?><?php
if (isset($_POST['approve']) && isset($_POST['withdraw_id'])) {
    $withdraw_id = $_POST['withdraw_id'];
    approveUser($withdraw_id);
}

function approveUser($username) {
    global $conn;
    $stmt = $conn->prepare("UPDATE withdraw SET state='approved' WHERE User_Name = ?");
    $stmt->bind_param("s", $username);  // "s" since User_Name is a string
    if ($stmt->execute()) {
        echo "<script>alert('Withdrawal approved!'); window.location='';</script>";
    } else {
        echo "<script>alert('Error approving withdrawal');</script>";
    }
}?>
<?php 
if (isset($_POST['depositeaprove']) && isset($_POST['depid'])) {
    $depid = $_POST['depid'];
    approveUserdeposit($depid);
}function approveUserDeposit($depid) {
    global $conn;

    // 1️⃣ Get ref_code and plan for this deposit
    $stmt = $conn->prepare("SELECT ref_code, plan FROM users_list WHERE Name = ?");
    $stmt->bind_param("s", $depid);
    $stmt->execute();
    $stmt->bind_result($leadRefCode, $plan);
    if (!$stmt->fetch()) {
        echo "<script>alert('User not found');</script>";
        $stmt->close();
        return;
    }
    $stmt->close();

    // 2️⃣ Get leader's balance directly where Joiningcode = ref_code
    $stmt = $conn->prepare("SELECT balance FROM users_list WHERE Joiningcode = ?");
    $stmt->bind_param("s", $leadRefCode);
    $stmt->execute();
    $stmt->bind_result($leaderBalance);
    if (!$stmt->fetch()) {
        echo "<script>alert('Leader not found');</script>";
        $stmt->close();
        return;
    }
    $stmt->close();

    // 3️⃣ Determine activation fee based on plan
    $activationFee = 0;
    switch ($plan) {
        case "plane1": $activationFee = 400; break;
        case "plane2": $activationFee = 800; break;
        case "plane3": $activationFee = 1600; break;
        case "plane4": $activationFee = 6400; break;
        case "plane5": $activationFee = 12800; break;
    }

    $bonus = $activationFee * 0.10; 
    $newBalance = $leaderBalance + $bonus;

    // 4️⃣ Update leader balance directly
    $stmt = $conn->prepare("UPDATE users_list SET balance = ? WHERE Joiningcode = ?");
    $stmt->bind_param("ds", $newBalance, $leadRefCode);
    if ($stmt->execute()) {
        $stmt->close();

        // ✅ Approve deposit for the user
        $stmt = $conn->prepare("UPDATE users_list SET Statefordeposit = 'Approved' WHERE Name = ?");
        $stmt->bind_param("s", $depid);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Deposit approved and leader balance updated!'); window.location='';</script>";
    } else {
        $stmt->close();

        // ❌ If balance update fails, still approve deposit
        $stmt = $conn->prepare("UPDATE users_list SET Statefordeposit = 'Approved' WHERE Name = ?");
        $stmt->bind_param("s", $depid);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Deposit approved but leader balance not updated!'); window.location='';</script>";
    }
}

?>
<?php 
$deposit_sql = "SELECT Name, ref_code, images, plan, trxtID 
                FROM users_list 
                WHERE Statefordeposit = 'pending'";
$deposit_result = $conn->query($deposit_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
  <style>
    @media only screen and (max-width: 600px) {
    body {
        font-size: 8px;
    }
}
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }
    .container-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 800px;
      text-align: center;
    }
    .lock {
      font-size: 50px;
      color: #2a5298;
    }
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 15px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }
    button {
      background: #2a5298;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background: #1e3c72;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
    .welcome {
      font-size: 20px;
      font-weight: bold;
      color: #2a5298;
      margin-bottom: 20px;
    }
    .logout-btn {
      margin-top: 20px;
      display: inline-block;
      background: red;
      padding: 10px 18px;
      border-radius: 8px;
      color: white;
      text-decoration: none;
    }
    .logout-btn:hover {
      background: darkred;
    }
    .tablecontainer{
        overflow-y: auto; /* vertical scroll */
      overflow-x: auto; /* horizontal scroll if needed */
    }
    .card {
        margin-bottom: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    .card img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }
  </style>
</head>
<body>

<div class="container-box">
    <?php if (!isset($_SESSION['logged_in'])): ?>
        <div class="lock">🔒</div>
        <h2>Admin Panel Login</h2>
        <form method="POST" action="">
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
    <?php else: ?>
        <div class="welcome">✅ Welcome to Admin Panel</div>
        <h3 class="mb-4">Pending Withdrawals</h3>
        <div class="table-responsive" style="border:1px solid black;">
            <table  class="table table-bordered table-striped ">
                <thead class="table-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Amount</th>
                        <th>Account No</th>
                        <th>Account Type</th>
                        <th>State</th>
                        <th>Approve</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['User_Name']); ?></td>
                            <td><?= htmlspecialchars($row['Withdraw amount']); ?></td>
                            <td><?= htmlspecialchars($row['Account NO']); ?></td>
                            <td><?= htmlspecialchars($row['Account type']); ?></td>
                            <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($row['State']); ?></span></td>
                            <td>
                               <form method="POST">
   <input type="hidden" name="withdraw_id" value="<?= $row['User_Name'] ?>"> <!-- assuming 'id' is PK -->
    <button type="submit" name="approve" class="btn btn-info">
        <i class="fa-solid fa-person-circle-check"></i>
    </button>
</form>

                            </td>
                           
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No pending withdrawals found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
            
        </div>
        
       <div class="container my-5" style="border:1px solid black">
    <h2 class="text-center mb-4">📌 Pending Deposits</h2>
    <div class="row">
        <?php if ($deposit_result && $deposit_result->num_rows > 0): ?>
            <?php while ($row = $deposit_result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5 class="card-title"><?= htmlspecialchars($row['Name']); ?></h5>
                        <p><strong>Referral Code:</strong> <?= htmlspecialchars($row['ref_code']); ?></p>
                        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($row['trxtID']); ?></p>
                        <p><strong>Plan:</strong> <?= htmlspecialchars($row['plan']); ?></p>
                        <div class="text-center">
                            <img src="homepage/<?= htmlspecialchars($row['images']); ?>" alt="Deposit Proof" class="img-fluid rounded">
                        <form method="post">
                            <input type="hidden" name="depid" value="<?= $row['Name'] ?>">
                            <button type="submit" name="depositeaprove" class="mt-3">Approve</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">No pending deposits found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>



        <a href="?logout=1" class="logout-btn">Logout</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>

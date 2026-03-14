<?php

session_start();
include('server/connection.php');
if(!isset($_SESSION['logged_in'])){
    header('location:login.php');
    exit;
}
if(isset($_GET['logout']) && $_GET['logout'] == 1){
    if(isset($_SESSION['logged_in'])){
        // Clear cart session data
        unset($_SESSION['cart'], $_SESSION['total'], $_SESSION['quantity']);

        // Optionally clear cart data from DB if exists
        if(isset($_SESSION['user_id'])){
            $user_id = (int) $_SESSION['user_id'];
            if($stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?")){
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->close();
            }
            if($stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")){
                $stmt->bind_param('i', $user_id);
                $stmt->execute();
                $stmt->close();
            }
        }

        unset($_SESSION['logged_in'], $_SESSION['user_email'], $_SESSION['user_name'], $_SESSION['user_id']);

        session_regenerate_id(true);
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');

        header('location:login.php');
        exit;
    }
}



if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];
    $user_email = $_SESSION['user_email'];

    // Check if passwords match
    if ($password !== $confirmpassword) {
        header('location:account.php?error=passwords dont match');
    } 
    // Check if password length is sufficient
    elseif (strlen($password) < 6) {
        header('location:account.php?error=password must be at least 6 characters');
    } 
    // If no errors, proceed to update password
    else {
        // Securely hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare the statement to update the password
        $stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
        $stmt->bind_param('ss', $hashed_password, $user_email);

        // Execute the query and handle the result
        if ($stmt->execute()) {
            header('location:account.php?message=password has been updated successfully');
        } else {
            header('location:account.php?error=could not update password');
        }
    }
}

// get orders
if(isset($_SESSION['logged_in'])){
    $user_id=$_SESSION['user_id'];
    $stmt=$conn->prepare("SELECT * FROM orders WHERE user_id=? ");
    $stmt->bind_param('i',$user_id);
    $stmt->execute();
    $orders=$stmt->get_result();
}

?>



<?php include('layouts/header.php');?>

      <section class="my-5 py-5">
        <div class="row container mx-auto">
            <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 c0l-sm-12">
            <p class="text-center" style="color:#d3acb3"><?php if(isset($_GET['register_success'])){echo $_GET['register_success'];}?></p>
            <p class="text-center" style="color:#d3acb3"><?php if(isset($_GET['login_success'])){echo $_GET['login_success'];}?></p>
           
                <h3 class="font-weight-bold">Account Info</h3>
                
                <div class="account-info">
                    <p>Name: <span><?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name'];}?></span></p>
                    <p>Email: <span><?php if(isset($_SESSION['user_email'])) {echo $_SESSION['user_email'];}?></span></p>
                    <p><a href="#orders" id="orders-btn">Your orders</a></p>
                    <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 c0l-sm-12">
                <form action="account.php"id="account-form" method="POST">
                    <p class="text-center" style="color:red"><?php if(isset($_GET['error'])){echo $_GET['error'];}?></p>
                    <p class="text-center" style="color:#d3acb3"><?php if(isset($_GET['message'])){echo $_GET['message'];}?></p>
                    <h3>Change Password</h3>
                    
                    <div class="form-group bro">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" id="account-password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" name="confirmpassword" class="form-control" id="account-password-confirm" placeholder="Password">
                    </div>
                    <div class="form-group">
                       <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn"> 
                    </div>
                </form>
            </div>
        </div>
    </section>




<!-- Orders -->
    <section id="orders" class="container my-5 py-5">
        <div class="container mt-2">
            <h2 class="font-weight-bold text-center" style="border-bottom: 3px solid #d3acb3; ">Your Orders</h2>
        </div>
        <table class="mt-5 pt-5 mx-auto w-100 orders">
            <tr>

                <th>Order cost</th>
                <th >Order status</th>
                <th>Order Date</th>
                <th>Order Details</th>
            </tr>
            <?php while($row=$orders->fetch_assoc()){?>
                    <tr>
                        
                       

                        
                        <td>
                            <span><?php echo $row['order_cost'];?></span>
                        </td>
                        <td>
                            <span><?php echo $row['order_status'];?></span>
                        </td>
                        <td>
                            <span><?php echo $row['order_date'];?></span>
                        </td>
                        <td>
                            <form action="order_details.php" method="POST">
                             <input type="hidden" name="order_status" value="<?php echo $row['order_status'];?>">   
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id'];?>">
                                <input type="submit" name="order_details_btn" class="btn order-details-btn" value="details">
                            </form>
                        </td>
                        
                    </tr>
            <?php }?>
            
               
        </table>

       
     </section>

      
     <?php include('layouts/footer.php');?>
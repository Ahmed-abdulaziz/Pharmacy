<?php
ob_start(); 

$pagetitle = 'Home';
include "init.php";
    if(isset($_SESSION['username'])){
 

?>
       
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Home</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                        <?php
                        
                        if(isset($_POST['add'])){

                            $productName = $_POST['name'];
                            $userPhone = $_POST['phone'];
                            $Quantity = $_POST['Quantity'];
                    
                            $check = checkitem('phone' , 'users' , "WHERE phone =$userPhone");  // check if phone in database or not have order completed or not
                           
                            if($check >= 1){
                    
                                
                                $row = select('Quantity' ,'products' , "WHERE name = '$productName'");
                                                
                                if($row['Quantity'] < 1){
                                    echo '<div class ="alert alert-danger">Sorry Product is empty</div>';
                                    header("Refresh:1; url=index.php");
                                    exit();
                                }else{
                    
                                $row = select('Quantity' ,'products' , "WHERE name = '$productName'");
                                if($row['Quantity'] < $Quantity){
                                    echo '<div class ="alert alert-danger">Sorry you have only '.$row['Quantity'].' of '.$productName.' </div>';
                                    header("Refresh:1; url=index.php");
                                    exit();  
                                }else{
                    
                                $row = select('id,name' ,'users' , "WHERE phone = $userPhone");
                                $user_id=$row['id'];
                                $username=$row['name'];
                            $row = select('id,name,Quantity,price' ,'products' , "WHERE name = '$productName'");
                    
                                $product_id=$row['id'];
                                $row['Quantity']= $row['Quantity'] -  $Quantity;
                                $Quant =$row['Quantity'];
                    
                                $stmt=$con->prepare("UPDATE products SET  Quantity = ? WHERE id =?");
                                $stmt->execute(array( $Quant  , $product_id));
                    
                                $stmt=$con->prepare("INSERT INTO offer (user_id ,product_name ,userphone , Quantity ,date) VALUES (:zuser_id ,:zproduct_name,:zuserphone ,:zQuantity , now() )");
                                $stmt->execute(array(
                                'zuser_id' => $user_id,
                                'zproduct_name'   =>  $productName,
                                'zuserphone' => $userPhone,
                                'zQuantity' => $Quantity,
                                
                                ));
                                
                                header("Location: offer.php");
                                exit();
                            }
                        }
                        }
                            else{
                                echo "<div class ='alert alert-success container'>Soory Phone is not in database</div>";
                                header("Refresh:2; url=index.php");
                                exit();
                            }
                        }
                        ?>
                        <div class="row" style="text-align: center;">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                    <?php 
                                        $check = checkitem('*' , 'users');
                                        echo $check;
                                    ?>
                                    <br>
                                        Users
                                 

                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="users.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">

                                    <?php 
                                        $check = checkitem('*' , 'orders' , "WHERE completed = 0");
                                        echo $check;
                                    ?>
                                     <br>
                                        Pending orders
                                 
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="orders.php?do=pending">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                    <?php 
                                        $check = checkitem('*' , 'orders' , "WHERE completed = 1");
                                        echo $check;
                                    ?>
                                     <br>
                                     completed orders
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="orders.php?do=completed">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">
                                    <?php 
                                        $check = checkitem('*' , 'products');
                                        echo $check;
                                    ?>
                                     <br>
                                     Products
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="products.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                    <?php 
                                        
                                        $rows = selectall('priceofQuantity' , 'orders_deteils' , "WHERE completed = 1");
                                        $total =0;
                                        foreach($rows as $row){
                                            $total = $total + $row['priceofQuantity'] ;
                                        }
                                        echo '$'. $total;
                                    ?>
                                     <br>
                                     All Profit
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="orders.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                    
                                </div>
                                
                                
                            </div>
                            <div class="col-xl-6 col-md-6">
                            <div class="card bg-light  mb-4">
                            <div class="card-header text-white bg-dark ">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        Pending Admin
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas>
                                                <?php
                                                    $check =checkitem('grouped' ,'admin',"WHERE grouped = 0");
                                                    if($check > 0){
                                                ?>
                                    <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Controls</th>
                                                       
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                            $rows = selectall('*' ,'admin',"WHERE grouped = 0");
                                                                foreach($rows as $row){
                                                                    
                                                        ?>
                                                        <tr>
                                                        <th scope="row"><?php echo $row['id'];?></th>
                                                        <td><?php echo $row['username'];?></td>
                                                        <td><?php echo $row['email'];?></td>
                                                        <td>
                                               <a href="admin.php?do=make&id=<?php echo $row['id']?>"> <button class="btn btn-primary">Make Admin</button></a>
                                               <a href="admin.php?do=delete&id=<?php echo $row['id']?>">  <button class="btn btn-danger">Delete</button></a>

                                             </td>
                                                       
                                                        </tr>
                                                                <?php }?>
                                                    </tbody>
                                                    </table>
                                                                <?php }else
                                                                    echo "There is no admin waiting";
                                                                ?>
                                    </div>
                                </div>
                            
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card  mb-4">
                                    <div class="card-header text-white bg-dark">
                                        <i class="fas fa-chart-area mr-1"></i>
                                        Make Offer
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas>

                                    <form class="form" method="post" role="form" autocomplete="off">
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Product Name</label>
                                    <div class="col-lg-9">
                                    <select class="js-example-basic-single form-group btn-block" name="name">
                                        <?php 
                                            $rows = selectall('*','products');
                                            foreach($rows as $row){
                                        ?>
                                                <option value="<?php echo $row['name']?>"><?php echo $row['name']?></option>
                                            <?php }?>
                                     </select>
                                   
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">User Phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="phone" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Quantity</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="Quantity" type="number">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Make Offer</button>

                                    </div>
                                </div>
                            </form>
                                </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header  text-white bg-dark">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        Product quantity less than or equal 5 
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas>
                                                <?php
                                                    $check =checkitem('Quantity' ,'products',"WHERE Quantity <= 5");
                                                    if($check > 0){
                                                ?>
                                    <table class="table table-dark">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">ID</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Quantity</th>
                                                       
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                            $rows = selectall('*' ,'products',"WHERE Quantity <= 5");
                                                                foreach($rows as $row){
                                                                    
                                                        ?>
                                                        <tr>
                                                        <th scope="row"><?php echo $row['id'];?></th>
                                                        <td><?php echo $row['name'];?></td>
                                                        <td><?php echo $row['Quantity'];?></td>
                                                       
                                                        </tr>
                                                                <?php }?>
                                                    </tbody>
                                                    </table>
                                                                <?php }else
                                                                    echo "No products are about to expire";
                                                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card text-white mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Orders
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                             
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'orders',"ORDER BY id DESC LIMIT 10");
                                                foreach($rows as $row){
                                                    $ID =$row['user_id'];
                                                    $name =select('name' ,'users' , "WHERE id =$ID ");
                                            ?>
                                            <a>
                                            <tr>
                                                <td><?php echo $row['id'];?></td>
                                                <td><a href="order_deteils.php?id=<?php echo $row['user_id'];?>&orderid=<?php echo $row['id'];?>"><?php echo $name['name'];?></a></td>
                                                <td><?php echo $row['payment'];?></td>
                                                <td><?php echo $row['date'];?></td>
                                                <td>
                                                    
                                                    <?php
                                                        if($row['completed'] == 0){
                                                            $status = 'pending'; 
                                                        }else{
                                                            $status = 'Complete';
                                                        }
                                                         echo $status;?>
                                                </td>
                                              
                                              
                                            </tr>
                                            </a>
                                                <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
           <?php
                include "assets/includes/footer.php";
                ob_end_flush();
        }else{
            header("Location: login.php");
        }
           ?>

<?php 
ob_start();
    $pagetitle = 'Orders';
    include "init.php";
    if(isset($_SESSION['username'])){
   
?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Orders</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Orders</li>
                        </ol>
                        <?php 
                        if(isset($_GET['do'])){
                    if($_GET['do']== 'add'){

                        if(isset($_POST['add'])){

                          

                            $phone=$_POST['phone'];
                            $name=$_POST['name'];
                            $Quantity=$_POST['Quantity'];
                            $payment = $_POST['payment'];
                            $types = $_POST['types'];
                            $check = checkitem('phone' , 'users' , "WHERE phone = $phone");  // check if phone in database or not have order completed or not
                            if($check >= 1){

                                if($types == 'box'){
                             $row = select('Quantity' ,'products' , "WHERE name = '$name'");
                            
                                if($row['Quantity'] < 1){
                                    echo '<div class ="alert alert-danger">Sorry Product is empty</div>';
                                    header("Refresh:1; url=orders.php");
                                    exit();
                                }else{

                                $row = select('Quantity' ,'products' , "WHERE name = '$name'");
                                if($row['Quantity'] < $Quantity){
                                    echo '<div class ="alert alert-danger">Sorry you have only '.$row['Quantity'].' of '.$name.' </div>';
                                    header("Refresh:1; url=orders.php");
                                    exit();  
                                }else{

                                $row = select('id,name' ,'users' , "WHERE phone = $phone");
                                $user_id=$row['id'];
                                $username=$row['name'];
                            $row = select('*' ,'products' , "WHERE name = '$name'");

                                $product_id=$row['id'];
                                $product_name=$row['name'];
                                $price=$row['price'];
                                $row['Quantity']= $row['Quantity'] - $Quantity;
                                $row['strips'] = $row['Quantity'] * $row['strip_box'];

                                $Quant =$row['Quantity'];
                                $strips = $row['strips'];

                                $stmt=$con->prepare("UPDATE products SET  Quantity = ? , strips = ? WHERE id =?");
                                $stmt->execute(array( $Quant ,$strips  , $product_id));
                              
                            $check = checkitem('user_id' , 'orders' , "WHERE user_id = $user_id AND completed = 0  ");  // check if user have order completed or not
                            $row= select('id' , 'orders' , "WHERE user_id = $user_id AND completed = 0 ");  // slect id of order 
                                
                                    // id of order
                                 if( is_array($row) ) {

                                    $IDD = $row['id'];
                                    
                                    }

                              $priceofQuantity =  $price *  $Quantity;

                            if($check > 0){

                                $check = checkitem('name' , 'orders_deteils' , "WHERE name = '$name' AND box = 1 AND completed = 0 ");

                                if($check > 0){

                                    $row = select('priceofQuantity , quantity ' , 'orders_deteils' , "WHERE user_id = $user_id AND box = 1 AND completed = 0 AND name = '$name'");

                                    $priceofQuantity =$priceofQuantity +  $row['priceofQuantity'];
                                    $Quantity =  $Quantity + $row['quantity'];

                                   
                                   
                                    $stmt=$con->prepare("UPDATE orders_deteils SET  priceofQuantity = ?, quantity = ?  WHERE user_id = ? AND box = 1 And completed = 0");
                                    $stmt->execute(array( $priceofQuantity , $Quantity ,$user_id ));

                                   header("Location: orders.php");
                                   exit();
                                   
                                }else{
                                    $stmt=$con->prepare("INSERT INTO orders_deteils (id_order , id_product , user_id , name , PriceOfOneProduct , priceofQuantity ,quantity ,box )
                                    VALUES (:zid_order , :zidpro , :ziduser   , :zname , :zPriceOfOneProduct, :zpriceofQuantity , :zquantity , 1)");
                                        $stmt->execute(array(
                                            'zid_order'   =>  $IDD,
                                            'zidpro' => $product_id,
                                            'ziduser'   =>  $user_id,
                                            'zname' => $name,
                                            'zPriceOfOneProduct'   =>  $price,
                                            'zpriceofQuantity' => $priceofQuantity,
                                            'zquantity'   =>  $Quantity,
                                            
                                            
                                        ));
                                        header("Location: orders.php");
                                        exit();
                                
                                }

                              
                            }else{

                                $stmt=$con->prepare("INSERT INTO orders (user_id) VALUES (:ziduser)");
                                $stmt->execute(array(
                                    'ziduser'   =>  $user_id,
                                ));
                
                                $row= select('id' , 'orders' , "WHERE user_id = $user_id AND  completed = 0");
                                $IDD = $row['id'];
                
                                $stmt=$con->prepare("INSERT INTO orders_deteils (id_order , id_product , user_id  , name , PriceOfOneProduct , priceofQuantity ,quantity ,box )
                                VALUES ( :zid_order , :zidpro , :ziduser  , :zname , :zPriceOfOneProduct, :zpriceofQuantity , :zquantity , 1)");
                                $stmt->execute(array(
                                    'zid_order'   =>  $IDD,
                                        'zidpro' => $product_id,
                                        'ziduser'   =>  $user_id,
                                        'zname' => $name,
                                        'zPriceOfOneProduct'   =>  $price,
                                        'zpriceofQuantity' => $priceofQuantity,
                                        'zquantity'   =>  $Quantity,
                                        
                                ));
                                header("Location: orders.php");
                
                            }

                          
                        }
                        }
                    }else{    // strips

                        $row = select('strips' ,'products' , "WHERE name = '$name'");
                            
                        if($row['strips'] < 1){
                            echo '<div class ="alert alert-danger">Sorry Product is empty</div>';
                            header("Refresh:1; url=orders.php");
                            exit();
                        }else{

                        $row = select('strips' ,'products' , "WHERE name = '$name'");
                        if($row['strips'] < $Quantity){
                            echo '<div class ="alert alert-danger">Sorry you have only '.$row['strips'].' of '.$name.' </div>';
                            header("Refresh:1; url=orders.php");
                            exit();  
                        }else{

                        $row = select('id,name' ,'users' , "WHERE phone = $phone");
                        $user_id=$row['id'];
                        $username=$row['name'];
                    $row = select('*' ,'products' , "WHERE name = '$name'");

                        $product_id=$row['id'];
                        $product_name=$row['name'];
                        $price=$row['price_box'];

                        $row['strips'] = $row['strips'] - $Quantity;

                        $row['Quantity']= $row['strips'] / $row['strip_box'];
                      

                        $Quant =$row['Quantity'];
                        $strips = $row['strips'];

                        $stmt=$con->prepare("UPDATE products SET  Quantity = ? , strips = ? WHERE id =?");
                        $stmt->execute(array( $Quant ,$strips  , $product_id));
                      
                    $check = checkitem('user_id' , 'orders' , "WHERE user_id = $user_id AND completed = 0  ");  // check if user have order completed or not
                    $row= select('id' , 'orders' , "WHERE user_id = $user_id AND completed = 0 ");  // slect id of order 
                        
                            // id of order
                         if( is_array($row) ) {

                            $IDD = $row['id'];
                            
                            }

                      $priceofQuantity =  $price *  $Quantity;

                    if($check > 0){

                        $check = checkitem('name' , 'orders_deteils' , "WHERE name = '$name' AND box = 0  AND completed = 0 ");

                        if($check > 0){

                            $row = select('priceofQuantity , quantity ' , 'orders_deteils' , "WHERE user_id = $user_id AND box = 0 AND completed = 0 AND name = '$name'");

                            $priceofQuantity =$priceofQuantity +  $row['priceofQuantity'];
                            $Quantity =  $Quantity + $row['quantity'];

                           
                           
                           $stmt=$con->prepare("UPDATE orders_deteils SET  priceofQuantity = ?, quantity = ?   WHERE user_id = ? AND box = 0 And completed = 0");
                           $stmt->execute(array( $priceofQuantity , $Quantity ,$user_id ));

                           header("Location: orders.php");
                           exit();
                           
                        }else{
                            $stmt=$con->prepare("INSERT INTO orders_deteils (id_order , id_product , user_id , name , PriceOfOneProduct , priceofQuantity ,quantity,box )
                            VALUES (:zid_order , :zidpro , :ziduser   , :zname , :zPriceOfOneProduct, :zpriceofQuantity , :zquantity , 0)");
                                $stmt->execute(array(
                                    'zid_order'   =>  $IDD,
                                    'zidpro' => $product_id,
                                    'ziduser'   =>  $user_id,
                                    'zname' => $name,
                                    'zPriceOfOneProduct'   =>  $price,
                                    'zpriceofQuantity' => $priceofQuantity,
                                    'zquantity'   =>  $Quantity,
                                    
                                    
                                ));
                                header("Location: orders.php");
                                exit();
                        
                        }

                      
                    }else{

                        $stmt=$con->prepare("INSERT INTO orders (user_id) VALUES (:ziduser)");
                        $stmt->execute(array(
                            'ziduser'   =>  $user_id,
                        ));
        
                        $row= select('id' , 'orders' , "WHERE user_id = $user_id AND  completed = 0");
                        $IDD = $row['id'];
        
                        $stmt=$con->prepare("INSERT INTO orders_deteils (id_order , id_product , user_id  , name , PriceOfOneProduct , priceofQuantity ,quantity ,box )
                        VALUES ( :zid_order , :zidpro , :ziduser  , :zname , :zPriceOfOneProduct, :zpriceofQuantity , :zquantity , 0)");
                        $stmt->execute(array(
                            'zid_order'   =>  $IDD,
                                'zidpro' => $product_id,
                                'ziduser'   =>  $user_id,
                                'zname' => $name,
                                'zPriceOfOneProduct'   =>  $price,
                                'zpriceofQuantity' => $priceofQuantity,
                                'zquantity'   =>  $Quantity,
                                
                        ));
                        header("Location: orders.php");
        
                    }

                  
                }
                }

                    }
                        }else{
                            echo '<div class="alert alert-danger">Sorry this phone is not existing in database</div>';
                            header("Refresh:1; url=orders.php?do=add");
                            exit();
                        }}
                        ?>
                          <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Add Order</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="phone" type="text">
                                    </div>
                                </div>
                                
                          
                                <div class="form-group row">
                                  
                                    <label class="col-lg-3 col-form-label form-control-label">Products</label>
                                    
                                    <div class="col-lg-9">
                                    <select class="js-example-basic-single  btn-block" name="name">
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
                                    <label class="col-lg-3 col-form-label form-control-label">Quantity</label>
                                    <div class="col-lg-9">
                                      <select class="form-control" name="types">
                                        <option value="box">Box</option>
                                        <option value="stripe">Stripe</option>
                                        </select>
                                        <input class="form-control" name="Quantity" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                  
                                  <label class="col-lg-3 col-form-label form-control-label">Payment</label>
                                  
                                  <div class="col-lg-9">
                                  <select class="form-control btn-block" name="payment">
                                      
                                     <option value="cash on delivery">cash on delivery</option>
                                     <option value="paypal">paypal</option>
                                        
                                   </select>
                                 
                                  </div>
                              </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Add order</button>

                                    </div>
                                </div>
                            </form>
                            <a href="orders.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->
        
                    <?php
                    }elseif($_GET['do']== 'pending'){

                        $status = "WHERE completed= 0";?>
 <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Orders
                                <a href="?do=add">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Order</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Controls</th>                                             
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Controls</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'orders' ,"$status");
                                                   
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
                                                <td>
                                                  <a href="?do=delete&id=<?php echo $row['id']?>">  <button class="btn btn-danger">Delete</button></a>

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
                        </div>
               <?php }elseif($_GET['do'] == 'completed'){
                            $status = "WHERE completed= 1";?>
                             <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Orders
                                <a href="?do=add">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Order</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Delivery Name</th>
                                                <th>Status</th>
                                             
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Delivery Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'orders' ,"$status");
                                                   
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
                                                <td><?php echo $row['delivery_name'];?></td>
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
                        </div>
                        <?php }

    }
                        else{
                           
                        
                        
                ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Orders
                                <a href="?do=add">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Order</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Delivery Name</th>
                                                <th>Status</th>
                                             
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                            <th>ID</th>
                                                <th>User Name</th>
                                                <th>Payment</th>
                                                <th>Date</th>
                                                <th>Delivery Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'orders' ,"ORDER BY id DESC");
                                                   
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
                                                <td><?php echo $row['delivery_name'];?></td>
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
                        </div>
                    </div>
                </main>
                <script>

                </script>
               <?php
                    }
                include "assets/includes/footer.php";
               ?>

<?php ob_end_flush();
    }else{
        header("Location: login.php");
    }
?>
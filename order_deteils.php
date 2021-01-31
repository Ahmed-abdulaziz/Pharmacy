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

    <?php if(isset($_GET['id']) && isset($_GET['orderid'])){

        $orderid = $_GET['orderid'];
        $ID = $_GET['id'];

        if(isset($_POST['submit'])){

            $delivery = $_POST['name'];
            $total = $_POST['total'];

            $stmt=$con->prepare("UPDATE orders SET  date = now() , delivery_name = ? , completed = 1   WHERE id = ?");
            $stmt->execute(array($delivery , $orderid));

            $stmt=$con->prepare("UPDATE orders_deteils SET   completed = 1   WHERE id_order = ?");
            $stmt->execute(array($orderid));

            $row = select('totalprice' ,'users' , "WHERE id = $ID ");
            $row['totalprice'] =$row['totalprice'] + $total;
            $total = $row['totalprice']; 

            $stmt=$con->prepare("UPDATE users SET   totalprice = ?   WHERE id = ?");
            $stmt->execute(array($total ,$ID));


            $row = select('target' ,'delivery' , "WHERE name = '$delivery'");
            $row['target'] =$row['target'] + 1;
            $target = $row['target']; 

            $stmt=$con->prepare("UPDATE delivery SET   target = ?   WHERE name = ?");
            $stmt->execute(array( $target ,$delivery));
                                  
            header("Location: orders.php");
        }
   
?>

                       
                            <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Order Deteils
                                <a href="orders.php?do=add">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Order</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                            <th>Order ID</th>
                                                <th>User Name</th>
                                                <th>Product Name</th>
                                                <th>quantity</th>
                                                <th>total price</th>
                                                <th>Status</th>
                                                <th>Controls</th>
                                             
                                            </tr>
                                        </thead>
                                       
                                        <tbody>
                                            <?php 
                                                 $ID = $_GET['id'];
                                                $rows = selectall('*' , 'orders_deteils' , "WHERE id_order = $orderid ");
                                                $total = 0;
                                                foreach($rows as $row){
                                                    $ID =$row['user_id'];
                                                    $name =select('name' ,'users' , "WHERE id =$ID ");
                                                    
                                                    $total = $total + $row['priceofQuantity']
                                            ?>
                                            <a>
                                            <tr>
                                                <td><?php echo $row['id_order'];?></td>
                                                <td><?php echo $name['name'];?></td>
                                                <td><?php echo $row['name'];?></td>
                                                <td><?php echo $row['quantity'];
                                                    if($row['box'] == 1){
                                                        echo " Box";
                                                    }else{
                                                        echo " stripe";
                                                    }
                                                    ?>
                                                        
                                                </td>
                                                <td><?php echo $row['priceofQuantity'];?></td>
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
                                                    <?php 
                                                    if($row['completed'] == 0){
                                                     
                                                    ?>
                                                  <a href="?do=edit&id=<?php echo $row['id']?>"> <button class="btn btn-primary">Edit</button></a>
                                                  <a href="?do=delete&id=<?php echo $row['id']?>">  <button class="btn btn-danger">Delete</button></a>
                                                    <?php }?>
                                                </td>
                                              
                                            </tr>
                                            </a>
                                                <?php }?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total = </th>
                                                <th><?php echo $total ;?></th>
                                                <th></th>
                                                <th>Controls</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?php if($status == 'pending'){?>
                                    <form method="post">
                                    <div class="form-group">
                                  
                                  <label class="col-lg-3 col-form-label form-control-label">Delivery</label>
                                  
                                  <div class="col-lg-9">
                                  <select class="js-example-basic-single  btn-block" name="name">
                                      <?php 
                                          $rows = selectall('*','delivery');
                                          foreach($rows as $row){
                                      ?>
                                              <option value="<?php echo $row['name']?>"><?php echo $row['name']?></option>
                                          <?php }?>
                                   </select>
                                 
                                  </div>
                              </div>
                                    <input name="total" type="number" step="any" value="<?php echo $total;?>" hidden>
                                    <button class="btn btn-success btn-block" name="submit"><i class="fa fa-check" aria-hidden="true"></i> Complete Order</button>
                                    </form>
                                          <?php }?>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>

            <?php
                    }elseif(isset($_GET['do'])){
                       
                        if($_GET['do'] == 'edit'){
                            if(isset($_GET['id'])){

                            
                                $id=$_GET['id'];
                                $row = select('*' , 'orders_deteils' , "WHERE id =$id");
                                $box = $row['box'];
                                $oldquantity = $row['quantity'];
                            if(isset($_POST['edit'])){
                                   
                                    $total = 0;
                                    $name=$_POST['name'];
                                    if($box == 1){
                                    $row = select('Quantity,strips,strip_box' , 'products' , "WHERE name ='$name'");
                                    $databaseQuantity = $row['Quantity'];
                                    $newQuantity=$_POST['Quantity'];


                                    $total = $databaseQuantity + $oldquantity;  
                                    $total = $total - $newQuantity;
                                    $strips = $total * $row['strip_box'];

                                    $stmt=$con->prepare("UPDATE products SET  Quantity = ? ,  strips = ?   WHERE name = ?"); // to update quantity of product
                                    $stmt->execute(array( $total , $strips , $name));

                                    $price=select('PriceOfOneProduct' , 'orders_deteils' ,"WHERE id =$id"); // to update price of order

                                    $priceofQuantity = $newQuantity * $price['PriceOfOneProduct'];

                                    $stmt=$con->prepare("UPDATE orders_deteils SET  quantity = ? , priceofQuantity = ?   WHERE id = ?");
                                    $stmt->execute(array( $newQuantity, $priceofQuantity ,$id));

                                    header("Location: orders.php");
                                }else{

                                    $row = select('Quantity,strips,strip_box' , 'products' , "WHERE name ='$name'");
                                    $databaseQuantity = $row['strips'];
                                    $newQuantity=$_POST['Quantity'];


                                    $total = $databaseQuantity + $oldquantity;  
                                    $strips = $total - $newQuantity;
                                    $Quantity = $strips / $row['strip_box'];

                                    $stmt=$con->prepare("UPDATE products SET  Quantity = ? ,  strips = ?   WHERE name = ?"); // to update quantity of product
                                    $stmt->execute(array( $Quantity , $strips , $name));

                                    $price=select('PriceOfOneProduct' , 'orders_deteils' ,"WHERE id =$id"); // to update price of order

                                    $priceofQuantity = $newQuantity * $price['PriceOfOneProduct'];

                                    $stmt=$con->prepare("UPDATE orders_deteils SET  quantity = ? , priceofQuantity = ?   WHERE id = ?");
                                    $stmt->execute(array( $newQuantity, $priceofQuantity ,$id));

                                    header("Location: orders.php");
                                }

                        }
                        ?>

                        
                    <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Edit Order</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off">
                              
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Product Name</label>
                                    <div class="col-lg-9">
                                       
                                        <select class="js-example-basic-single  btn-block" value="<?php echo  $row['name']?>" name="name">
                                        <?php 
                                            $rows = selectall('*','products');
                                            foreach($rows as $row1){
                                        ?>
                                                <option value="<?php echo $row1['name']?>"><?php echo $row1['name']?></option>
                                            <?php }?>
                                     </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Quantity</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="Quantity" value="<?php echo  $row['quantity']?>" type="text">
                                    </div>
                                </div>
                                
                              
                                
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="edit"><i class="fa fa-plus" aria-hidden="true"></i> Edit Order</button>

                                    </div>
                                </div>
                            </form>
                            <a href="orders.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                    <?php }else{
                        header("Location: orders.php");
                    }
                }elseif($_GET['do'] == 'delete'){
                        if(isset($_GET['id'])){

                            $IDD = $_GET['id'];

                           
                            $order=select('quantity,name ,id_order,box' , 'orders_deteils' , "WHERE id= $IDD");
                            $name = $order['name'];

                            $products=select('Quantity,id,strips,strip_box' , 'products' , "WHERE name='$name'");
                            $id = $products['id'];
                            $Quantity =  $products['Quantity'] ;
                            $strips = $products['strips'] ;
                            $strip_box = $products['strip_box'] ;

                              if($order['box'] == 1){

                                $products['Quantity'] = $products['Quantity'] + $order['quantity'];
                                $newQuantity = $products['Quantity'];
                                $strips =  $newQuantity *  $strip_box;
    
                                $stmt=$con->prepare("UPDATE products SET  quantity = ? , strips = ? WHERE id = ?");
                                $stmt->execute(array( $newQuantity , $strips , $id));
    
                                $stmt=$con->prepare("DELETE FROM  orders_deteils WHERE id = :zid");
                                $stmt->bindparam('zid',$IDD);
                                $stmt->execute();
                            }else{
                                $strips = $strips + $order['quantity'];
                                $newQuantity = $strips / $strip_box;
    
    
                                $stmt=$con->prepare("UPDATE products SET  quantity = ? , strips = ? WHERE id = ?");
                                $stmt->execute(array($newQuantity , $strips , $id));
    
                                $stmt=$con->prepare("DELETE FROM  orders_deteils WHERE id = :zid");
                                $stmt->bindparam('zid',$IDD);
                                $stmt->execute();
                            }
                           

                            $ID = $order['id_order'];      // id in order table

                            $check=checkitem('id_order' ,'orders_deteils' ,"WHERE id_order =$ID");
                            if($check < 1){
                                     
                            $stmt=$con->prepare("DELETE FROM  orders WHERE id = :zid");
                            $stmt->bindparam('zid',$ID);
                            $stmt->execute();

                            }
                           
                           

                           
                            header("Location: orders.php");
                           
                        }else{
                            header("Location: orders.php");
                        }
                }
                }else{
                    header("Location: orders.php");
                }
            ?>



<?php 


    include "assets/includes/footer.php";


ob_end_flush();
}else{
    header("Location: login.php");
}
?>



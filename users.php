<?php 
ob_start();
    $pagetitle = 'users';
    include "init.php";

if(isset($_SESSION['username'])){

    if(isset($_POST['clear'])){
            
        $stmt=$con->prepare("UPDATE users SET   totalprice = 0 ");
         $stmt->execute();
                              
    }
?>
            <div id="layoutSidenav_content">
             
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Users</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                        <?php 
                    if(isset($_GET['do'])){

                        if($_GET['do'] == 'add'){

                        if(isset($_POST['add'])){

                          

                            $name=$_POST['name'];
                            $email=$_POST['email'];
                            $phone=$_POST['phone'];
                            $address=$_POST['address'];
                            $age = $_POST['age'];
                            $check = checkitem('phone' , 'users' , "WHERE phone = $phone ");
                            if($check > 0){
                                echo "<div class ='alert alert-danger container'>Phone is aleardy in database</div>";
                                  header("Refresh:1; url=users.php?do=add");
                             exit();
                            }else{

                                $stmt=$con->prepare("INSERT INTO users (name ,email , phone , Adderss , Age) 
                                VALUES (:zname ,:zemail ,:zphone , :zAdderss , :zAge )");
                                    $stmt->execute(array(
                                        'zname'   =>  $name,
                                        'zemail' => $email,
                                        'zphone'   =>  $phone,
                                        'zAdderss' => $address,
                                        'zAge'   =>  $age,
                                        
                                        
                                    ));
                                    header("Location: users.php");
                                    exit();
                            }

                          


                        }
                        ?>
                          <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Add User</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="name" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="email" type="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="phone" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Adderss</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="address" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Age</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="age" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Add User</button>

                                    </div>
                                </div>
                            </form>
                            <a href="users.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->
        
                    <?php
                    } elseif($_GET['do'] == 'edit'){
                            if(isset($_GET['id'])){
                                $id=$_GET['id'];
                                $row = select('*' , 'users' , "WHERE id =$id");
                            if(isset($_POST['edit'])){
                            $name=$_POST['name'];
                            $email=$_POST['email'];
                            $phone=$_POST['phone'];
                            $address=$_POST['address'];
                            $age = $_POST['age'];
                            if(empty($phone)){

                                $result = select('phone' , 'users' , "WHERE id = $id");
                                $phone = $result['phone']; 
                                
                                $stmt=$con->prepare("UPDATE users SET  name = ?, email = ? , phone = ? , Adderss = ? , Age = ?  WHERE id = ?");
                                $stmt->execute(array( $name ,$email , $phone , $address ,$age,$id));
                                header("Location: users.php");
                                    exit();
                            } else {

                            $check = checkitem('phone' , 'users' , "WHERE phone = $phone ");
                            if($check > 0){
                                echo "<div class ='alert alert-danger container'>Phone is aleardy in database</div>";
                                  header("Refresh:1; url=users.php?do=edit&id=$id");
                             exit();
                            }else{
                         
                                
                                $stmt=$con->prepare("UPDATE users SET  name = ?, email = ? , phone = ? , Adderss = ? , Age = ?  WHERE id = ?");
                                $stmt->execute(array( $name ,$email , $phone , $address ,$age,$id));
                                header("Location: users.php");
                                    exit();
                                }   }
                        }
                        ?>
                        

                        <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Edit User</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="name" value="<?php echo  $row['name']?>" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="email" value="<?php echo  $row['email']?>" type="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo  $row['phone']?>" type="text" disabled>
                                        <input class="form-control" name="phone" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Adderss</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="address" value="<?php echo  $row['Adderss']?>" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Age</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="age" value="<?php echo  $row['Age']?>" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="edit"><i class="fa fa-plus" aria-hidden="true"></i> Edit User</button>

                                    </div>
                                </div>
                            </form>
                            <a href="users.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>

                    <?php
                     }else{
                         header("Location: users.php");
                     } }elseif($_GET['do']== 'delete'){
                         if(isset($_GET['id'])){
                           
                            $IDD = $_GET['id'];
                            $stmt=$con->prepare("DELETE FROM  users WHERE id = :zid");
                            $stmt->bindparam('zid',$IDD);
                            $stmt->execute();

                            $stmt=$con->prepare("DELETE FROM  orders WHERE user_id  = :zid");
                            $stmt->bindparam('zid',$IDD);
                            $stmt->execute();

                            $stmt=$con->prepare("DELETE FROM  offer WHERE user_id = :zid");
                            $stmt->bindparam('zid',$IDD);
                            $stmt->execute();
                            
                            header("Location: users.php");
                         }else{
                            header("Location: users.php");
                         }
                         
                     }elseif($_GET['do']== 'view'){

                        if(isset($_GET['id'])){
                           
                            $IDD = $_GET['id'];
                            $rows = selectall('*' ,'orders_deteils' ,"WHERE user_id = $IDD AND completed = 1");
                            $name = select('name' ,'users' , "WHERE id =$IDD");
                            ?>

                           <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Purchase details for <?php echo $name['name'];?>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                 <th>ID</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                            
                                                foreach($rows as $row){
                                            $order_id = $row['id_order'];
                                            $date = select('date' ,'orders' ,"WHERE id = $order_id");?>
                                            
                                        
                                            <tr>
                                                
                                                <td><?php echo $row['id_order'];?></td>
                                                <td><?php echo $row['name'];?></td>
                                                <td><?php echo $row['quantity'];?></td>
                                                <td><?php echo $date['date'];?></td>
                                                <td><?php echo $row['priceofQuantity'];?></td>
                                             
                                            </tr>
                                            
                                                <?php }?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                            
                       <?php  }else{
                            header("Location: users.php");
                         }
                     }
                    
                    
                } else{
                ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Users
                                <a href="?do=add"><button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add User</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Total purchases
                                                <form method="post">
                                                <button class="btn btn-info" name="clear">Clear all purchases</button>
                                                </form> 
                                                </th>
                                                <th>Controls</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Total purchases</th>
                                                <th>Controls</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'users' , "ORDER BY totalprice DESC LIMIT 10");
                                                foreach($rows as $row){
                                            ?>
                                            <a href="index.php">
                                            <tr>
                                                
                                                <td><?php echo $row['id'];?></td>
                                                <td><a href="?do=view&id=<?php echo $row['id']?>"><?php echo $row['name'];?></a></td>
                                                <td><?php echo $row['email'];?></td>
                                                <td><?php echo $row['phone'];?></td>
                                                <td><?php echo $row['Adderss'];?></td>
                                                <td><?php echo $row['Age'];?></td>
                                                <td><?php echo $row['totalprice'];?></td>
                                                <td>
                                                  <a href="?do=edit&id=<?php echo $row['id']?>"> <button class="btn btn-primary">Edit</button></a>
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
                    </div>
                </main>

               <?php
                    }
                include "assets/includes/footer.php";
                ob_end_flush();
}else{
    header("Location: login.php");
}
               ?>

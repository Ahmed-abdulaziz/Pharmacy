<?php 
ob_start();
    $pagetitle = 'Delivery';
    include "init.php";
 if(isset($_SESSION['username'])){
        if(isset($_POST['clear'])){
            
            $stmt=$con->prepare("UPDATE delivery SET   target = 0 ");
             $stmt->execute();
                                  
        }
   
?>
            <div id="layoutSidenav_content">
             
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">delivery</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">delivery</li>
                        </ol>
                        <?php 
                    if(isset($_GET['do'])){

                        if($_GET['do'] == 'add'){

                        

                        if(isset($_POST['add'])){

                          

                            $name=$_POST['name'];
                            $address=$_POST['address'];
                            $phone=$_POST['phone'];
                            $age = $_POST['age'];
                            $check = checkitem('name' , 'delivery' , "WHERE name = '$name' ");
                            if($check > 0){
                                echo "<div class ='alert alert-danger container'>name is aleardy in database please choose another name</div>";
                                  header("Refresh:1; url=users.php?do=add");
                             exit();
                            }else{

                                $stmt=$con->prepare("INSERT INTO delivery (name ,phone , Address , Age ) 
                                VALUES (:zname ,:zphone , :zAddress , :zAge )");
                                    $stmt->execute(array(
                                        'zname'   =>  $name,
                                        'zphone'   =>  $phone,
                                        'zAddress' => $address,
                                        'zAge'   =>  $age,
                                        
                                        
                                    ));
                                    header("Location: delivery.php");
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
                            <h3 class="mb-0">Add Delivery</h3>
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
                                       <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Add delivery</button>

                                    </div>
                                </div>
                            </form>
                            <a href="delivery.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->
        
                    <?php
                    }
                elseif($_GET['do'] == 'edit'){
                
                        if(isset($_GET['id'])){

                            $id=$_GET['id'];
                            $row = select('*' , 'delivery' , "WHERE id =$id");
                        if(isset($_POST['edit'])){
                               
                                $name=$_POST['name'];
                                $address=$_POST['address'];
                                $phone=$_POST['phone'];
                                $age = $_POST['age'];
                                $target = $_POST['target'];
                                if(empty($name)){

                            $result = select('name' , 'delivery' , "WHERE id = $id");
                            $name = $result['name']; 
                            
                            $stmt=$con->prepare("UPDATE delivery SET  name = ? ,phone = ? , Address = ? , Age =? , target = ?  WHERE id = ?");
                            $stmt->execute(array( $name , $phone , $address ,$age,$target , $id));
                            header("Location: delivery.php");
                                exit();
                        } else {

                        $check = checkitem('name' , 'delivery' , "WHERE name = '$name'");
                        if($check > 0){
                            echo "<div class ='alert alert-danger container'>This Name is aleardy in database</div>";
                              header("Refresh:1; url=delivery.php?do=edit&id=$id");
                         exit();
                        }else{
                     
                            
                            $stmt=$con->prepare("UPDATE delivery SET  name = ? ,phone = ? , Address = ? , Age =? , target = ?  WHERE id = ?");
                            $stmt->execute(array( $name , $phone , $address ,$age,$target , $id));
                            header("Location: delivery.php");
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
                            <h3 class="mb-0">Edit Delivery</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                         <input class="form-control" value="<?php echo $row['name']?>" name="name" type="text" disabled>
                                        <input class="form-control"  name="name" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['phone']?>"  name="phone" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Adderss</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['Address']?>"  name="address" type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Age</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="age" value="<?php echo $row['age']?>"  type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Target</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="target" value="<?php echo $row['target']?>"  type="text">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="edit"><i class="fa fa-plus" aria-hidden="true"></i> Edit delivery</button>

                                    </div>
                                </div>
                            </form>
                            <a href="delivery.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->
                <?php 
                }  else{
                    header("Location: delivery.php");
                } 
            } elseif($_GET['do'] == 'delete'){

                if(isset($_GET['id'])){
                           
                    $IDD = $_GET['id'];
                    $stmt=$con->prepare("DELETE FROM  delivery WHERE id = :zid");
                    $stmt->bindparam('zid',$IDD);
                    $stmt->execute();
    
               
                    header("Location: delivery.php");
                 }else{
                    header("Location: delivery.php");
                 }
            }
                    
            }  else{
                ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Users
                                <a href="?do=add"><button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Delivery</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered"  width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Target 
                                                 <form method="post">
                                                <button class="btn btn-info" name="clear">Clear all Target</button>
                                                </form> 
                                            </th>
                                            <th>Controls</th>
                                           
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Age</th>
                                                <th>Target</th>
                                                <th>Controls</th>
                                                
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'delivery' ,"ORDER BY target DESC");
                                                foreach($rows as $row){
                                            ?>
                                           
                                            <tr>
                                                
                                                <td><?php echo $row['id'];?></td>
                                                <td><?php echo $row['name'];?></td>
                                                <td><?php echo $row['phone'];?></td>
                                                <td><?php echo $row['Address'];?></td>
                                                <td><?php echo $row['age'];?></td>
                                                <td><?php echo $row['target'];?></td>
                                                <td>
                                                  <a href="?do=edit&id=<?php echo $row['id']?>"> <button class="btn btn-primary">Edit</button></a>
                                                  <a href="?do=delete&id=<?php echo $row['id']?>">  <button class="btn btn-danger">Delete</button></a>

                                                </td>
                                            </tr>
                                            
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
               ?>

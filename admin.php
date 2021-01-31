<?php 
ob_start();
    $pagetitle = 'users';
    include "init.php";

if(isset($_SESSION['username'])){

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
                         $pass=$_POST['pass'];
                     
                         $check = checkitem('email' , 'admin' , "WHERE email ='$email'");
                         if($check > 0){
                            echo "<div class ='alert alert-danger container'>Email is existing in database</div>";
                         }else{

                         
                         if(strlen($name) < 4){
                
                            echo "<div class ='alert alert-danger container'>name must be more than 4 chrecture</div>";
                           
                    }elseif(strlen($pass) < 6){
                       
                        echo "<div class ='alert alert-danger container'>password must be more than 5 numbers not ".strlen($pass)." number</div>";
                     
                    }else{
                        $stmt=$con->prepare("INSERT INTO admin (username ,email , password ,grouped ) 
                        VALUES (:zusername ,:zemail , :zpassword , 1)");
                            $stmt->execute(array(
                                'zusername'   =>  $name,
                                'zemail'   =>  $email,
                                'zpassword' => $pass,
                            
                            ));
                            header("Location: admin.php");
                            exit();
                    }

                       

                    }
                     }
                     ?>
                       <div class="col-md-8 offset-md-2">
                 <span class="anchor" id="formUserEdit"></span>
                 <hr class="my-5">

                 <!-- form user info -->
                 <div class="card card-outline-secondary">
                     <div class="card-header">
                         <h3 class="mb-0">Add Admin</h3>
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
                                 <label class="col-lg-3 col-form-label form-control-label">Password</label>
                                 <div class="col-lg-9">
                                     <input class="form-control" name="pass" type="text">
                                 </div>
                             </div>
                           
                             
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label"></label>
                                 <div class="col-lg-9">
                                    <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Add Admin</button>

                                 </div>
                             </div>
                         </form>
                         <a href="admin.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                     </div>
                 </div>
                 <!-- /form user info -->

             </div>
             <!--/col-->
     
                 <?php
                 } elseif($_GET['do'] == 'edit'){
                         if(isset($_GET['id'])){
                        $id=$_GET['id'];
                            $row = select('*' , 'admin' , "WHERE id =$id");
                        $password = $row['password'];
                         if(isset($_POST['edit'])){
                         $name=$_POST['name'];
                         $email=$_POST['email'];
                         $oldpassword=$_POST['old'];
                         $newpassword=$_POST['new'];
                        
                    if(strlen($name) < 4){
                
                            echo "<div class ='alert alert-danger container'>name must be more than 4 words</div>";
                            header("Refresh:1; url=admin.php?do=edit&id=$id");
                            exit();
                     }elseif($oldpassword == $password){

                        if(empty($newpassword)){
                       
                            $stmt=$con->prepare("UPDATE admin SET  username = ?, email = ? , password = ?  WHERE id = ?");
                            $stmt->execute(array( $name ,$email , $oldpassword , $id));
                            header("Location: admin.php");
                                exit();
                        }elseif(strlen($newpassword) < 6){
                            echo "<div class ='alert alert-danger container'>New Password must be more than 5 words</div>";
                            header("Refresh:1; url=admin.php?do=edit&id=$id");
                            exit();
                        }else{
                          
                                 
                                 $stmt=$con->prepare("UPDATE admin SET  username = ?, email = ? , password = ?  WHERE id = ?");
                                 $stmt->execute(array( $name ,$email , $newpassword , $id));
                                 header("Location: admin.php");
                                     exit();
                           }
                    }elseif(empty($oldpassword)){

                        $stmt=$con->prepare("UPDATE admin SET  username = ?, email = ? , password = ?  WHERE id = ?");
                        $stmt->execute(array( $name ,$email , $password , $id));
                        header("Location: admin.php");
                            exit();
                    }else{
                        echo "<div class ='alert alert-danger container'>Old Password IS incorrect</div>";
                        header("Refresh:2; url=admin.php?do=edit&id=$id");
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
                         <h3 class="mb-0">Edit Admin</h3>
                     </div>
                     <div class="card-body">
                         <form class="form" method="post" role="form" autocomplete="off">
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                 <div class="col-lg-9">
                                     <input class="form-control" name="name" value="<?php echo  $row['username']?>" type="text">
                                 </div>
                             </div>
                             
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                 <div class="col-lg-9">
                                     <input class="form-control" name="email" value="<?php echo  $row['email']?>" type="email">
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label">Old Password</label>
                                 <div class="col-lg-9">
                                     <input class="form-control" name="old" type="password" >
                                     
                                 </div>
                             </div>
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label">New Password</label>
                                 <div class="col-lg-9">
                                     <input class="form-control" name="new" type="password" >
                                     
                                 </div>
                             </div>
                         
                             
                             <div class="form-group row">
                                 <label class="col-lg-3 col-form-label form-control-label"></label>
                                 <div class="col-lg-9">
                                    <button class="btn btn-success" name="edit"><i class="fa fa-plus" aria-hidden="true"></i> Edit Admin</button>

                                 </div>
                             </div>
                         </form>
                         <a href="admin.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

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
                         $stmt=$con->prepare("DELETE FROM  admin WHERE id = :zid");
                         $stmt->bindparam('zid',$IDD);
                         $stmt->execute();                         
                         header("Location: admin.php");
                      }else{
                         header("Location: admin.php");
                      }
                      
                  }elseif($_GET['do']== 'make'){
                    if(isset($_GET['id'])){
                        
                        $IDD = $_GET['id'];
                        $stmt=$con->prepare("UPDATE admin SET  grouped = 1  WHERE id = ?");
                        $stmt->execute(array($IDD));

                        header("Location: admin.php");
                    }else{
                        header("Location: index.php");
                     }
                  }
                 
                 
             } else{
             ?>
                     <div class="card mb-4">
                         <div class="card-body">
                               <div class="card mb-4">
                         <div class="card-header">
                             <i class="fas fa-table mr-1"></i>
                             Admins
                             <a href="?do=add"><button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Admin</button></a>
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table class="table table-bordered" width="100%" cellspacing="0">
                                     <thead>
                                         <tr>
                                             <th>ID</th>
                                             <th>Name</th>
                                             <th>Email</th>
                                             <th>Controls</th>
                                         </tr>
                                     </thead>
                                     <tfoot>
                                         <tr>
                                             <th>ID</th>
                                             <th>Name</th>
                                             <th>Email</th>
                                             <th>Controls</th>
                                         </tr>
                                     </tfoot>
                                     <tbody>
                                         <?php 
                                             $rows = selectall('*' , 'admin' ,"WHERE grouped = 1");
                                             foreach($rows as $row){
                                         ?>
                                         <a href="index.php">
                                         <tr>
                                             
                                             <td><?php echo $row['id'];?></td>
                                             <td><?php echo $row['username'];?></td>
                                             <td><?php echo $row['email'];?></td>
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

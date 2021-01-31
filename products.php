<?php 
ob_start();
    $pagetitle = 'Products';
    include "init.php";
    if(isset($_SESSION['username'])){
   
?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Products</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
                        </ol>
                        <?php 
            if(isset($_GET['do'])){
                    if($_GET['do']== 'add'){

                        if(isset($_POST['add'])){

                          
                            $name=$_POST['name'];
                            $Description = $_POST['description'];
                            $Quantity=$_POST['Quantity'];
                            $price=$_POST['price'];
                            $phone=$_POST['phone'];
                            $strip_box=$_POST['strip'];
                            
                        $check = checkitem('name' , 'products' , "WHERE name = '$name'");
                        if($check > 0){   // if product is aleady in database
                            $row = select('Quantity' , 'products' , "WHERE name = '$name'");
                            $row['Quantity'] =  $row['Quantity'] + $Quantity;
                            $row['strips'] =  $row['Quantity'] * $row['strip_box'];

                            $Quant = $row['Quantity'];
                            $strips =  $row['strips'];
                            $stmt=$con->prepare("UPDATE products SET  Quantity = ?  , strips = ? WHERE name =?");
                            $stmt->execute(array( $Quant ,$strips , $name));
                        }else{
                            $imageName = $_FILES['image']['name'];
                            $imageSize = $_FILES['image']['size'];
                            $imageTmp  = $_FILES['image']['tmp_name'];
                            $imageType = $_FILES['image']['type'];

                            $imageName = $_FILES['image']['name'];
                            $imageSize = $_FILES['image']['size'];
                            $imageTmp  = $_FILES['image']['tmp_name'];
                            $imageType = $_FILES['image']['type'];

                            $exp = explode('.' , $imageName);
                            $imageExtension = strtolower(end($exp));
                            $Mimage = rand(0,100000) . '_' .$imageName;
                            move_uploaded_file($imageTmp , "assets/img//". $Mimage);
                            
                           $price_box =  $price / $strip_box;
                           $strips = $strip_box *  $Quantity ;
                            $stmt=$con->prepare("INSERT INTO products (image ,name , description , Quantity , price ,strips ,strip_box ,price_box ,  company_phone) 
                            VALUES (:zimage ,:zname ,:zdescription , :zQuantity , :zprice ,:zstrips ,:zstrip_box ,:zprice_box ,  :zcompany_phone)");
                            $stmt->execute(array(
                            'zimage'   =>  $Mimage,
                            'zname' => $name,
                            'zdescription'   =>  $Description,
                            'zQuantity' => $Quantity,
                            'zprice'   =>  $price,
                            'zstrips'   =>  $strips,
                            'zstrip_box'   =>  $strip_box,
                            'zprice_box'   =>  $price_box,
                            'zcompany_phone' => $phone,
                            ));
                                header("Location: products.php");
                        }
                        }
                        ?>
                          <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Add Products</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Image</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="image" type="file">
                                    </div>
                                </div>
                                
                          
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="name" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Description</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="description" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Quantity</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="Quantity" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">strips / Box</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="strip" step="any" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">price</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="price"  step="any" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Company Phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="phone" type="number">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="add"><i class="fa fa-plus" aria-hidden="true"></i> Add Product</button>

                                    </div>
                                </div>
                            </form>
                            <a href="products.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->
        
                  
                <?php
          }elseif($_GET['do'] == 'edit'){
              if(isset($_GET['id'])){
                  
                $idd = $_GET['id'];
                $row = select('*' , 'products' , "WHERE id = $idd");
                
                if(isset($_POST['edit'])){

                    $name=$_POST['name'];
                    $Description = $_POST['description'];
                    $Quantity=$_POST['Quantity'];
                    $price=$_POST['price'];
                    $phone=$_POST['phone'];
                    $strip_box=$_POST['strip'];
                    
                    $imageName = $_FILES['image']['name'];
                    $imageSize = $_FILES['image']['size'];
                    $imageTmp  = $_FILES['image']['tmp_name'];
                    $imageType = $_FILES['image']['type'];

                    if(empty($imageName)){

                        $result = select('image' , 'products' , "WHERE id = $idd");
                        $Mimage = $result['image']; 
                    } else {
                        $imageName = $_FILES['image']['name'];
                        $imageSize = $_FILES['image']['size'];
                        $imageTmp  = $_FILES['image']['tmp_name'];
                        $imageType = $_FILES['image']['type'];

                        $exp = explode('.' , $imageName);
                        $imageExtension = strtolower(end($exp));
                        $Mimage = rand(0,100000) . '_' .$imageName;
                        move_uploaded_file($imageTmp , "assets/img//".$Mimage);
                    }
                    $price_box =  $price / $strip_box;
                    $strips = $strip_box *  $Quantity ;

                    $stmt=$con->prepare("UPDATE Products SET  image = ?, name = ? , description = ?  , Quantity = ? , price = ? , strips =  ? , strip_box = ?, price_box = ?,company_phone = ? WHERE id = ?");
                    $stmt->execute(array( $Mimage , $name ,$Description , $Quantity , $price ,$strips, $strip_box ,$price_box,  $phone ,$idd));
                
                   
                        header("Location: products.php");
                

                        }
                  
                  ?>
              
              <div class="col-md-8 offset-md-2">
                    <span class="anchor" id="formUserEdit"></span>
                    <hr class="my-5">

                    <!-- form user info -->
                    <div class="card card-outline-secondary">
                        <div class="card-header">
                            <h3 class="mb-0">Edit Products</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" method="post" role="form" autocomplete="off" enctype="multipart/form-data">
                              
                            <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Image</label>
                                    <div class="col-lg-9">
                                        <img src="assets/img/<?php echo $row['image'];?>">
                                        <input class="form-control" name="image" type="file">
                                    </div>
                                </div>
                                
                          
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['name'];?>" name="name" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Description</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['description'];?>" name="description" type="text">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Quantity</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['Quantity'];?>" name="Quantity" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">price</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" value="<?php echo $row['price'];?>" name="price"  step="any" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">strips / Box</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="strip" value="<?php echo $row['strip_box'];?>" step="any" type="number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label">Company Phone</label>
                                    <div class="col-lg-9">
                                        <input class="form-control"  value="<?php echo $row['company_phone'];?>" name="phone" type="number">
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label form-control-label"></label>
                                    <div class="col-lg-9">
                                       <button class="btn btn-success" name="edit"><i class="fa fa-plus" aria-hidden="true"></i> Edit Product</button>

                                    </div>
                                </div>
                            </form>
                            <a href="products.php"> <button class="btn btn-primary btn-block"><i class="fa fa-ban" aria-hidden="true"></i> cancel</button></a> 

                        </div>
                    </div>
                    <!-- /form user info -->

                </div>
                <!--/col-->   
           <?php   }else{
                  header("Location: products.php");
              }
            
          }elseif($_GET['do'] == 'delete'){
              
            if(isset($_GET['id'])){
                           
                $IDD = $_GET['id'];
                $stmt=$con->prepare("DELETE FROM  products WHERE id = :zid");
                $stmt->bindparam('zid',$IDD);
                $stmt->execute();

           
                header("Location: products.php");
             }else{
                header("Location: products.php");
             }
          }

    }else{
                            
                       
                ?>
                       <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                     Products
                                <a href="?do=add">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Products</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>descroption</th>
                                                <th>Quantity</th>
                                                <th>price</th>
                                                <th>Company phone</th>
                                                <th>Controls</th>
                                             
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>description</th>
                                                <th>Quantity</th>
                                                <th>price</th>
                                                <th>Company phone</th>
                                                <th>Controls</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'products');
                                                   
                                                foreach($rows as $row){
                                                    
                                            ?>
                                            <a>
                                            <tr>
                                                <td><?php echo $row['id'];?></td>
                                                <td><?php echo $row['name'];?></td>
                                                <td><?php echo $row['description'];?></td>
                                                <td><?php echo $row['Quantity'];?></td>
                                                <td><?php echo $row['price'];?></td>
                                                <td><?php echo $row['company_phone'];?></td>
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
?>
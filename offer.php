<?php 
ob_start();
    $pagetitle = 'Offers';
    include "init.php";
    if(isset($_SESSION['username'])){
    if(isset($_POST['clear'])){
            
        $stmt=$con->prepare("DELETE FROM  offer ");
        $stmt->execute();
                              
    }
   
?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Offers</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item active">Offers</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                  <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table mr-1"></i>
                                Offers
                                <a href="index.php">  <button class="btn btn-success fa-pull-right"><i class="fa fa-plus" aria-hidden="true"></i> Add Offer</button></a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User Name</th>
                                                <th>Product Name</th>
                                                <th>Date</th>
                                                <th>Quantity</th>
                                               
                                             
                                            </tr>
                                        </thead>
                                       
                                        <tbody>
                                            <?php 
                                                $rows = selectall('*' , 'offer' ,"ORDER BY id DESC");
                                                   $subtotal = 0;
                                                   $total = 0;
                                                foreach($rows as $row){
                                                    $phone =$row['userphone'];
                                                    $productname =$row['product_name'];
                                                    $username =select('name' ,'users' , "WHERE phone =$phone ");
                                                    $productname =select('name ,price' ,'products' , "WHERE name ='$productname'");

                                                    $subtotal= $productname['price'] * $row['Quantity'];
                                                    $total = $total +$subtotal;
                                                    
                                            ?>
                                            <a>
                                            <tr>
                                                <td><?php echo $row['id'];?></td>
                                                <td><?php echo $username['name'];?></td>
                                                <td><?php echo $productname['name'];?></td>
                                                <td><?php echo $row['date'];?></td>
                                                <td><?php echo $row['Quantity'];?></td>
                                               
                                            
                                              
                                            </tr>
                                            </a>
                                                <?php }?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th></th>
                                                <th></th>
                                                <th>Total =</th>
                                                <th><?php echo '$'.$total;?></th>
                                                
                                            </tr>
                                        </tfoot>
                                       
                                    </table>
                                    <form method="post">
                                                <button class="btn btn-info btn-block" name="clear">Clear all Offers</button>
                                        </form> 
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                        <?php
                    
                include "assets/includes/footer.php";
               ?>

<?php ob_end_flush();
}else{
    header("Location: login.php");
}
?>
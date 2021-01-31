<?php

session_start(); 
include "connect.php";
include "assets/includes/functions.php";
if(isset($_SESSION['username'])){
    header('Location: index.php');
}

        if(isset($_POST['submit'])){

            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass =$_POST['pass'];

            if(strlen($name) < 4){
                
                    echo "<div class ='alert alert-danger container'>name must be more than 4 chrecture</div>";
                    header("Refresh:2; url=register.php");
                    exit();
            }elseif(strlen($pass) < 6){
               
                echo "<div class ='alert alert-danger container'>password must be more than 5 numbers not ".strlen($pass)." number</div>";
                header("Refresh:2; url=register.php");
                exit();
            }else{
                $stmt=$con->prepare("INSERT INTO admin (username ,email , password) 
                VALUES (:zusername ,:zemail , :zpassword)");
                    $stmt->execute(array(
                        'zusername'   =>  $name,
                        'zemail'   =>  $email,
                        'zpassword' => $pass,
                    
                    ));
                    echo "<div class ='alert alert-danger container'>Pending to submit Your Account</div>";
                    header("Refresh:2; url=login.php");
                    exit();
            }
        }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Register</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Create Account</h3></div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputFirstName">User Name</label>
                                                        <input class="form-control py-4" name="name" id="inputFirstName" type="text" placeholder="Enter Your Name" required />
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="form-group">
                                                <label class="small mb-1" for="inputEmailAddress">Email</label>
                                                <input class="form-control py-4" name="email" id="inputEmailAddress" type="email" aria-describedby="emailHelp" placeholder="Enter email address" required/>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="small mb-1" for="inputPassword">Password</label>
                                                        <input class="form-control py-4" name="pass" id="inputPassword" type="password" placeholder="Enter password" required />
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block" name="submit">Create Account</button></div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="small"><a href="login.php">Have an account? Go to login</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>

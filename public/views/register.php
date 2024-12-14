<?php 
    $pageTitle = "Register";
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php' ?>
<body>
    <div class="container">
        <div class="d-flex justify-content-center align-items-center vh-100">
            <div class="row w-100">
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center text-center">
                    <img src="/public/images/WattoDo.svg" alt="WattoDo Logo" width="200" height="200">
                    <h2>WattoDo</h2>
                    <p class="text-muted">Your ultimate to-do list app to stay organized and productive.</p>
                </div>
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <div class="form-container card shadow-sm w-100 mx-3">
                        <div class="d-flex align-items-center px-3 pt-3">
                            <i class="fa-solid fa-user-plus fa-2x me-2"></i>
                            <h4 class="mt-2">REGISTER</h4>
                        </div>
                        <div class="px-3 pb-2">
                            <hr class="divider">
                            <form id="registerForm">

                                <div class="my-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" class="form-control" name="username" placeholder="Enter your username">
                                </div>
                                <div class="my-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" id="name" class="form-control" name="name" placeholder="Enter your full name">
                                </div>
                                <div class="my-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password">
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-main">Register</button>
                                </div>
                            </form>
                            <div class="text-center my-3">
                                <p class="mb-0">Already registered? <a href="/login" class="text-main text-decoration-none">Sign in</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

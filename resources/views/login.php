<!doctype html>
<html lang="en">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="<?= config('app.public_path') ?>assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/libs/css/style.css">
    <link rel="stylesheet" href="<?= config('app.public_path') ?>assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <style>
    html,
    body {
        height: 100%;
    }

    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    </style>
</head>

<body>
    <!-- ============================================================== -->
    <!-- login page  -->
    <!-- ============================================================== -->
    <div class="splash-container">
        <div class="card ">
            <div class="card-header text-center"><a href="../index.html"><img class="logo-img" src="<?= config('app.public_path') ?>/assets/images/logo.png" alt="logo"></a><span class="splash-description">Please enter your user information.</span></div>
            <div class="card-body">
                <form id="signIn" action="login" method="post">
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="username" id="username" type="text" placeholder="Username" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input class="form-control form-control-lg" name="password" id="password" type="password" placeholder="Password">
                    </div>
                    <?php echo csrf_field() ?>
                    <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Remember Me</span>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
            </div>
            <div class="card-footer bg-white p-0  ">
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="<?php echo url('/register') ?>" class="footer-link">Create An Account</a></div>
                <div class="card-footer-item card-footer-item-bordered">
                    <a href="#" class="footer-link">Forgot Password</a>
                </div>
            </div>
        </div>
    </div>
  
    <!-- ============================================================== -->
    <!-- end login page  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="<?= config('app.public_path') ?>assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= config('app.public_path') ?>assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        $(document).ready(function() {
            $("#signIn").submit(function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "<?= config('app.url') ?>login",
                    data: formData,
                    processData: false, // Prevent jQuery from converting the form data into a query string
                    contentType: false, // Let the browser set the correct content type (multipart/form-data)
                    success: function(response) {
                        Swal.fire({
                            icon: response['status'],
                            text: response['message'],
                        }).then(function() {
                            if(response['status'] == 'success' && response['redirect'] !== null) {
                                window.location.href = response['redirect'];
                            }else if (response['status'] === 'success') {
                                window.location.href = "<?php echo url(); ?>";
                            }
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("Error occurred: ", textStatus, errorThrown, jqXHR);
                        Swal.fire({
                            icon: textStatus,
                            text: jqXHR.responseJSON.message,
                        })
                        // alert("Something went wrong. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
 
</html>
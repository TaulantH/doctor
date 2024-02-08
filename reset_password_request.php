<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .background{
            background-color: #252422;
            
        }
        body{
            background-color: #FFFCF2;
        }
        .color{
            color: #EB5E28 !important;
        }
        .loginBtn{
            background-color: #EB5E28;
            border: none;
        }
    </style>
</head>
<body>
<section class="vh-100 gradient-custom">
        <div class="container py-1 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card background text-black" style="border-radius: 1rem;">
                        <div class="card-body p-2 text-center" style="height:250px;">

                            <div class="mb-md-5 mt-md-4 pb-5">
                            <form method="post" action="reset_password.php">
                            <h2 class="fw-bold mb-2 text-uppercase text-white color">Password Reset Request</h2>


    <div class="form-outline form-white mb-4">
        <label for="email_or_username" class="form-label text-white color">Email or Username:</label>
        <input type="text" class="form-control" id="email_or_username" name="email_or_username" required>
        <button type="submit" class="btn btn-outline-dark btn-lg px-5 mt-3 loginBtn" name="reset_confirmation">Reset Password</button>
        </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>

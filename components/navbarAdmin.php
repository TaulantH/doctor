<?php
   //check session
    

    if(isset($_SESSION['user'])){
        header("Location: ../login.php");
    }
    if(!isset($_SESSION['user']) && !isset($_SESSION['adm'])){
        header("Location: ../login.php");
    }

    $doctorId = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : 1; // Set a default value, change as needed

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= include_once "brand.php"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
   *{
            padding: 0;
            margin: 0;
            text-decoration: none;
            list-style: none;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        nav{
            background: white;
            height: 80px;
            width: 100%;
            border-bottom: 2px solid #EB5E28;
            position: sticky;
            top: 0;
            z-index: 100; 
        }


        label.logo{
            color: #FFFCF2;
            font-size: 35px;
            line-height:80px;
            padding: 0 100px;
            font-weight: bold;
        }

        nav ul{
            float: right;
            margin-right: 100px;
 
        }

        nav ul li{
            display: inline-block;
            line-height:80px;
            margin: 0px 10px;
        }

        nav ul li a {
            color: black;
            font-size: 17px;
        }

        a:hover{
            color: #CCC5B9;
            transition: 0s;
            cursor: pointer;
            border-bottom: 1px solid #EB5E28;
        }
        .checkBtn{
            font-size: 30px;
            color: #FFFCF2;
            float: right;
            line-height: 80px;
            margin-right: 40px;
            cursor: pointer;
            display: none;
        }
        #check{
            display: none;
        }
        @media (max-width: 992px){
            label.logo{
                font-size: 30px;
                padding-left: 50px;
            }
            nav ul li a{
                font-size: 16px;
            }
           nav{
            position:sticky;
            top: 0;
            z-index: 10;
           }
        }
        @media (max-width: 992px){
            .checkBtn{
                display: block;
            }
            
            nav ul{
                position: fixed;
                width: 100%;
                height: 100vh;
                background: #CCC5B9;
                top: 80px;
                left: -100%;
                text-align: center;
                transition: all .5s;
                z-index: 10;
                text-decoration: none;
            }
            nav ul li{
                display: block;
                margin: 50px 0;
                line-height: 30px;
                
            }
            nav ul li a{
                font-size: 20px;
                color: #252422;

            }
            .checkBtn{
                color: black;
            }
            a:hover{
                color: rgba(64, 61, 57, 0.7)
            }
            #check:checked ~ ul{
                left: 0%;
            }
            .fixed{
                position:fixed;
                top:0;
                z-index: 10;
            }
        }

    </style>
</head>
<body>
<!-- navbar -->

<nav>
        <ul>
			<li>
      <li><a class="nav-link textColor" href="../dashboard.php">Dashboard</a></li>
        </li>
     <!-- Modify the href attribute to include the doctor_id -->
     <li ><a class="nav-link textColor" href="../upComingAppointments.php?doctor_id=0">Upcoming Appointments</a></li>


        <li><a class="nav-link textColor" href="logout.php?logout">Logout</a></li>
        
      </ul>

    </div>

</nav>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
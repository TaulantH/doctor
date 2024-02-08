<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        *{
            padding:0;
            margin:0;
            box-sizing: border-box;
            list-style: none;
            
        }
        footer{
            background: #403D39;
        }
        .footerContainer{
            width: 100%;
            padding: 20px 30px 20px
        }
        .socialIcons{
            display: flex;
            justify-content: center;
        }
        .socialIcons a{
            text-decoration: none;
            padding: 10px;
            background-color:#FFFCF2;
            margin: 10px;
            border-radius:50%
        }
        .socialIcons a i{
            font-size: 2em;
            color: #252422;
            opacity: 0.9;
        }
        .socialIcons a:hover{
            background-color: #403D39;
            transition: 0.5s;
        }
        .socialIcons a:hover i{
            color: #FFFCF2;
            transition: 0.5s;
        }
        .footerNav{
            margin: 30px 0 10px 0;
        }
        .footerNav ul{
            display: flex;
            justify-content: center;
            list-style-type: none;
        }
        .footerNav ul li a{
            color: #FFFCF2;
            margin: 20px;
            text-decoration: none;
            font-size: 1.3em;
            opacity: 0.7;
            transition: 0.5s;
        }
        .footerNav ul li a:hover{
            opacity: 1;
        }
        .footerBottom{
            background: linear-gradient(#403D39, #252422);
            text-align:center;
            padding:20px;
        }
        .footerBottom p{
            color: #FFFCF2;
        }
        .designer{
            color: #FFFCF2;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 400;
            margin: 0px 5px;
        }
        @media (max-width: 700px){
            .footerNav ul{
                flex-direction: column;
            }
            .footerNav ul li{
                width: 100%;
                text-align: center;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
<footer>    
    <div class="footerContainer">
        
        <div class="socialIcons">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-instagram"></i></a>
            <a href=""><i class="fa-brands fa-twitter"></i></a>
            <a href=""><i class="fa-brands fa-google-plus"></i></a>
            <a href=""><i class="fa-brands fa-youtube"></i></a>
        </div>
        <div class="footerNav">
            <ul>
                <li><a href="//<?php echo $_SERVER['HTTP_HOST'];?>/index.php">Home</a></li>
                <li><a href="//<?php echo $_SERVER['HTTP_HOST'];?>/product.php">Products</a></li>
                <li><a href="//<?php echo $_SERVER['HTTP_HOST'];?>/about.php">About</a></li>
                <li><a href="//<?php echo $_SERVER['HTTP_HOST'];?>/contact.php">Contact</a></li>
            </ul>
        </div>
        
    </div>
    <div class="footerBottom">
            <p>Copyright &copy;2023; Created by <span class="designer">Taulant Hoxha<span></p>
        </div>
</footer>    
</body>
</html>
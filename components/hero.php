<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero</title>
    <style>
        
        .hero{
            width:100%;
            height:100%;
            position: relative;
        }
        .imgLeft{
            position: absolute;
            width:100%;
            height:100%;
            background-image: url(https://cdn.dribbble.com/userupload/10095027/file/original-6108206ceaf67ee3efaea89a814ab5ad.jpg?resize=752x);
            background-size: cover;
            background-repeat: no-repeat;
            background-position:center;
        }

        .containerHero{
            position:absolute;
            left:50%;
            top: 50%;
            transform: translate(-50%, -50%);
            /* height: 30vh; */
            width: 40vw;
            background: #5dc0d7;
            opacity: 0.9;
        }
        .containerBorder{
            height:95%;
            margin: 1%;
            border: 1px solid #EB5E28;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items:center;
            
        }
        .containerBorder h1{
            color: black;
            font-size: 70px;
            margin: 0px 5px 0px 5px
        }
        .name{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items:center;
            padding-bottom: 10px
        }
        .welcome{
            display: flex;
            justify-content: center;
            align-items:center;
            text-align:center;
        }
        .text{
            color: #CCC5B9;
            font-size: 30px;
            border-top: 1px solid #EB5E28;
            /* padding-top: 10px; */
            /* padding-bottom: 10px */
            margin: 0px
            
        }
        @media(max-width: 567px){
            .containerHero{
                width: 90%;
            }
            .containerBorder h1{
            color: black;
            font-size: 40px;
            margin: 0px 5px 0px 5px
        } }
        @media(max-width: 350px){
            .containerHero{
                width: 90%;
            }
            .containerBorder h1{
            color: black;
            font-size: 32px;
            margin: 0px 5px 0px 5px
        }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="imgLeft">
        </div>
        <div class="imgRight">
        </div>
        <div class="containerHero">
            <div class="containerBorder">
                <div class="name">
                    <div class="welcome"><h1>Welcome to</h1></div>
                    <h1>Hospital Kosova</h1> 
                    </div>
                
            </div>
            
        </div>
    </div>
</body>
</html>

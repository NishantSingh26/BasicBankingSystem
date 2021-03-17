<?php
include 'config.php';

if(isset($_POST['submit']))
{
    $from = $_GET['id'];
    $toUser = $_POST['to'];
    $amnt = $_POST['amount'];

    $sql = "SELECT * from users where id=$from";
    $query = mysqli_query($conn,$sql);
    $sql1 = mysqli_fetch_array($query); 

    $sql = "SELECT * from users where id=$toUser";
    $query = mysqli_query($conn,$sql);
    $sql2 = mysqli_fetch_array($query);

  
 if($amnt > $sql1['balance'])
    {

        echo '<script type="text/javascript">';
        echo ' alert("Insufficient Balance")';  
        echo '</script>';
    }

     else if($amnt == 0){
         echo "<script type='text/javascript'>alert('Enter Amount Greater than Zero');
    </script>";
     }
    else {

      
        $newCredit = $sql1['balance'] - $amnt;
        $sql = "UPDATE users set balance=$newCredit where id=$from";
        mysqli_query($conn,$sql);



        $newCredit = $sql2['balance'] + $amnt;
        $sql = "UPDATE users set balance=$newCredit where id=$toUser";
        mysqli_query($conn,$sql);

        $sender = $sql1['name'];
        $receiver = $sql2['name'];
        $sql = "INSERT INTO transfer(`sender`, `receiver`, `transferred_amount`) VALUES ('$sender','$receiver','$amnt')";
        $tns=mysqli_query($conn,$sql);
        if($tns){
           echo "<script type='text/javascript'>
                    alert('Transaction Successfull!');
                    window.location='transactionhistory.php';
                </script>";
        }
        $newCredit= 0;
        $amnt =0;
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Transfer</title>
    <link rel="shortcut icon" href="sign.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
    .logo-text{
	font-size:25px;
    color: black;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    font-weight: bold;
	  
    }
    .nav-link1{
      color: navy;
      font-weight: bold;
      font-size: 25px;
     	  
    }
    .list-customer{
      padding-left: 1100px;
    }

    .nav-link1:hover{
      color:#82EEFD;
      text-decoration: none;
    }
    .button {
      background-color: #2887c8;
      border: none;
      color: white;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 18px;
      margin: 0px 2px;
      border-radius: 25px;
    }
    .button:hover{
      background-color: #296d98;
      color: #10002b;
    }
    .button:active{
      background-color: #2ec4b6;
    }
    h2{
      text-align: center;
      margin-top: 20px;
    }
	.form-control{
	color:black;
    width: auto;
	}
	.form-control.hover{
		color:black;
	}

    h2
    {
        text-decoration: underline;
        font-family: sans-serif;
        color: #03045e;
        font-weight: bold;
    }
    footer p{
        clear: both;
        position: relative;
        height:50px;
        margin-top: 30px;
    }   
    .flat-table-1 {
		background: #336ca6;
	}
	.flat-table-1 tr:hover {
		background: rgba(0,0,0,0.19);
	}
    table{
		text-align:center;
		margin-left: auto;
		margin-right: auto;
		border:4px solid gray;
		border-collapse:collapse;
	}
    th{
		color:violet;
		font-size:18px;
		padding:15px;
	}
	td{
		font-size:18px;
		color: #92c6df;
		padding: 8px 10px 7px 12px;
	}
	tr{
		transition: background 0.3s, box-shadow 0.3s;
	}
	th,td{
		border-collapse:collapse;
		border:2px groove gray;
	}
    
    </style>
</head>


<body style="background-image:linear-gradient( #0066cc 10%, #ccffff 100%); ">
<table>

<?php
  include 'navbar.php';
?>

<ul class="navbar-nav">
<li class="list-customer">
</li>
</ul>
</nav>
    <div class="container divStyle">
        <h2>Transfer Details</h2>
       
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM  users where id=$sid";
                $query=mysqli_query($conn,$sql);
                if(!$query)
                {
                    echo "Error ".$sql."<br/>".mysqli_error($conn);
                }
                $rows=mysqli_fetch_array($query);
            ?>
            <form method="post" name="tcredit" class="tabletext" ><br/>
        

        
        <div class="view">
            <table class="flat-table-1"  >
                <tr>
                  
                    <th>Name</th>
                    <td><?php echo $rows['name'] ?></td>
                </tr>
                <tr>
                    
                    <th>Email</th>
                    <td><?php echo $rows['email'] ?></td>
                </tr>
                <tr>    
                    <th>Account Balance</th>
                    <td><?php echo $rows['balance'] ?></td>
                </tr>
                
            </table>
        </div>
        <br/><br/><br/>
        <center><label>TO:</label>
        <select class=" form-control"   name="to" style="margin-bottom:2%;" >
            <option value="" disabled selected>Choose </option>
            <?php
                include 'config.php';
                $sid=$_GET['id'];
                $sql = "SELECT * FROM users where id!=$sid";
                $query=mysqli_query($conn,$sql);
                if(!$query)
                {
                    echo "Error ".$sql."<br/>".mysqli_error($conn);
                }
                while($rows = mysqli_fetch_array($query)) {
            ?>
                <option class="table text-center table-striped " value="<?php echo $rows['id'];?>" >

                    <?php echo $rows['name'] ;?>
                   (Balance: <?php echo $rows['balance'] ;?>)
                    <!--(Credits:
                    <?php echo $rows['credits'] ;?> )-->

                </option>
            <?php
                }
            ?>
        </select>
            <label>AMOUNT:</label>
            <input type="number" id="amm" class="form-control" name="amount" min="0" required  />  <br/><br/>
                <div class="text-center btn3" >
            <button class="button" name="submit" type="submit" id="myBtn" style="margin:8px"; style="background-color : #2887c8;">Proceed</button> 
			<a href="viewusers.php">
    <button class="button" style="background-color : #2887c8;" >Back</button>
	</a>
	<a href="index.php">
    <button class="button" style="background-color : #2887c8;">Home</button>
	</a>
            </div></center>
            
            
        </form>
    </div>
    <center><div >
	
</div></center><br>
    <center><div >
	
</div></center>
</table>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<footer class="footer1" align="center"> <br>
    <div  style="height: 60%" >
    <p>&copy 2021 Made by <b>Nishant</b></p>
    </div>
  </footer>
</body>
</html>
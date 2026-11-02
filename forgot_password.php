<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Forgot Password | SGBIT Alumni</title>

<?php include_once "db_config.php"; ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .forgot_container {
        background: rgba(255, 255, 255, 0.95);
        padding: 3rem 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        width: 380px;
        text-align: center;
        animation: fadeInUp 0.8s ease;
    }

    h1 {
        font-size: 2.2rem;
        color: #667eea;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    input {
        padding: 0.8rem 1rem;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 1rem;
        transition: all 0.3s;
        outline: none;
    }

    input:focus {
        border-color: #667eea;
        box-shadow: 0 0 8px rgba(102,126,234,0.4);
    }

    button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.8rem;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102,126,234,0.4);
    }

    a {
        display: inline-block;
        margin-top: 1rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    a:hover {
        color: #764ba2;
    }

    .links {
        margin-top: 1rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
        .forgot_container {
            width: 90%;
            padding: 2rem;
        }
    }
</style>
</head>

<body>
<div class="forgot_container">
    <h1>Retrieve Password</h1>
    <form class="forgot_form" method="post">
        <input type="text" placeholder="Registration Number" name="forgot_userid" required>
        <input type="email" placeholder="Email" name="forgot_password" required>
        <button type="submit" name="retrieve">Retrieve Password</button>

        <div class="links">
            <a href="index.php"> Home</a> &nbsp;&nbsp;&nbsp;
            <a href="login.php"> Login</a>
        </div>
    </form>
</div>
</body>

<?php
if(isset($_POST['retrieve']))
{
    $id = $_POST['forgot_userid'];
    $email = $_POST['forgot_password'];

    $sql = "SELECT pi_register FROM alumniinfo WHERE pi_register='$id' AND pi_email='$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $temp = $row['pi_register'];
            $sql2 = "SELECT al_password FROM alumnimember WHERE pi_register='$temp'";
            $result2 = $conn->query($sql2);
            while($row2 = $result2->fetch_assoc()) 
            {
                echo "<script>alert('Your password is: ".$row2["al_password"]."');</script>";
            }
        }
    }
    else
    {
        echo "<script>alert('ERROR: Please mail our administrator help_admin@techno.com to retrieve your password.');</script>";
    }
}
?>
</html>

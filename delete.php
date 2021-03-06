<?php
// Process delete operation after confirmation
session_start();

if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
    $username = validateData($_COOKIE['username']);
    $password = validateData($_COOKIE['password']);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    $tmp = $result->fetch_all(MYSQLI_ASSOC);
    if (count($tmp) == 0) {
        $_SESSION['class'] = "alert alert-danger";
        $_SESSION['message'] = "Authentication error";
        header("Location: index.php");
        exit();
    }
    $_SESSION['username'] = $username;

} else {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit();
    }
}

$idGet = trim($_GET["id"]);
if(isset($_GET["id"]) && !empty($idGet)) {
    // Include config file
    require_once "config.php";

    // Prepare a select statement
    $id = trim($_GET["id"]);
    $sql = "SELECT * FROM employees WHERE id = $id";
    $result = $conn->query($sql);
    $temp = $result->fetch_all(MYSQLI_ASSOC);
    if (count($temp) == 1) {
        $row = $temp[0];
        $name = $row["name"];
        $address = $row["address"];
        $salary = $row["salary"];
    } else {
        // URL doesn't contain valid id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}

if(isset($_POST["id"]) && !empty($_POST["id"])) {
    // Include config file
    require_once "config.php";

    // Prepare a delete statement
    $id = trim($_POST["id"]);
    $sql = "DELETE FROM employees WHERE id = $id";
    $result = $conn->query($sql);
    if ($result) {
        $_SESSION['class'] = "alert alert-success";
        $_SESSION['message'] = "Deleted record successful";
        header("location: home.php");
        exit();
    } else {
        header("location: error.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Delete Record <?php echo $idGet; ?></h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger fade in">
                        <input type="hidden" name="id" value="<?php echo $idGet; ?>"/>
                        <p>Are you sure you want to delete this record <?php echo $idGet; ?>?</p><br>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="index.php" class="btn btn-default">No</a>
                        </p>
                    </div>
                </form>
                <div class="form-group">
                    <h3><label>Name</label></h3>
                    <h4><p class="form-control-static"><?php echo $row["name"]; ?></p></h4>
                </div>
                <div class="form-group">
                    <h3><label>Address</label></h3>
                    <h4><p class="form-control-static"><?php echo $row["address"]; ?></p></h4>
                </div>
                <div class="form-group">
                    <h3><label>Salary</label></h3>
                    <h4><p class="form-control-static"><?php echo number_format($row["salary"], 2, ',', ' '); ?></p></h4>
                </div>
                <p><a href="home.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>


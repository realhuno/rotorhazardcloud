<?php
/*
Author: Javed Ur Rehman
Website: http://www.allphptricks.com/
*/

include("auth.php"); //include auth.php file on all secure pages ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Welcome Home</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<div class="form">
<p>Welcome <?php echo $_SESSION['username']; ?>!</p>
<p>This is secure area.</p>
<a href="index.php?page=tracker">Tracker</a>&nbsp;&nbsp;
<a href="index.php?page=groups">Groups</a>&nbsp;&nbsp;
<a href="index.php?page=groups2">Archiv</a>&nbsp;&nbsp;
<a href="index.php?page=logout">Logout</a>&nbsp;&nbsp;
<a href="index.php?page=users">Pilots</a>&nbsp;&nbsp;
</div>
<?php
$current_page = isset($_GET['page']) ? $_GET['page'] : null;

    switch ($current_page) {
        case ('tracker'):
            include 'tracker.php';
            break;
        case ('groups'):
            include 'groups.php';
            break;
		 case ('groups2'):
            include 'groups_server2.php';
            break;
        case ('logout'):
            include 'logout.php';
            break;
		case ('users'):
            include 'users.php';
            break;
        default:
            include 'dashboard.php';
    }

?>


</body>
</html>

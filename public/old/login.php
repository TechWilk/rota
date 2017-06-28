<?php namespace TechWilk\Rota;

use DateInterval;
use DateTime;

include('includes/config.php');
include('includes/functions.php');

// we must never forget to start the session
session_start();

// check if user is logged in - if so, redirect to homepage

if (isset($_SESSION['is_logged_in']) || $_SESSION['db_is_logged_in'] == true) {
    header("Location: index.php");
}

function isPasswordCorrectUsingMD5WithUsername($username, $plainTextPassword)
{
    $username = mysqli_real_escape_string(db(), $username);
    $sql = "SELECT password FROM cr_users WHERE username = '$username'";
    $result = mysqli_query(db(), $sql) or die(mysqli_error(db()));
    
    $ob = mysqli_fetch_object($result);
    
    $passwordHash = $ob->password;

    if (md5($plainTextPassword) == $passwordHash) {
        return true;
    } else {
        return false;
    }
}


// check login credentials

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = mysqli_real_escape_string(db(), $username);

    // check number of attempts

    if (!numberOfLoginAttemptsIsOk($username, $_SERVER['REMOTE_ADDR'])) {
        $message = "Too many failed attempts for your account. Please wait 15 minutes.";
        logFailedLoginAttempt($username, $_SERVER['REMOTE_ADDR']);
    }

    // check password, if number of attempts is ok

    elseif (isPasswordCorrectWithUsername($username, $password)) {
        setSessionAndRedirect($username);
        $message = "correct";
    } elseif (isPasswordCorrectUsingMD5WithUsername($username, $password)) {
        updateDatabase(); // check for db updates
        $userId = getIdWithUsername($username);
        $_SESSION['userid'] = $userId; // allow to insert statistic
        forceChangePassword($userId, $password);
        insertStatistics("user", __FILE__, "password updated to bcrypt on login", null, $_SERVER['HTTP_USER_AGENT']);
        setSessionAndRedirect($username);
        $message = "correct and updated";
    } else {
        $message = "Username or password incorrect";
        logFailedLoginAttempt($username, $_SERVER['REMOTE_ADDR']);
    }
}


if (isset($_GET['username'])) {
    $username=$_GET['username'];
} elseif (isset($_GET['loginname'])) {
    $username=$_GET['loginname'];
} elseif (!empty($username)) {
    $username = $username;
} else {
    $username="";
}



/* ---- Login page ---- */
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Login | <?php echo $owner; ?></title>
    <link rel="stylesheet" href="css/login.css">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  </head>
  <body class="align">
    <div class="site__container">
      <div class="grid__container">
				<img src="images/logo.png" alt="<?php echo $owner ?> logo" width="120" height="120" />
				<?php if ($config['auth']['facebook']['enabled'] == true): ?>
				<form action="fb-login.php" method="get" class="form form--login">
					<div class="form__field">
            <input type="submit" value="Sign In with Facebook">
          </div>
        </form>
				<p class="text--center">Or</p>
				<?php endif; ?>
        <form action="login.php" method="post" class="form form--login">
          <div class="form__field">
            <label class="fa fa-user" for="login__username"><span class="hidden">Username</span></label>
            <input name="username" id="login__username" type="text" class="form__input" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" autosuggest="off" placeholder="Username" value="<?php echo $username; ?>" required>
          </div>
          <div class="form__field">
            <label class="fa fa-lock" for="login__password"><span class="hidden">Password</span></label>
            <input name="password" id="login__password" type="password" class="form__input" placeholder="Password" required>
          </div>
					<?php if (!empty($message)): echo "<p>".$message."</p>"; endif; ?>
          <div class="form__field">
            <input type="submit" value="Sign In">
          </div>
        </form>
        <p class="text--center">Not a member? <a href="mailto:<?php echo siteSettings()->getAdminEmailAddress() ?>">Request access</a> <span class="fontawesome-arrow-right"></span></p>
      </div>
  </div>
</body>
</html>
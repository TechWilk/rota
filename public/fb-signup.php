<?php
// Include files, including the database connection
include('includes/config.php');
include('includes/functions.php');

if ($config['auth']['facebook']['enabled'] != true) {
    header('Location: index.php');
    exit;
}

session_start();


// Handle POST from form

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
  
    createPendingUser($_POST['id'], $firstName, $lastName, $email, 'facebook');
  
    echo "Account requested.  You shall recieve an email once your account request has been approved.";
    exit;
}

$fb = new Facebook\Facebook([
  'app_id' => $config['auth']['facebook']['appId'],
  'app_secret' => $config['auth']['facebook']['appSecret'],
  'default_graph_version' => 'v2.2',
  ]);
  
$accessToken = $_SESSION['fb_access_token'];
$_SESSION['foo'] = 'bar';

// fetch user info

try {
    // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email', $accessToken);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();






// login without creating signup form if account already linked

if (userExistsWithSocialIdForPlatform($user->getId(), 'facebook')) {
    // login
  setSessionAndRedirect(getUsernameWithSocialId($user->getId()));
    exit;
}

// Split first and last names from FB
$names = explode(' ', $user->getName(), 2);





// ~~~~~~~~~~~~ Presentation ~~~~~~~~~~~~
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Signup | <?php echo $owner; ?></title>
    <link rel="stylesheet" href="css/login.css">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  </head>
  <body class="align">
    <div class="site__container">
      <div class="grid__container">
        <p>Confirm your details</p>
        <form action="#" method="post" class="form form--login">
        <input type="hidden" name="id" value="<?php echo $user->getId() ?>" />
          <div class="form__field">
            <label class="fa fa-user" for="firstName"><span class="hidden">First name</span></label>
            <input name="firstName" id="firstName" type="text" class="form__input" placeholder="First Name" value="<?php echo $names[0]; ?>" required>
          </div>
          <div class="form__field">
            <label class="fa fa-user" for="lastName"><span class="hidden">Last name</span></label>
            <input name="lastName" id="lastName" type="text" class="form__input" placeholder="Last Name" value="<?php echo $names[1]; ?>" required>
          </div>
          <div class="form__field">
            <label class="fa fa-envelope-o" for="email"><span class="hidden">Email address</span></label>
            <input name="email" id="email" type="text" class="form__input" placeholder="Email address" value="<?php echo $user->getEmail(); ?>" required>
          </div>
					<?php if (!empty($message)): echo "<p>".$message."</p>"; endif; ?>
          <div class="form__field">
            <input type="submit" value="Request access">
          </div>
        </form>
      </div>
  </div>
</body>
</html>
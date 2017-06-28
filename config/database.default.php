<?php

# Only a MySQL database can be used with this software.

# Enter the name of your Database
$config['db']['dbname'] = 'churchrota';

# Enter the username
$config['db']['user'] = 'username';

# Enter the password
$config["db"]["pass"] = 'password';

# Unless your host tells you differently, this should remain as 'localhost'
$config["db"]["host"] = 'localhost';

# Don't change unless multiple installs are required in the same db
# This value cannot be changed once installed, unless you want something to break!
$config["db"]["prefix"] = "cr_";


# ------------------------ RENAME FILE: ------------------------
#  rename this file to "database.php"

# ---------------------- CONFIG COMPLETE: ----------------------
#  copy all files to your webserver and navigate to "install.php"

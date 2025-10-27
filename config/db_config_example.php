echo "<?php
\$servername = 'localhost';
\$username = 'YOUR_USERNAME';
\$password = 'YOUR_PASSWORD';
\$dbname = 'YOUR_DATABASE';
\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);
if (\$conn->connect_error) {
    die('Connection failed: ' . \$conn->connect_error);
}
?>" > config/db_config.example.php

git add config/db_config.example.php
git commit -m "Add db_config example template"
git push origin main

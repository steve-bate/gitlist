<html>
<head>
  <title>Create Repository</title>
        <link rel="stylesheet" type="text/css" href="/gitlist/themes/default/css/style.css">

  <style>
label {
  width: 100px;
  display: inline-block;
  margin-bottom: 8px;
}

input[type='text'] {
  width: 30em;
}
  </style>
</head>
<body>
<h1>Create Repository</h1>
<form>
<label for="name">Name:</label><input name="name" type="text"><br>
<label for="description">Description:</label><input name="description" type="text"/><br>
<input type="submit">
</form>

<?php
require_once("../src/Config.php");
$config = \GitList\Config::fromFile("../config.ini");
$repository = $config->get('git', 'repositories')[0];
$repository_url_prefix = $config->get('git', 'repository_url_prefix');

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

if (isset($_GET['name']) && $_GET['name'] != "") {
  $name = $_GET['name'];
  $name = preg_replace('/\.*\//', "", $name);
  $desc = $_GET['description'];

  
  $cmd = escapeshellcmd("git-create-repository " . $name);
  if ($desc != "") {
    $cmd = $cmd . " " . $desc;
  }
  echo "<hr>";
  $dirname = $name . ".git";
  chdir($repository);
  echo "Creating directory: " . $dirname . "<br>";
  mkdir($dirname);
  chdir($dirname);
  echo "Initializing git repository...<br>";
  echo shell_exec('git --bare init');
  echo "<br>";
  if ($desc) {
    echo "Writing description...<br>";
    $desc_file = fopen("description", "w");
    fwrite($desc_file, $desc);
    fclose($desc_file);  
  }
  echo "URL: " . $repository_url_prefix . $dirname . "<br>";
  echo "Done<br>";
}
?>
<hr>
<a href="/gitlist/">gitlist</a>
</body>
</html>

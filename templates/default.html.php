<?php
/**
 * @file Default template file.
 */
?>

<html>

<body>
    <h1><?php echo $app->config->site->title; ?></h1>
    <h2><?php echo $app->config->site->subtitle; ?></h2>
    <p><?php var_dump($data); ?></p>
</body>

</html>
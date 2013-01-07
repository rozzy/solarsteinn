<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
    <?php
    require 'solarsteinn.class.php';
    /* Non-static method: */
    //$c = new Solarsteinn();
    //echo $c->compile(time(), $zones, '%e %B, *');

    /* Static method: */
    echo Solarsteinn::compile("-2 hours");
?>
</body>
</html>
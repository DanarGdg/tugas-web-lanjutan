<html>
<body>
    <h1> Operator logika</h1>
<p>
<?php
$b = 4 != 4;
$c = 3+7 == 10;
$a = ($b and $c);
Echo "\$a=$a <br>";
$a = ($b or $c);
Echo "\$a=$a <br>";
$a = ($b xor $c);
Echo "\$a=$a <br>";
$a = (!$b or $c);
Echo "\$a=$a <br>";
$a = $b && $c;
Echo "\$a=$a <br>";
$a = $b || $c;
Echo "\$a=$a <br>";

?>
</p>
</body>
</html>
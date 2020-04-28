<?php

$actID = $_GET['activity'];
?>
<a href="#">
    <img src="cottagePics/MoonRiver1.jpg">
</a>
<form action="../includes/process.php?cottage=<?php echo $actID ?>" method="POST">
<button type="submit" name="purchase">Purchase</button>
</form>
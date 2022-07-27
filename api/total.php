<?php
include_once "../functionall.php";
$total->save(['id'=>1,'total'=>$_POST['total']]);
to("../back?do=total");
?>
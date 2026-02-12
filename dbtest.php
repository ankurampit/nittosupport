<?php
$link = mysqli_connect('localhost', 'nittosupport_demoU', '@r=hxOp*AC3!D4{E', 'nittosupport_demo');
if (!$link) {
    die('❌ MySQL error: ' . mysqli_connect_error());
}
echo '✅ Database connection successful!';
mysqli_close($link);
?>

<?php
session_start();
unset($_SESSION["username"]);
session_destroy();
?>

<script>
    alert("You are now logged out");
    window.location.href = "index.php";
</script>

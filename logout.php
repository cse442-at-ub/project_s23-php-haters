<?php
    session_start();

    // Set $_SESSION to an empty array
    $_SESSION = array();

    // Destroy the session
    session_destroy();
    header("location: login.php");
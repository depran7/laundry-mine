<?php 
require 'functions.php';
session_start();
logout();
header("Location: login.php");
exit;
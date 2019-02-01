<?php
/*
Plugin Name: Job Alert
Plugin URI: https://www.hoppenbrouwerstechniek.nl
Author URI: https://www.hoppenbrouwerstechniek.nl
Description: Job Alerts for WP Job Manager
Version: 1.0.0
Author: Marnick van den Brand
Text Domain: Job Alert
*/

require 'vendor/autoload.php';

require "config.php";

$tlcJobAlert = new TlcJobAlert\Main();
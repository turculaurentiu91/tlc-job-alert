<?php
/*
Plugin Name: TLC Job Alert
Plugin URI: https://www.fiverr.com/laurentiuturcu
Author URI: https://www.fiverr.com/laurentiuturcu
Description: Alerts for WP Job Manager
Version: 1.0.0
Author: Turcu Laurentiu
Text Domain: tlc-job-alerts
*/

require 'vendor/autoload.php';

require "config.php";

$tlcJobAlert = new TlcJobAlert\Main();
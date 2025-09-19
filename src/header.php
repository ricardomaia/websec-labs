<?php
defined('APP') or die('Access denied');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

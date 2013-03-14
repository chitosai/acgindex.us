<?php

include('accounts.php');
include('config.php');

function utility_error($title, $detail = '') {
	echo $title . ' <br><br>' . $detail;
	exit();
}
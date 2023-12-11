<?php
	include('../basedados/basedados.h');

	//destroir as sessões
	session_start();
	session_destroy();

	header("Location: index.php");
?>
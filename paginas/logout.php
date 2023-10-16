<?php
	include('../basedados/basedados.h');

	//destroir as sessões
	session_start();
	session_destroy();

	//fechar a conecção à base de dados
	mysqli_close($conn);

	header("refresh:0;url=index.php");
?>
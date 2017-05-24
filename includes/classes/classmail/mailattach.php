<?php
require("class.phpmailer.php");
$msg = "";
if ($_POST['action'] == "send") {
	$varname = $_FILES['archivo']['name'];
    $vartemp = $_FILES['archivo']['tmp_name'];
	
	$mail = new PHPMailer();
	$mail->Host = "localhost";
	$mail->From = "blog@unijimpe.net";
	$mail->FromName = "Blog Unijimpe";
	$mail->Subject = $_POST['asunto'];
	$mail->AddAddress($_POST['destino']);
	if ($varname != "") {
		$mail->AddAttachment($vartemp, $varname);
	}
	$body = "<strong>Mensaje</strong><br /><br />".$_POST['mensaje']."<br />";
	$body.= "<i>Enviado por http://blog.unijimpe.net</i>";
	$mail->Body = $body;
	$mail->IsHTML(true);
	$mail->Send();
	$msg = "Mensaje enviado correctamente";
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PHP Email Attach</title>
<link href="mailattach.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="descdet">
	<div class="borde">
	<strong class="subder">Env&iacute;ar Email con Adjuntos</strong><br />
	Ingresar los datos en el formulario. <br />
	<?php if ($msg != "") { ?><span class="conf"><?php echo $msg; ?></span><br /><?php } ?>
	<form action="mailattach.php" method="post" enctype="multipart/form-data">
	  <p>	  Destinatario<br />
	  <input type="text" name="destino" size="50">
	  Asunto<br />
	  <input type="text" name="asunto" size="50">
	  Adjunto<br />
	  <input type="file" name="archivo"  size="32">
	  Mensaje<br />
	  <textarea name="mensaje" cols="47" rows="8" wrap="virtual" id="mensaje"></textarea>
	  <input type="submit" name="btsend" class="boton" value="Enviar Email">
	  <input type="hidden" name="action" value="send" />
	  </p>
	</form>
	</div>
	</td>
  </tr>
</table>
</body>
</html>

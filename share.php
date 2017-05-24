<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Compartir</title>
<link href="theme/telegrow/share.css" rel="stylesheet" type="text/css" />
</head>


<?php
if (isset($_POST['comentario'])) {
$codigohtml = '

    <html>
    <head>
    <title>Un amigo te ha recomendado este producto</title>
    </head>
    
    '.$_POST['comentario'].'
    </body>

';

$email = $_POST['mail'];
$asunto = 'Un amigo te ha recomendado este producto';
$cabeceras = "From: info@telegrow.com\r\nContent-type: text/html\r\n";

mail($email,$asunto,$codigohtml,$cabeceras);
}
?>
<form id="form" name="form" method="post" action="">
  <h1><?php echo base64_decode($_GET['title'])?></h1>
  <p>
    <label for="mail">Enviar con nota a </label> 
    <input type="text" name="mail" id="mail" />
  </p>
  <p>
    <textarea name="comentario" id="comentario" cols="45" rows="5"><?php echo base64_decode($_GET['title'])?> <?php echo urldecode(base64_decode($_GET['url']))?></textarea>
  </p>
  <p>
  	<input type="submit" name="button" id="button" value="Enviar" />
  </p>
</form>
</body>
</html>
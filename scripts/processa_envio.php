<?php 
	// echo '<pre>';
	// print_r($_POST);
	// echo '</pre>';


	// Criando a Classe Mensagem
	
	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	class Mensagem {
		private $email = null;
		private $assunto = null;
		private $msg = null;
		public $status = [ 'status_envio' => null, 
						   'status_descricao' => ''];

		public function __get($att) {
			return $this->$att;
		}

		public function __set($att, $value){
			$this->$att = $value;
		}

		public function msgValida() {
			if(empty($this->email) || empty($this->assunto) || empty($this->msg)) {
				return false;
			}else {
				return true;
			}
		}

	}

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;



	//=========//
	$msg = new Mensagem();

	$msg->__set('email', $_POST['email']);
	$msg->__set('assunto', $_POST['assunto']);
	$msg->__set('msg', $_POST['msg']);

	// echo '<pre>';
	// print_r($msg);
	// echo '</pre>';

	// validando dados
	if(!$msg->msgValida()){
		// echo 'Mensagem invalida';
		header('Location: index.php?msg=invalida');
	}

	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
	    $mail->SMTPDebug = false;                      //Enable verbose debug output
	    $mail->isSMTP();                                            //Send using SMTP
	    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
	    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
	    $mail->Username   = 'email';                     //SMTP username
	    $mail->Password   = 'senha';                               //SMTP password
	    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
	    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

	    //Recipients
	    $mail->setFrom('mailerphp83@gmail.com', 'Web completo Remetente');
	    $mail->addAddress($msg->__get('email'));     //Add a recipient
	    //$mail->addAddress('ellen@example.com');               //Name is optional
	    //$mail->addReplyTo('info@example.com', 'Information');
	    //$mail->addCC('cc@example.com');
	    //$mail->addBCC('bcc@example.com');

	    //Attachments
	   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
	    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

	    //Content
	    $mail->isHTML(true);                                  //Set email format to HTML
	    $mail->Subject = $msg->__get('assunto');
	    $mail->Body    = $msg->__get('msg');
	    $mail->AltBody = 'É nescessário utilizar um Client que suporte HTML para visualizar todo o conteúdo desta mensagem!';

	    $mail->send();
	    $msg->status['status_envio'] = true;
	    $msg->status['status_descricao'] = 'E-mail enviado com Sucesso!';
	} catch (Exception $e) {
	    $msg->status['status_envio'] = false;
	    $msg->status['status_descricao'] = "Não foi possivel enviar o e-mail, por favor tente mais tarte. Mailer Error: {$mail->ErrorInfo}";
	}


?>


<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>

	<body>

		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">

					<?php if($msg->status['status_envio']) { ?>

						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $msg->status['status_descricao'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php }else{  ?>

						<div class="container">
							<h1 class="display-4 text-danger">Ops!</h1>
							<p><?= $msg->status['status_descricao'] ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>

	</body>
</html>

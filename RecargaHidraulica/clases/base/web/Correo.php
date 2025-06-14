<?php
require_once('base/externo/htmlMimeMail5/htmlMimeMail5.php');

class Correo {
    public function Correo() {
    }
    
    public function CorreoSimple($CONF,$remitente,$destinatario,$tema,$mensaje) {
        $mail=new htmlMimeMail5();
        
        $mail->setSMTPParams($CONF['correo']['host'], $CONF['correo']['puerto'], $CONF['correo']['dominio'], true, $CONF['correo']['usuario'], $CONF['correo']['contrasena']);

        $mail->setReturnPath($CONF['correo']['usuario']);
        $mail->setFrom(utf8_decode($remitente));

        $mail->setSubject(utf8_decode($tema));
        $mail->setText(utf8_decode($mensaje));

        return($mail->send(array($destinatario),'smtp'));
    }
    
    public function CorreoAdjunto($CONF,$remitente,$destinatarios,$asunto,$mensaje,$bcc=array(),$html=false,$adjuntos=array(),$path='',$extra=array()) {
        $mail=new htmlMimeMail5();
        
        $mail->setSMTPParams($CONF['correo']['host'], $CONF['correo']['puerto'], $CONF['correo']['dominio'], true, $CONF['correo']['usuario'], $CONF['correo']['contrasena']);
    
        $mail->setReturnPath(isset($extra['Return-Path']) ? $extra['Return-Path'] : $CONF['correo']['usuario']);
        foreach($extra AS $val=>$var) {
            if($val=='Return-Path') continue;
            $mail->setHeader($val,$var);
        }

        $mail->setFrom(utf8_decode($remitente));
        
        // Destinatario principal
        $para=$destinatarios[0];
        array_shift($destinatarios);
        
        // CCs
        $ccs=implode(",",$destinatarios);
        if($ccs) $mail->setCc($ccs);
        
        // BBCs
        $bccs=implode(",",$bcc);    
        if($bccs) $mail->setBcc($bccs);
        

        $mail->setSubject(utf8_decode($asunto));
        if($html) {
			$mail->setHTML(utf8_decode($mensaje),$path);
			$mail->setText(utf8_decode(strip_tags($mensaje))); // para que igual lleve parte de texto
		} else {
			$mail->setText(utf8_decode(strip_tags($mensaje))); // para que igual lleve parte de texto
		}
        
        for($i=0; $i<count($adjuntos); $i++ ) {
			$mail->addAttachment(new fileAttachment($adjuntos[$i]));
		}

        return($mail->send(array($para),'smtp'));
    }
}

?>

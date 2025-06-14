<?php
class EmailMessage {

    protected $connection;
    protected $messageNumber;
    
    public $bodyHTML = '';
    public $bodyPlain = '';
    public $attachments;
    
    public $getAttachments = true;
    
    public function __construct($connection, $messageNumber) {
    
        $this->connection = $connection;
        $this->messageNumber = $messageNumber;
        
    }

    public function fetch() {
        $structure = @imap_fetchstructure($this->connection, $this->messageNumber);
        if(!$structure) {
            return false;
        }
        else {
            if(isset($structure->parts)) $this->recurse($structure->parts);
                else $this->plano($structure);
            return true;
        }
        
    }
    
    /* GAEA */
    public function plano($structure) {
        if($part->type == 0) {
            if($part->subtype == 'PLAIN') {
                $this->bodyPlain.=imap_body($this->connection,$this->messageNumber);
            } else {
                $this->bodyHTML.=imap_body($this->connection,$this->messageNumber);
            }
        }
    }
    
    public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {
        foreach($messageParts as $part) {
            $partNumber = $prefix . $index;
            
            if($part->type == 0) {
                if($part->subtype == 'PLAIN') {
                    $this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
                }
                else {
                    $this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
                }
            }
            else if($part->type == 2) {
                $msg = new EmailMessage($this->connection, $this->messageNumber);
                $msg->getAttachments = $this->getAttachments;
                $msg->recurse($part->parts, $partNumber.'.', 0, false);
                $this->attachments[] = array(
                    'type' => $part->type,
                    'subtype' => $part->subtype,
                    'filename' => '',
                    'data' => $msg,
                    'inline' => false,
                );
            }
            else if(isset($part->parts)) {
                if($fullPrefix) {
                    $this->recurse($part->parts, $prefix.$index.'.');
                }
                else {
                    $this->recurse($part->parts, $prefix);
                }
            }
            else if($part->type > 2) {
				$this->attachments[] = array(
					'type' => $part->type,
					'subtype' => $part->subtype,
					'filename' => $this->getFilenameFromPart($part),
					'data' => $this->getAttachments ? $this->getPart($partNumber, $part->encoding) : '',
					'inline' => isset($part->id),
					'cid' => isset($part->id) ? str_replace(array('<', '>'),'', $part->id) : '' // 
				);
            }
            
            $index++;
            
        }
        
    }
    
    function getPart($partNumber, $encoding) {

        $data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
        switch($encoding) {
            case 0: return $data; // 7BIT
            case 1: return $data; // 8BIT
            case 2: return $data; // BINARY
            case 3: return base64_decode($data); // BASE64
            case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
            case 5: return $data; // OTHER
        }


    }
    
    function getFilenameFromPart($part) {

        $filename = '';

        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                if(strtolower($object->attribute) == 'filename') {
                    $filename = $object->value;
                }
            }
        }

        if(!$filename && $part->ifparameters) {
            foreach($part->parameters as $object) {
                if(strtolower($object->attribute) == 'name') {
                    $filename = $object->value;
                }
            }
        }

        return $filename;

    }

}

<?php

function SendMail($address,$subject,$body,$content_type="text/plain")
    {
    $headers="From: CarsTT<noreplay@carstt.ru>\n";
    $headers.="To: ".$address."\n";
    $headers.="Mime-Version: 1.0\n";
    $headers.="Content-type: ".$content_type."; charset=\"utf8\"\n";
    
    
    if(!mail($address,$subject,$body,$headers))
        return false;
            
    return true;    
    
    }

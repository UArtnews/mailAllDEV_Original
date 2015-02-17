<?php

class SwitchMail {
    static public function gmail($username){

        $passwords = array(
            'uazipmail' => 'nhriuxis3911',
            'uamemo' => 'cyawljne76',
            'uamail' => 'bwzrshsf89'
        );
        if(array_key_exists($username, $passwords)){
            $transport = SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
            $transport->setUsername($username);
            $transport->setPassword($passwords[$username]);

            $gmail = new Swift_Mailer($transport);

            Mail::setSwiftMailer($gmail);
        }
    }
}
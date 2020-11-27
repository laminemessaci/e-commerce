<?php


namespace App\services;

use Mailjet\Client;
use Mailjet\Resources;

class MailJet
{
    private $api_Key = '9991bc1f2514f8443bf5e7e9b99ffb6b';
    private $api_key_secret = '9e9c4705ce8a9561411e1e8070392299';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_Key, $this->api_key_secret, true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "lamine.messaci@hotmail.fr",
                        'Name' => "la boutique  de Aya"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 1969399,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
    }

}
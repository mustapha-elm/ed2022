<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailjetService {

    private $apiKey = '349194d0afa834014917e996d71de62a';
    private $apiKeySecret = '5c6961dee4137d44bfe2db49e60a0b68';

    private $mj;

    public function __construct()
    {
        $this->mj = new Client($this->apiKey, $this->apiKeySecret,true,['version' => 'v3.1']);
    }


    public function send(string $toEmail, string $toName, string $subject, string $HTMLPart) {
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "contact@electrodeals.tech",
                        'Name' => "Electrodeals"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $HTMLPart
                ]
            ]
        ];
        $response = $this->mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
        dd($response->getData());
    }

    public function sendWelcome(string $toEmail, string $toName) {
        $body = [
            'Messages' => [
              [
                'From' => [
                  'Email' => "contact@electrodeals.tech",
                  'Name' => "Electrodeals"
                ],
                'To' => [
                  [
                    'Email' => $toEmail,
                    'Name' => $toName
                  ]
                ],
               
                'TemplateID' => 4124554,
                'TemplateLanguage' => true,
                'Subject' => 'Bienvenue chez ElectroDeals',
                'Variables' => ['prenom' => $toName]
              ]
            ]
          ];
          $response = $this->mj->post(Resources::$Email, ['body' => $body]);
          $response->success();
          //&& dd($response->getData())
    }
}
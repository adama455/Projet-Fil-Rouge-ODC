<?php
namespace App\Services;

use App\Entity\User;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class MailerService{

    public function __construct(MailerInterface $mailer, Environment $twig){
        $this->mailer = $mailer;
        $this->twig = $twig;    

    }
    public function sendEmail($user,$subject="Création de compte")
    {
        $email = (new Email())
            ->from('khadimoulmoustapha@gmail.com')
            ->to($user->getLogin())
            ->subject($subject)
            ->html($this->twig->render('mail/index.html.twig',[
                "user"=>$user,
                "token"=>$user->getToken(),
                "subject"=>$subject
        ]));
        $this->mailer->send($email);
    }
}
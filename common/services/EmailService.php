<?php


namespace common\services;


use Yii;
use yii\base\Component;

class EmailService extends Component
{
 public static function send($to, $subject, $views, $data) {
   Yii::$app->mailer->compose($views, $data)
     ->setTo($to)
     ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
     ->setSubject($subject)
     ->send();
 }
}
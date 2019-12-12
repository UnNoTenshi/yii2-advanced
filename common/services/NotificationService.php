<?php


namespace common\services;


use yii\base\Component;

class NotificationService extends Component
{
  public function sendEmailAboutNewRoleInProject($data)
  {
    EmailService::send($data->user->email,
      'Поменялась роль в проекте \'' . $data->project->title . '\'',
      [
        'html' => 'assignRole-html',
        'text' => 'assignRole-text'
      ], (array) $data);
  }
}
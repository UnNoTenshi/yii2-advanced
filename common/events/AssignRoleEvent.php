<?php


namespace common\events;


use yii\base\Event;

class AssignRoleEvent extends Event
{
  public $project;
  public $user;
  public $role;

  public function dump()
  {
    return [
      'project' => $this->project->id,
      'user' => $this->user->id,
      'role' => $this->role
    ];
  }
}
<?php

use yii\db\Migration;

/**
 * Class m191125_144028_table_task
 */
class m191125_144028_table_task extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {
      $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }

    $this->createTable("task", [
      "id" => $this->primaryKey(),
      "title" => $this->string(255)->notNull(),
      "description" => $this->text(),
      "project_id" => $this->integer()->null(),
      "executor_id" => $this->integer()->null(),
      "started_at" => $this->integer()->null(),
      "completed_at" => $this->integer()->null(),
      "creator_id" => $this->integer()->notNull(),
      "updater_id" => $this->integer()->null(),
      "created_at" => $this->integer()->notNull(),
      "updated_at" => $this->integer()->null()
    ], $tableOptions);

    $this->addForeignKey("fx_task_user_1", "task", "executor_id", "user", "id");
    $this->addForeignKey("fx_task_user_2", "task", "creator_id", "user", "id");
    $this->addForeignKey("fx_task_user_3", "task", "updater_id", "user", "id");
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropForeignKey("fx_task_user_1", "task");
    $this->dropForeignKey("fx_task_user_2", "task");
    $this->dropForeignKey("fx_task_user_3", "task");

    $this->dropTable("task");
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

  }

  public function down()
  {
      echo "m191125_144028_table_task cannot be reverted.\n";

      return false;
  }
  */
}

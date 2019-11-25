<?php

use yii\db\Migration;

/**
 * Class m191125_145927_table_project
 */
class m191125_145927_table_project extends Migration
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

    $this->createTable("project", [
      "id" => $this->primaryKey(),
      "title" => $this->string(255)->notNull(),
      "description" => $this->text()->notNull(),
      "active" => $this->boolean()->notNull()->defaultValue(0),
      "creator_id" => $this->integer()->notNull(),
      "updater_id" => $this->integer()->null(),
      "created_at" => $this->integer()->notNull(),
      "updated_at" => $this->integer()->null()
    ], $tableOptions);

    $this->addForeignKey("fx_project_user_1", "project", "creator_id", "user", "id");
    $this->addForeignKey("fx_project_user_2", "project", "updater_id", "user", "id");
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropForeignKey("fx_project_user_1", "project");
    $this->dropForeignKey("fx_project_user_2", "project");

    $this->dropTable("project");
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

  }

  public function down()
  {
      echo "m191125_145927_table_project cannot be reverted.\n";

      return false;
  }
  */
}

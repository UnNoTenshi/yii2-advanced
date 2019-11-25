<aside class="main-sidebar">

  <section class="sidebar">
    <?= dmstr\widgets\Menu::widget(
      [
        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
        'items' => [
          ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
          ['label' => 'Home', 'icon' => 'file-code-o', 'url' => ['/site']],
          ['label' => 'About', 'icon' => 'file-code-o', 'url' => ['site/about'], 'visible' => !Yii::$app->user->isGuest],
          ['label' => 'Contact', 'icon' => 'file-code-o', 'url' => ['site/contact'], 'visible' => !Yii::$app->user->isGuest],
          ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
          ['label' => 'SignUp', 'url' => ['site/signup'], 'visible' => Yii::$app->user->isGuest],
        ],
      ]
    ) ?>

  </section>

</aside>

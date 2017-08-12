<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1 style="font-style: italic">TODOs</h1   >
       
        	<?php if(!Yii::$app->user->isGuest) : ?>
            <div class="app_main_profilepic_container">
                <img src="<?= Yii::$app->user->identity->profile_pic ?>" class="app_main_profilepic" />
            </div>                
                 <p class="lead">Welcome : <span><?= strtoupper(Yii::$app->user->identity->first_name).'!' ?> </span></p>
            <?php endif; ?>

        <div class="app_btn_container"> 
        <?php if(Yii::$app->user->isGuest) : ?>
        	<a href="index.php?r=site/login" class="app_btn app_btn_login">login</a>
        	<a href="index.php?r=site/signup" class="app_btn app_btn_signup">signup</a>
        <?php endif; ?>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <a href="index.php?r=tasks" class="app_btn app_btn_view">view tasks</a>
        <?php endif; ?>
        </div>
    </div>
</div>

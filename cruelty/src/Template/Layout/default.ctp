<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Cruelty';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['bootstrap.min.css', 'cruelty.css']) ?>
    <?= $this->Html->script(['jquery-1.12.4.min.js', 'bootstrap.min.js']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Cruelty</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="<?= ($this->request->getParam('action') == "play" ? "active" : null) ?>"><?= $this->Html->link('Play', [
                        'controller' => 'Games',
                        'action' => 'play'
                    ]) ?></li>
                    <li class="<?= ($this->request->getParam('action') == "about" ? "active" : null) ?>"><?= $this->Html->link('About', [
                        'controller' => 'Games',
                        'action' => 'about'
                    ]) ?></li>
                    <li class="<?= ($this->request->getParam('action') == "api" ? "active" : null) ?>"><?= $this->Html->link('API', [
                        'controller' => 'Users',
                        'action' => 'api'
                    ]) ?></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container">
        <div class="starter-template">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </div><!-- /.container -->
</body>
</html>

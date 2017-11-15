<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;
use Cake\I18n\Time;

/**
 * Simple console wrapper around Psy\Shell.
 */
class GameShell extends Shell
{

    /**
     * Start the shell and interactive console.
     *
     * @return int|null
     */
    public function main()
    {


    }

    public function run()
    {
        $gamesTable = $this->loadModel('Games');
        $currentGame = $gamesTable->findByComplete(false)->first();
        if (empty($currentGame)) {
            $gamesTable->newGame();
        }
        if (Time::now() > $currentGame->end_time) {
            $gamesTable->runGame();
            $this->out("Ran the game.");
        } else {
            $this->out("Not time to run the game yet.");
        }
    }

    public function forceRun()
    {
        $gamesTable = $this->loadModel('Games');
        $gamesTable->runGame();
        $this->out("Ran the game.");
    }

    public function random()
    {
        $usersTable = $this->loadModel('Users');
        $gamesUsersTable = $this->loadModel('GamesUsers');
        $users = $usersTable->find('all');
        foreach ($users as $user) {
            $gamesUsersTable->insertPlay($user->id, rand(0,1));
        }
        $this->out('Inserted random plays for all players.');
    }

    public function newGame()
    {
        $gamesTable = $this->loadModel('Games');
        $gamesTable->createNewGame();
        $this->out('Created a new game.');
    }

    /**
     * Display help for this console.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = new ConsoleOptionParser('console');
        $parser->setDescription(
            'This shell provides a REPL that you can use to interact ' .
            'with your application in an interactive fashion. You can use ' .
            'it to run adhoc queries with your models, or experiment ' .
            'and explore the features of CakePHP and your application.' .
            "\n\n" .
            'You will need to have psysh installed for this Shell to work.'
        );

        return $parser;
    }
}

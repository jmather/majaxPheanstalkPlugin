<?php

class pheanstalkStatsTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'crons'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'pheanstalk';
    $this->name             = 'stats';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [pheanstalk:stats|INFO] task does things.
Call it with:

  [php symfony pheanstalk:stats|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $pheanstalk = PheanstalkBridge::getInstance();
    foreach($pheanstalk->stats() as $name => $val)
    {
      $this->logSection($name, $val);
    }
  }
}

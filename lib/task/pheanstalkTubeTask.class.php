<?php

class pheanstalkTubeTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('tube_name', sfCommandArgument::REQUIRED, 'The name of the tube to look at'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'crons'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'pheanstalk';
    $this->name             = 'tube';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [pheanstalk:tube|INFO] task does things.
Call it with:

  [php symfony pheanstalk:tube|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $pheanstalk = PheanstalkBridge::getInstance();
    foreach($pheanstalk->statsTube($arguments['tube_name']) as $name => $val)
    {
      $this->logSection($name, $val);
    }
  }
}

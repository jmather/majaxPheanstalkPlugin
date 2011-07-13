<?php
/**
 * Pheanstalk Worker task
 *
 * @package majaxPheanstalkPlugin
 */
class pheanstalkRunWorkerTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandArgument::REQUIRED, 'The application name'),
      new sfCommandArgument('worker_class', sfCommandArgument::REQUIRED, 'Worker Class'),
      new sfCommandArgument('log_path', sfCommandArgument::REQUIRED, 'Log Path'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'pheanstalk';
    $this->name             = 'run_worker';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [pheanstalk:run_worker|INFO] task does things.
Call it with:

  [php symfony pheanstalk:run_worker|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // Create a context from the config
    $context = sfContext::createInstance($this->configuration);

    $worker_class = $arguments['worker_class'];
    $log_path = $arguments['log_path'];
    $thread = new $worker_class($log_path, $this);
    if (!($thread instanceof majaxPheanstalkWorkerThread))
      throw new InvalidArgumentException('Class supplied was not a majaxPheanstalkWorkerThread child');

    $thread->run();
  }
}

<?php

abstract class majaxPheanstalkWorkerThread {
  
  protected $memory_limit = 100000000;
  protected $sleep_ms = 100000;
  protected $enable_pheanstalk = true;

  private $path;

  public function __construct($path) {
    $this->setBasePath($path);
    $this->log('starting');
    $this->doConstruct();

    if ($this->enable_pheanstalk)
      $this->pheanstalk = majaxPheanstalk::getInstance();
  }

  protected function doConstruct()
  {
    // placeholder for subclasses
    // Legacy support
    $this->doInit();
  }
  protected function doInit() {
    // placeholder for subclasses
    // depreciated.
  }

  public function __destruct() {
    $this->doDestruct();
    $this->log('ending');
  }

  protected function doDestruct()
  {
    // placeholder for subclasses
  }

  private function setBasePath($path) {
    $this->path = $path;
  }

  public function run() {
    $this->log('starting to run');
    $cnt = 0;

    while(1) {
      $this->doRun();
      $cnt++;

      $memory = memory_get_usage();

      $this->log('memory:' . $memory);

      if($memory > $this->memory_limit) {
        $this->log('exiting run due to memory limit');
        exit;
      }

      usleep($this->sleep_ms);
    }
  }

  protected function doRun()
  {
    throw new Exception('You must override doRun()');
  }
  
  protected function getJob($pipe)
  {
    if ($this->enable_pheanstalk)
      return $this->pheanstalk->watch($pipe)->ignore('default')->reserve();
    throw new Exception('Pheanstalk is not enabled.');
  }

  protected function deleteJob($job)
  {
    if ($this->enable_pheanstalk)
      return $this->pheanstalk->delete($job);
    throw new Exception('Pheanstalk is not enabled.');
  }

  protected function buryJob($job)
  {
    if ($this->enable_pheanstalk)
      return $this->pheanstalk->bury($job);
    throw new Exception('Pheanstalk is not enabled.');
  }

  private function log($txt) {
    file_put_contents($this->path . '/worker.txt', $txt . "\n", FILE_APPEND);
  }
}


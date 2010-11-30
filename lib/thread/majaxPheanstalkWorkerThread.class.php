<?php

abstract class majaxPheanstalkWorkerThread {
  
  protected $memory_limit = 100000000;
  protected $sleep_ms = 100000;

  private $path;

  public function __construct($path) {
    $this->setBasePath($path);
    $this->log('starting');

    $this->pheanstalk = majaxPheanstalk::getInstance();

    $this->doInit();
  }
   
  protected function doInit() {
    // placeholder for subclasses
  }

  public function __destruct() {
    $this->log('ending');
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
    return $this->pheanstalk->watch($pipe)->ignore('default')->reserve();
  }

  protected function deleteJob($job)
  {
    return $this->pheanstalk->delete($job);
  }

  protected function buryJob($job)
  {
    return $this->pheanstalk->bury($job);
  }

  private function log($txt) {
    file_put_contents($this->path . '/worker.txt', $txt . "\n", FILE_APPEND);
  }
}


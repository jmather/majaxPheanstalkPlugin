<?php

class ExampleWorkerThread extends majaxPheanstalkWorkerThread
{
  protected function doConstruct()
  {
    $this->log('Initializing something important...');
  }

  protected function doDeconstruct()
  {
    $this->log('Closing down some other external resources');
  }

  protected function doRun()
  {
    $job = $this->getJob('my_tube');
    $data = $job->getData();
    $this->log('Got job data: '.$data);
    $this->deleteJob($job);
  }
}

<?php

class ExampleWorkerThread extends majaxPheanstalkWorkerThread
{
  protected function doRun()
  {
    $job = $this->getJob('my_tube');
    $data = $job->getData();
    $this->log('Got job data: '.$data);
    $this->deleteJob($job);
  }
}

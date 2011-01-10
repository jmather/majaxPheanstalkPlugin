To use
======

Pushing jobs
------------

    $pheanstalk = majaxPheanstalk::getInstance();
    $pheanstalk->useTube('test_tube')->put('Job data');




To run jobs
-----------

Create a worker, modeled after ExampleWorkerThread in lib/thread/, which extends majaxPheanstalkWorkerThread and implements doRun().

    <?php

    class ExampleWorkerThread extends majaxPheanstalkWorkerThread
    {
      protected function doRun()
      {
        $job = $this->getJob('test_tube');
        $data = $job->getData();
        $this->log('Got data: '.$data);
        $this->deleteJob($job);
      }
    }

    ?>


Now you're ready to run the task!


    ./symfony pheanstalk:run_worker ExampleWorkerThread /path/to/store/log/in/

Easy-peasy

Extended Features
=================

Helper Functions
----------------

There are two primary helpers you will need for stateful management.

### protected function doConstruct() { }

This is run during __construct(), before Pheanstalk is initialized.

### protected function doDestruct() { }

This is run during __destruct()


Configuration Options
---------------------

### memory_limit (10240000 -- 10mb)

You can set how much memory we're allowed to use (in bytes), and we will fail as soon after that as possible.

It is checked before every iteration of doRun().

To change it, in your doConstruct(), simply:

    protected function doConstruct()
    {
      $this->memory_limit = 8000000;
    }


### sleep_ms (100000 -- 1/10th of a second)

Number of microseconds (i.e. a millionth of a second, 1000000 == 1 second) to pause for between doRun() iterations.

To change it, in your doConstruct(), simply:

    protected function doConstruct()
    {
      $this->sleep_ms = 8000000;
    }

### enable_pheanstalk (true)

Set this to false to not instantize Pheanstalk in the constructor. Use this if you're making a generic
worker thread as opposed to a Pheanstalk worker thread.

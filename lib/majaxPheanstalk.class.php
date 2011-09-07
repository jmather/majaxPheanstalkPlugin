<?php
class majaxPheanstalk {
  static $conn = null;
  public static function getInstance()
  {
    $host = sfConfig::get('app_pheanstalk_host', '127.0.0.1');
    $port = sfConfig::get('app_pheanstalk_port', 11300);
    
    $path = sfConfig::get('app_pheanstalk_path',realpath(dirname(__FILE__).'/../vendor')).'/pheanstalk_init.php';

    require_once $path;

    if (self::$conn == null)
    {
      self::$conn = new Pheanstalk($host.':'.$port);
    }
    return self::$conn;
  }

  public static function addJob($tube, $job_data)
  {
    $p = self::getInstance();
    $p->useTube($tube)->addJob($job_data);
  }

  public static function getJob($tube)
  {
    $p = self::getInstance();
    return $p->watch($tube)->ignore('default')->reserve();
  }

  public static function deleteJob($job)
  {
    $p = self::getInstance();
    return $p->delete($job);
  }
}


<?php

// Smaller priority is better
class NegativeQueue extends SplPriorityQueue
{
  public function compare($priority1, $priority2)
  {
    if ($priority1 === $priority2) return 0;
    return $priority1 > $priority2 ? -1 : 1;
  }
}

// Implementation of A* search algoritm
class AStar {

  // List of routes with prices
  protected $routes;

  // Priority queue
  protected $queue;

  // Array with passed points
  protected $passed;

  function __construct(&$routes) {
    $this->routes = $routes;
    $this->queue = new NegativeQueue();
    $this->queue->setExtractFlags(NegativeQueue::EXTR_BOTH);
    $this->passed = array();
  }


  function initRoutes($source) {
    $this->addRoutesToQueue(array($source), 0);
  }

  function addRoutesToQueue($currentRoute, $price) {
    $lastPoint = end($currentRoute);
    foreach ($this->routes as $route) {
      // Check that new direction exists and add it if it's not passed
      $direction = $this->getRouteDirection($route, $lastPoint);
      if ($direction > -1 && !$this->isPassed($route[$direction])) {
        $this->addRoute($currentRoute, $route[$direction], $price + $route[2]);
      }
    }
  }

  // Extend route and add to queue
  function addRoute($route, $newPoint, $price) {
    $route[] = $newPoint;
    $this->queue->insert($route, $price);
  }

  // Because we not sure in order of price rules
  function getRouteDirection(&$route, $source) {
    if ($route[0] == $source) {
      return 1;
    }
    if ($route[1] == $source) {
      return 0;
    }
    return -1;
  }

  // Check is point already passed
  function isPassed($target) {
    return in_array($target, $this->passed);
  }

  function addPassedPoint($target) {
    return array_push($this->passed, $target);
  }

  // Main function
  function findCheapestRoute($source, $target) {
    $this->initRoutes($source);

    while ($this->queue->valid()) {
      $stage = $this->queue->extract();
      $route = $stage['data'];
      $price = $stage['priority'];

      // Get last route's point
      $item = end($route);
      if ($this->isPassed($item)) {
        continue;
      }

      // Congratulations!
      if ($item == $target) {
        return array('route' => $route, 'price' => $price);
      }

      $this->addPassedPoint($item);
      $this->addRoutesToQueue($route, $price);
    }

    // We don't find the route
    return array('route' => array(), 'price' => -1);
  }

  // Stub for run search in one line
  static function search($source, $target, &$routes) {
    $star = new AStar($routes);
    return $star->findCheapestRoute($source, $target);
  }
}
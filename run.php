<?php

require("astar.php");

$paths = array(
  array("Таганрог", "Ростов", 110),
  array("Ростов", "Новочеркасск", 50),
  array("Неклиновка", "Таганрог", 23),
  array("Ростов", "Самбек", 70),
  array("Ростов", "Москва", 1000),
  array("Питер", "Москва", 1300),
  array("Питер", "Таганрог", 2500),
  array("Самбек", "Таганрог", 30),
);

$result = AStar::search("Таганрог", "Питер", $paths);
// var_dump($result);
print("Цена вопроса: " . $result['price']);
print("Путь: " . implode(" -> ", $result['route']));
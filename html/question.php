<?php

//functions.phpを読み込んでその処理を実行　
require __DIR__.'/../lib/functions.php';

$id = $_GET['id'] ?? '';

//上記のidのデータをfetchByIdで取得し、$dataという変数に受けておく
$data = fetchById($id);

if(!$data){
  error404();
}

$formattedData = generateFormattedData($data);



$assignData = [
      'id' => $formattedData['id'],
      'question' => $formattedData['question'],
      'answers' => $formattedData['answers'],
];
  loadTemplate('question', $assignData);




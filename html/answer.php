<?php

//functions.phpを読み込んでその処理を実行　
require __DIR__.'/../lib/functions.php';

$id = $_POST['id'] ?? '';
$selectedAnswer = $_POST['selectedAnswer'] ?? '';


//上記のidのデータをfetchByIdで取得し、$dataという変数に受けておく
$data = fetchById($id);

if(!$data){
  // HTTPレスポンスのヘッダを404にする
  header('HTTP/1.1 404 Not Found');

  // レスポンスの種類を指定する
  header('Content-Type: application/json; charset=UTF-8');

  $response = [
    'message' => 'The specified id could not be found',
   
  ];
  
  echo json_encode($response);
  exit(0);
}

$formattedData = generateFormattedData($data);

//functions.phpの値を全て取ってきている。

$correctAnswer = $formattedData['correctAnswer'];
$correctAnswerValue = $formattedData['answers'][$correctAnswer];
$explanation = $formattedData['explanation'];

$result = $selectedAnswer === $correctAnswer;

$response = [
  'result' => $result,
  'correctAnswer' => $correctAnswer,
  'correctAnswerValue' => $correctAnswerValue,
  'explanation' => $explanation,
];
// レスポンスの種類を指定する
header('Content-Type: application/json; charset=UTF-8');
echo json_encode($response);
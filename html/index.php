<?php

//functions.phpを読み込んでその処理を実行　
require __DIR__.'/../lib/functions.php';


//上記のidのデータをfetchByIdで取得し、$dataという変数に受けておく
$dataList = fetchAll();

if(!$dataList){
  error404();
}
$questions = [];
foreach ($dataList as $data){
  $questions[] = generateFormattedData($data);

}

$assignData = [
  'questions' => $questions,
      
];
  loadTemplate('index', $assignData);




<?php

function loadTemplate($filename, array $assignData = []){
  extract($assignData);
  // include(他のファイルを読み込むことができる)
include __DIR__.'/../template/'.$filename.'.tpl.php';

}

function error404(){
  // HTTPレスポンスのヘッダを404にする
  header('HTTP/1.1 404 Not Found');

  // レスポンスの種類を指定する
  header('Content-Type: text/html; charset=UTF-8');
  loadTemplate('404');
  
  exit(0);
}

function fetchAll(){
  //ファイルを開く（fopen)　$handlerという変数で受け取る
  // data。csvを/../と書くのが面倒なため、data.csvを同じ階層の中に持ってくると__DIR__.になる
  $handler = fopen(__DIR__.'/data.csv','r');

  //データを取得(fgetcsv)
  $questions = [];
  //while（繰り返し処理）に入れることにより14-8を1つ1つ書かなくていい
  while ($row = fgetcsv($handler)) {
    //isDataRow()という関数を使用する。33-68にて実装する
    if (isDataRow($row) ){
        $questions[] = $row;
       
    }
  }

  //ファイルを閉じる(fclose)
  fclose($handler);

  //データを返す(データ取得の$question)
    return $questions;
}

function fetchById($id){
  
  $handler = fopen(__DIR__.'/data.csv','r');

  //データを取得(fgetcsv)
  $question = [];
  //while（繰り返し処理）に入れることにより14-8を1つ1つ書かなくていい
  while ($row = fgetcsv($handler)) {
    //isDataRow()という関数を使用する。33-68にて実装する
    if (isDataRow($row) ){
      if ($row[0] === $id){
        $question = $row;
        break;
      }
       
      
    }
  }

  //ファイルを閉じる(fclose)
  fclose($handler);

  //データを返す(データ取得の$question)
    return $question;
}

/**
 * クイズの問題データの行か判定
 *
 * @param array $row csvファイルの1行分のデータ
 *
 * @return bool クイズのデータの場合はtrue/クイズのデータでなければfalse
 */

 //かきの実装が問題なければ14行目が実行される。
function isDataRow(array $row)
{
    // データの項目数が足りているか判定（8こあるか？）
    if (count($row) !== 8) {
        return false;
    }

    // データの項目の中身がすべて埋まっているか確認する
    foreach ($row as $value) {
        // 項目の値が空か判定
        if (empty($value)) {
            return false;
        }
    }

    // idの項目が数字ではない場合は無視する
    //is_numericは数字かどうか？の判定
    if (!is_numeric($row[0])) {
        return false;
    }

    // 正しい答えはa,b,c,dのどれか
    $correctAnswer = strtoupper($row[6]);
    $availableAnswers = ['A', 'B', 'C', 'D'];
    //配列の中に値があるかどうか？存在してるか？を判定
    if (!in_array($correctAnswer, $availableAnswers)) {
        return false;
    }

    // すべてチェックが問題なければtrue
    return true;
}

/**
 * 取得できたクイズのデータ1行を利用しやすいように連想配列に変換
 * 値をHTMLに組み込めるようにエスケープも行う
 *
 * @param array $data クイズ情報(1問分)
 *
 * @return array 整形したクイズの情報
 */

function generateFormattedData($data)
{
    // 構造化した配列を作成する
    $formattedData = [
        'id' => escape($data[0]),
        'question' => escape($data[1], true),
        'answers' => [
            'A' => escape($data[2]),
            'B' => escape($data[3]),
            'C' => escape($data[4]),
            'D' => escape($data[5]),
        ],
        'correctAnswer' => escape(strtoupper($data[6])),
        'explanation' => escape($data[7], true),
    ];

    return $formattedData;
}

/**
 * HTMLに組み込むために必要なエスケープ処理を行う
 *
 * @param string $data エスケープしたい情報
 * @param bool $nl2br 改行を<br>に変換する場合はtrue
 *
 * @return string エスケープ済の文字列
 */
function escape($data, $nl2br = false)
{
    // HTMLに埋め込んでも大丈夫な文字に変換する
    $convertedData = htmlspecialchars($data, ENT_HTML5);

    // 改行コードを<br>タグに変換するか判定
    if ($nl2br) {
        /// 改行コードを<br>タグに変換したものをを返却
        return nl2br($convertedData);
    }

    return $convertedData;
}

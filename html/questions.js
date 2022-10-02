// querySelectorAll（ol.answers liの全て）がclickされた際の処理
const answersList = document.querySelectorAll('ol.answers li');

// ansersListの関数にある処理をforEach（繰り返し）clickされた時にcheckClickedAnserを読み込んで処理される設定
answersList.forEach(li => li.addEventListener('click',checkClickedAnswer))



// クリックした時に実行される処理を設定 上記のclick　eventが発生したらcheckClickedAnswerのメソッドを読み込む
function checkClickedAnswer(event) {
  // クリックされた答えの要素（liタグ）　　上記のeventの中にcurrentTargetがあるみたい・・・。
  const clickedAnswerElement = event.currentTarget;

// 選択した答え（A.B.C.D）HTMLで記載したdata-answerが読み込まれる
const selectedAnswer = clickedAnswerElement.dataset.answer;

//上記と同じ考え方だけど、clickedAnswerElement（li）の親要素のolを参照するので closestというメソッドを記載する。上記はHTMLに記載してあるanswerだけど下記はidになる。
const questionId = clickedAnswerElement.closest('ol.answers').dataset.id;

// フォームデータの入れ物を作る
const formData = new FormData();

// 送信したい値を追加
formData.append('id', questionId);
formData.append('selectedAnswer', selectedAnswer);

// xhr = XMLHttpRequestの頭文字です
const xhr = new XMLHttpRequest();

// HTTPメソッドをPOSTに指定、送信するURLを指定
xhr.open('POST', 'answer.php');

// フォームデータを送信
xhr.send(formData);

// loadendはリクエストが完了したときにイベントが発生する
xhr.addEventListener('loadend', function(event){
  /** @type {XMLHttpRequest} */
  const xhr = event.currentTarget;
  

  //リクエストが成功したかステータスコードで確認
  if (xhr.status === 200){
      const response = JSON.parse(xhr.response);
      //答えが正しいか判定
      const result = response.result;
      const correctAnswer = response.correctAnswer;
      const correctAnswerValue = response.correctAnswerValue;
      const explanation = response.explanation;
      
      //画面表示
      displayResult(result, correctAnswer,correctAnswerValue,explanation);

  }else{
    //エラー
    alert('Error: 回答データの取得に失敗しました');
  }
});


}


function displayResult(result, correctAnswer, correctAnswerValue, explanation) {
  // メッセージを入れる変数を用意
let message;

// カラーコードを入れる変数を用意
let answerColorCode;

// 答えが正しいか判定(===は比較する)selectedAnser（選択した答え）correctAnser（正しい答え）が一致したら出力する。
if(result){
// 正しい答えだった時
  message = '正解！おめでとう！！';
  answerColorCode = '';
} else {
  // 間違った答えだった時
  message = '残念！不正解！！';
  answerColorCode = '#f05959';

}

  alert(message);

  //正解の内容をHTMLに組み込む
  document.querySelector('#correct-answer').innerHTML = correctAnswer +'. '+ correctAnswerValue;
  document.querySelector('#explanation').innerHTML = explanation;
  // 色を変更(間違っていた時だけ色が変わる)  querySelectorが実行されたら#correct-answerの配列をanswerColorCode(#f05959)に変更
  document.querySelector('#correct-answer').style.color = answerColorCode;

  // 答え全体を表示  
  // querySelectorが実行されたらdisplayのstyleをblockにして表示させる
  document.querySelector('#section-correct-answer').style.display = 'block';
}

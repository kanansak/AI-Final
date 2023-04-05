<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ติดตั้ง Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>AI Final</title>
</head>

<body>
    <div class="container mt-5">
        <h1>รับข้อความ</h1>
        <form action="" method="GET">
            <div class="form-group">
                <label for="message">ข้อความ</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
        </form>
    </div>

    <!-- ติดตั้ง JavaScript ของ Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <?php

if (isset($_GET["message"])) {
    $Apikey = "etTW2zhw5WLwgAoo2HkfnePopSOP52sJ";
    $message = $_GET["message"];

//EmoNews บริการทำนายอารมณ์ของผู้อ่าน หลังจากอ่านหัวข้อข่าว โดยใช้เทคนิค fastText
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.aiforthai.in.th/emonews/prediction?text=" . urlencode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Apikey:$Apikey",
        ),
    ));

    $EmoNews = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $EmoNews;
        $json_str = $EmoNews;
        $obj_EmoNews = json_decode($json_str);
        //echo '<script>console.log(' . json_encode($EmoNews) . ');</script>';
        // Calculate total score
        $total_score = $obj_EmoNews->result->surprise + $obj_EmoNews->result->neutral + $obj_EmoNews->result->sadness
          + $obj_EmoNews->result->pleasant + $obj_EmoNews->result->fear + $obj_EmoNews->result->anger
          + $obj_EmoNews->result->joy;
        // Calculate percentages
          $surprise_percentage = round(($obj_EmoNews->result->surprise / $total_score) * 100, 2);
          $neutral_percentage = round(($obj_EmoNews->result->neutral / $total_score) * 100, 2);
          $sadness_percentage = round(($obj_EmoNews->result->sadness / $total_score) * 100, 2);
          $pleasant_percentage = round(($obj_EmoNews->result->pleasant / $total_score) * 100, 2);
          $fear_percentage = round(($obj_EmoNews->result->fear / $total_score) * 100, 2);
          $anger_percentage = round(($obj_EmoNews->result->anger / $total_score) * 100, 2);
          $joy_percentage = round(($obj_EmoNews->result->joy / $total_score) * 100, 2);
        echo '<div class="container-fluid">
            <div class="col-lg-6">
                <h4>ผลลัพธ์</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">' . $obj_EmoNews->text . '</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Result:</p>
                        <ul class="list-group">
                          <li class="list-group-item">Surprise: ' . $surprise_percentage . '%</li>
                          <li class="list-group-item">Neutral: ' . $neutral_percentage . '%</li>
                          <li class="list-group-item">Sadness: ' . $sadness_percentage . '%</li>
                          <li class="list-group-item">Pleasant: ' . $pleasant_percentage . '%</li>
                          <li class="list-group-item">Fear: ' . $fear_percentage . '%</li>
                          <li class="list-group-item">Anger: ' . $anger_percentage . '%</li>
                          <li class="list-group-item">Joy: ' . $joy_percentage . '%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>';
    }
}
//CUICUI Survey
if (isset($_GET["message"])) {
  $message = $_GET["message"];
  $curl = curl_init();
  $post_fields = json_encode(array("text" => $message));
  $curl = curl_init();
  curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.aiforthai.in.th/ai9-sentiment',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $post_fields,
      CURLOPT_HTTPHEADER => array(
          "apiKey: $Apikey",
          'Content-Type: application/json',
      ),
  ));
  $CUICUI = curl_exec($curl);
  curl_close($curl);
  echo $CUICUI;
  echo '<script>console.log(' . json_encode($CUICUI) . ');</script>';
}
//เอสเซนส์ ระบบวิเคราะห์ความคิดเห็นจากข้อความ (Social Sensing: SSENSE)
if (isset($_GET["message"])) {
  $message = $_GET["message"];
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.aiforthai.in.th/ssense?text=" . urlencode($message),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Apikey: $Apikey",
    ),
));

$response  = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    //echo $response ;
    $SSENSE = json_decode($response ,true);
    echo '<script>console.log(' . json_encode($SSENSE) . ');</script>';
    echo "Sentiment score: " . $SSENSE['sentiment']['score'] . "<br>";
    echo "Sentiment polarity: " . $SSENSE['sentiment']['polarity'] . "<br>";
    echo "Sentiment polarity-neg: " . $SSENSE['sentiment']['polarity-neg'] . "<br>";
    echo "Sentiment polarity-pos: " . $SSENSE['sentiment']['polarity-pos'] . "<br>";
    echo "Intention request: " . $SSENSE['intention']['request'] . "<br>";
    echo "Intention sentiment: " . $SSENSE['intention']['sentiment'] . "<br>";
    echo "Intention question: " . $SSENSE['intention']['question'] . "<br>";
    echo "Intention announcement: " . $SSENSE['intention']['announcement'] . "<br>";
    //echo "Input: " . $SSENSE['preprocess']['input'] . "<br>";
    //echo "Neg: " . json_encode($SSENSE['preprocess']['neg']) . "<br>";
    //echo "Pos: " . json_encode($SSENSE['preprocess']['pos']) . "<br>";
    //echo "Segmented: " . json_encode($SSENSE['preprocess']['segmented']) . "<br>";
    //echo "Keyword: " . json_encode($SSENSE['preprocess']['keyword']) . "<br>";
    //echo "Alert: " . json_encode($SSENSE['alert']) . "<br>";
    //echo "Comparative: " . json_encode($SSENSE['comparative']) . "<br>";
    echo "Associative: " . json_encode($SSENSE['associative']) . "<br>";
}
}
?>
</body>
</html>
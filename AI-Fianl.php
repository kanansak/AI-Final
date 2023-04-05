<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- ติดตั้ง Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <title>รับข้อความ</title>
</head>
<body>
  <div class="container mt-5">
    <h1>รับข้อความ</h1>
    <form>
      <div class="form-group">
        <label for="message">ข้อความ</label>
        <textarea class="form-control" id="message" rows="3"></textarea>
      </div>
      <button type="button" class="btn btn-primary" onclick="submitMessage()">ส่งข้อความ</button>
    </form>
  </div>

  <!-- ติดตั้ง JavaScript ของ Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

  <script>
    function submitMessage() {
      var message = document.getElementById("message").value;
      console.log(message);
      // send message to API
      fetch("https://api.aiforthai.in.th/emonews/prediction?text=" + encodeURIComponent(message), {
        headers: {
          "Apikey": "etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
        }
      })
      .then(response => response.json())
      .then(data => console.log(data))
      .catch(error => console.error(error));
    }
  </script>

<?php
if(isset($_GET["text"])) {
    $message = $_GET["text"];
    $curl = curl_init();
 
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.aiforthai.in.th/emonews/prediction?text=".urlencode($message),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,  
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Apikey:etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
    )
  ));
   
  $response = curl_exec($curl);
  $err = curl_error($curl);
   
  curl_close($curl);
   
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    echo $response;
    console.log($response);
  }
}
?>

</body>
</html>
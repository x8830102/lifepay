<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<div  style="padding: 15px;transform: translateY(10%);">
  <div align="center" style="width: 220px;margin: auto;padding: 10px">
  <img id='qrcode' src='#' style="width: 100%;border:4px solid #666" />
  <input type="hidden" id="mnow" value="250">
  <input type="hidden" id="num" value="100">

  <script>
  var mnow = $('#mnow').val();
  var num = $('#num').val();
  content = encodeURIComponent('http://livelink.com.tw/test_table/life_pay.php?m='+mnow+'&st=tzml&nm='+num);

  $("#qrcode").attr("src","http://chart.apis.google.com/chart?cht=qr&chl="+ content +"&chs=512x512");
  </script>

  </div>
</div>


<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Require a Drawn Signature · Signature Pad</title>
  <style>
    body { font: normal 100.01%/1.375 "Helvetica Neue",Helvetica,Arial,sans-serif; }
  </style>
  <!-- <link href="assets/jquery.signaturepad.css" rel="stylesheet"> -->
  <!--[if lt IE 9]><script src="../assets/flashcanvas.js"></script><![endif]-->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
</head>
<body>
  <form method="POST" action="" class="sigPad">

    <p class="drawItDesc">Draw your signature</p>
    <ul class="sigNav">
      <li class="clearButton"><a href="#clear">Clear</a></li>
    </ul>

    <div class="sig sigWrapper">
      <div class="typed"></div>
      <canvas class="pad" width="198" height="55"></canvas>
      <input type="hidden" name="output" class="output">
    </div>

    <button type="submit">I accept the terms of this agreement.</button>

 

  </form>

  <?php


  //require_once 'signature-to-image.php';

  // $sig = $_POST['output'];

  // $img = sigJsonToImage($json);
  // if (isset($_POST['$output'])){
  // $sig = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);
  // echo $sig;
  // }

  require_once 'signature-to-image.php';

  if (isset($_POST['output'])){

  $sig = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);
  $img = sigJsonToImage($sig);

  imagepng($img, 'signature.png');

  imagedestroy($img);

  }

  ?>

  <script src="jquery.signaturepad.js"></script>
  <script>
    $(document).ready(function() {
      $('.sigPad').signaturePad({drawOnly:true});
    });
  </script>
  <script src="assets/json2.min.js"></script>

</body>
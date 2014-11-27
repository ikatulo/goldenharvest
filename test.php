function signature($img){

  $sig = filter_input(INPUT_POST, 'output', FILTER_UNSAFE_RAW);
  $img = sigJsonToImage($sig);

  imagepng($img, 'signature.png');

  imagedestroy($img);

}

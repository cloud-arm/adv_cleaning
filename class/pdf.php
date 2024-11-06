<?php 
function get_pdf($date, $name, $path)
{

  $file_name = $path . $name . '.pdf';
  $html_code = '<link rel="stylesheet" href="bootstrap.min.css">';
  $html_code .= $date;
  $pdf = new Pdf();
  $pdf->load_html($html_code);
  $pdf->render();
  $file = $pdf->output();
  file_put_contents($file_name, $file);

  return $file_name;
}
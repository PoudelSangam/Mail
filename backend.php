<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$progress=0;
// Get the code from the form
$code = $_POST['code'];

// Create a PDF instance
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$progress=10;

// Load HTML content
$html = '<html><body><pre>' . $code . '</pre></body></html>';
$dompdf->loadHtml($html);

// Set paper size (optional)
$dompdf->setPaper('A4', 'portrait');
$progress=20;

// Render PDF (first save to a variable)
$dompdf->render();
$output = $dompdf->output();
$progress=30;

// Send PDF as an attachment to Gmail using PHPMailer
$mail = new PHPMailer(true);
$response = [];
$progress=40;

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 's9800938405@gmail.com';
    //$mail->Password   = 'mqylueurejedjktq';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $progress=50;

    //Recipients
    $mail->setFrom('s9800938405@gmail.com', 'sangam poudel');
    $mail->addAddress('078bct031.sangam@sagarmatha.edu.np', 'sangam');
    $progress=60;

    // Attach PDF
    $mail->addStringAttachment($output, 'code.pdf', 'base64', 'application/pdf');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Code to PDF Conversion';
    $mail->Body    = 'Please find attached the PDF file converted from the uploaded code.';
    $progress=80;



    // Send email
    $mail->send();
    $progress=100;
    $response['status'] = 'success';
    $response['message'] = 'PDF sent successfully';
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// $totalSteps = 100;
// for ($i = 0; $i <= $totalSteps; $i += 10) {
//    // usleep(50000); // Simulate some processing time (adjust as needed)
//     $progress = $i; // Store progress in session
// }

// Output progress as JSON
header('Content-Type: application/json');


echo json_encode($response);
//echo json_encode(['progress' => $progress]);
?>

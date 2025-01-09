<?php
session_start();

require_once '../control/Database.php';
require_once '../control/Articles.php';

// Include the Dompdf library from the dompdf-master folder
// require_once '../dompdf-master/src/Autoloader.php';
// Dompdf\Autoloader::register();
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$db = new Database();
$conn = $db->getConnection();
$articles = new Articles($conn);

// Check if the article ID is provided
if (!isset($_GET['articleID'])) {
    header("Location: index.php");
    exit();
}

$articleID = $_GET['articleID'];
$article = $articles->getArticleByID($articleID);

// Redirect if the article doesn't exist
if (!$article) {
    header("Location: index.php");
    exit();
}

// Configure Dompdf options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Build the HTML content for the PDF
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($article['Title']) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #333;
        }
        h1, h2 {
            color: #444;
        }
        .header {
            text-align: center;
            padding: 20px;
        }
        .content {
            margin: 20px;
        }
        .banner {
            text-align: center;
            margin-bottom: 20px;
        }
        .banner img {
            max-width: 100%;
            height: auto;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . htmlspecialchars($article['Title']) . '</h1>
    </div>
    <div class="banner">
        <img src="' . htmlspecialchars($article['BannerImage']) . '" alt="Banner">
    </div>
    <div class="content">
        <p>' . nl2br(htmlspecialchars($article['InnerText'])) . '</p>
    </div>
    <div class="footer">
        <p>Generated on ' . date('Y-m-d H:i:s') . '</p>
    </div>
</body>
</html>';

// Load HTML content into Dompdf
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Stream the generated PDF to the browser
$dompdf->stream('Article-' . $articleID . '.pdf', ['Attachment' => 0]);
exit();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Struk' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        .receipt-container {
            max-width: 400px;
            font-family: 'Courier New', Courier, monospace;
        }
    </style>
</head>
<body class="bg-gray-100 flex justify-center items-start min-h-screen pt-10">
    <?php include $view; ?>
</body>
</html>
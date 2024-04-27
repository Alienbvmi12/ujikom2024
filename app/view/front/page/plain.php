<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php $this->getAdditionalBefore(); ?>
    <?php $this->getAdditional(); ?>
    <?php $this->getAdditionalAfter(); ?>
    <style>
        @media print{
            .print-el {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php $this->getJsFooter(); ?>
    <?php $this->getJsReady(); ?>


    <?php $this->getThemeContent(); ?>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php $this->getAdditionalBefore(); ?>
    <?php $this->getAdditional(); ?>
    <?php $this->getAdditionalAfter(); ?>
</head>

<body class="bg-primary">
    <?php $this->getJsFooter(); ?>
    <?php $this->getJsReady(); ?>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <?php $this->getThemeContent(); ?>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>
</body>

</html>
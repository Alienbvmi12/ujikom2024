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
        #main-layout{
            position:relative;
        }
        #main-layout::before {
            content: "";
            position:absolute;
            top: 0;
            left:0;
            width: 100%;
            height: 100%;
            background: url('<?= base_url() ?>skin/img/bg.jpg');
            background-repeat: no-repeat;
            -webkit-filter: blur(10px);
            filter: blur(10px);
        }
    </style>
</head>

<body>
    <?php $this->getJsFooter(); ?>
    <?php $this->getJsReady(); ?>

    <div class="d-flex flex-row" id="main-layout" style="justify-content: stretch; min-height: 100vh">
        <div class="d-flex flex-column w-100 justify-content-between">
            <div class="d-flex flex-column w-100">
                <div class="container py-4">
                    <?php $this->getThemeContent(); ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
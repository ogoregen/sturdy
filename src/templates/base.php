<!DOCTYPE html>
<html>
    <head>
        <title><?= isset($context["title"]) ? $context["title"]." - " : "" ?>Sturdy</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?= $context["metaDescription"] ?? "" ?>">
    </head>
    <body>
        <?php include $template ?>
    </body>
</html>
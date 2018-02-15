<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Your Shoes List</title>
</head>
<body>
<ul>
    <?php foreach($products as $product): ?>
    <li>
        <a href="<?php echo $product['link'] ?>">
            <img src="<?php echo $product['img'] ?>" alt=""/>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>{{ $test }}</h1>
<p>{{ $test2 }}</p>


<h2>{{ $test }}</h2>
<p> {{ $test2 }}</p>
{{ foreach $products as $product }}

    <p>{{ $product }}</p>
{{ endforeach }}
</body>
</html>

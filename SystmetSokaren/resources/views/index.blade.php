<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sytemet Sökaren</title>
</head>
<body>
    <header>
        <img src="" alt="">
        <h1>Systemet Sökaren</h1>
    </header>
        
    <main>
        <form action="/search" method="POST">
            <label for="Städer">Specifik Stad</label>
            <input type="text" name="Städer" placeholder="Stad">
                <datalist>
                    @foreach($stores as $item)
                        <option value="{{$item->displayName}}">
                    @endforeach
                </datalist>
        </form>

        <form action="" method="POST">
            <label for="Varor">Specifik vara</label>
            <input type="text" name="Varor" placeholder="Vara">
                <datalist>
                    @foreach($products as $item)
                        <option value="{{$item->name}}">
                    @endforeach
                </datalist>
        </form>

    </main>

</body>
</html>
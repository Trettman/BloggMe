<head>
    <title>BloggMe</title>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
    <script src="/BloggMe/JS/jquery-2.1.4.min.js"></script>
    <script src="/BloggMe/JS/jquery-ui.js"></script>
    <script src="/BloggMe/JS/jquery-ui.min.js"></script>
    <script src="/BloggMe/JS/main.js"></script>
</head>
<body>
    <header>
        <div id="top">
            <div class="center">
                <a href="home"><img src="/BloggMe/icons/Blogg.png"></a>
                <form action="search" method="post">
                    <input id="search" type="text" name="search" placeholder="Search for friends, family and other members!" value="">
                    <input type="submit" name="go" value="Search">
                </form>
            </div>        
            <a class="button" href="logout">Sign out</a>
            <a class="button" href="profile?id=<?php echo $_SESSION["user_id"]; ?>">My account</a>
        </div>
    </header>
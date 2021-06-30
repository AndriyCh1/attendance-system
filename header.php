<?php
    include('connection.php');
    session_start();

    if(!isset($_SESSION["worker_id"])){
        header('location:login.php');
    }
?>
<body>
<header class="header">
    <div class="container">
        <div class="nav">
            <div class="nav-logo">
                <img src="images/logo.jpg" alt="">
            </div>
            <nav class="nav-menu">
                <ul class="nav-menu__list">
                    <li class="nav-menu__item">
                        <a class="nav__link" href="index.php">Профіль</a>
                    </li>
                    <li class="nav-menu__item">
                        <a class="nav__link" href="my-records.php">Моя присутність</a>
                    </li>
                </ul>
            </nav>
            <div class="user-nav">
                <a href="logout.php" class="user-nav__link">Вийти</a>
            </div>
        </div>
    </div>
</header>

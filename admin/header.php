<?php
    include('connection.php');
    session_start();
    
    if(!isset($_SESSION["adminId"])){
        header('location:login.php');
    }
?>
<body>
<header class="header">
    <div class="container">
        <div class="nav">
            <div class="nav-logo">
                <img src="../images/logo.jpg" alt="">
            </div>
            <nav class="nav-menu">
                <ul class="nav-menu__list">
                    <li class="nav-menu__item">
                        <a class="nav__link" href="index.php">Працівники</a>
                    </li>
                    <li class="nav-menu__item">
                        <a class="nav__link" href="record-action.php">Присутність працівників</a>
                    </li>
                    <!-- <li class="nav-menu__item">
                        <a class="nav__link" href="statistic.php">Діаграми</a>
                    </li> -->
                    <!-- <li class="nav-menu__item">
                        <a class="nav__link" href="statistic.php">Звіт</a>
                    </li> -->
                    <!-- <li class="nav-menu__item">
                        <a class="nav__link" href="statistic.php">Додати адміністрацію</a>
                    </li> -->
                    <!-- <li class="nav-menu__item">
                        <a class="nav__link" href="#"></a>
                    </li>
					<li class="nav-menu__item">
                        <a class="nav__link" href="#">Сформувати звіт</a>
                    </li> -->
                </ul>
            </nav>
            <div class="user-nav">
                <!-- <a href="#" class="user-nav__link">Редагувати</a> -->
                <a href="logout.php" class="user-nav__link">Вийти</a>
            </div>
        </div>
    </div>
</header>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/CSS/style.css">
    <title>Document</title>
</head>

<body>
    <header>
        <h1>Amazing Maze!</h1>
    </header>
    <main>
        <div>
            <h2>Trouvez la souris et remportez la partie!</h2>
        </div>
        <?php
        session_start();
        $mazes = [
            [
                [5, 0, 0, 0, 0, 0, 0, 0],
                [1, 1, 0, 1, 1, 1, 1, 0],
                [0, 0, 0, 0, 0, 0, 0, 1],
                [0, 1, 0, 1, 1, 0, 1, 1],
                [0, 0, 0, 0, 1, 0, 0, 4],
            ],
            [
                [5, 1, 0, 0, 0, 0, 0, 0],
                [0, 1, 0, 1, 1, 1, 1, 0],
                [0, 1, 0, 0, 0, 0, 1, 0],
                [0, 1, 0, 1, 1, 0, 1, 0],
                [0, 0, 0, 0, 1, 0, 0, 4],
            ],
            [
                [5, 1, 0, 0, 0, 0, 0, 0],
                [0, 1, 1, 1, 1, 0, 1, 0],
                [0, 0, 0, 0, 0, 0, 1, 0],
                [0, 1, 0, 1, 1, 0, 1, 0],
                [0, 1, 0, 0, 1, 0, 1, 4],
            ],
        ];
        if (!isset($_SESSION['maze'])) {
            $_SESSION['maze'] = $mazes[rand(0, count($mazes) - 1)];
        }
        $maze = $_SESSION['maze'];

        if (!isset($_SESSION['position'])) {
            $_SESSION['position'] = [0, 0];
        }
        ?>

        <div>
            <form action="" method="POST">
                <div>
                    <p>Utilisez les direction pour vous déplacez!</p>
                </div>
                <div class="column">
                    <div class="up">
                        <input type="submit" value='up' name="dir">
                    </div>
                    <div class="lr">
                        <div class="left">
                            <input type="submit" value='left' name="dir">
                        </div>
                        <div class="right">
                            <input type="submit" value='right' name="dir">
                        </div>
                    </div>
                    <div class="down">
                        <input type="submit" value='down' name="dir">
                    </div>
                </div>
                <div class="reset">
                    <input type="submit" value="reset" name="reset">
                </div>
            </form>
        </div>

        <?php


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['reset'])) {
                session_destroy();
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }

            if (isset($_POST['dir'])) {
                $dir = $_POST['dir'];
                $maze[0][0] = 0;
                switch ($dir) {
                    case 'up':
                        if ($_SESSION['position'][0] - 1 >= 0 && $maze[$_SESSION['position'][0] - 1][$_SESSION['position'][1]] != 1) {
                            $_SESSION['position'] = [$_SESSION['position'][0] - 1, $_SESSION['position'][1]];
                            if ($maze[$_SESSION['position'][0]][$_SESSION['position'][1]] == 4) {
                                $gameOver = true;
                            }
                        }
                        $maze[$_SESSION['position'][0]][$_SESSION['position'][1]] = 5;
                        break;
                    case 'down':
                        if ($_SESSION['position'][0] + 1 <= count($maze) - 1 && $maze[$_SESSION['position'][0] + 1][$_SESSION['position'][1]] != 1) {
                            $_SESSION['position'] = [$_SESSION['position'][0] + 1, $_SESSION['position'][1]];
                            if ($maze[$_SESSION['position'][0]][$_SESSION['position'][1]] == 4) {
                                $gameOver = true;
                            }
                        }
                        $maze[$_SESSION['position'][0]][$_SESSION['position'][1]] = 5;
                        break;
                    case 'left':
                        if ($_SESSION['position'][1] - 1 >= 0 && $maze[$_SESSION['position'][0]][$_SESSION['position'][1] - 1] != 1) {
                            $_SESSION['position'] = [$_SESSION['position'][0], $_SESSION['position'][1] - 1];
                            if ($maze[$_SESSION['position'][0]][$_SESSION['position'][1]] == 4) {
                                $gameOver = true;
                            }
                        }
                        $maze[$_SESSION['position'][0]][$_SESSION['position'][1]] = 5;
                        break;
                    case 'right':
                        if ($_SESSION['position'][1] + 1 <= count($maze[$_SESSION['position'][0]]) - 1 && $maze[$_SESSION['position'][0]][$_SESSION['position'][1] + 1] != 1) {
                            $_SESSION['position'] = [$_SESSION['position'][0], $_SESSION['position'][1] + 1];
                            if ($maze[$_SESSION['position'][0]][$_SESSION['position'][1]] == 4) {
                                $gameOver = true;
                            }
                        }
                        $maze[$_SESSION['position'][0]][$_SESSION['position'][1]] = 5;
                        break;
                }
            }
        }
        foreach ($maze as $i => $line) {
            $catPos = $_SESSION['position'];
            foreach ($line as $j => $cell) {
                if (!(($i === $catPos[0] && $j === $catPos[1])
                    || ($i === $catPos[0] + 1 && $j === $catPos[1])
                    || ($i === $catPos[0] - 1 && $j === $catPos[1])
                    || ($i === $catPos[0] && $j === $catPos[1] + 1)
                    || ($i === $catPos[0] && $j === $catPos[1] - 1)
                    || ($i === $catPos[0] + 1 && $j === $catPos[1] + 1)
                    || ($i === $catPos[0] + 1 && $j === $catPos[1] - 1)
                    || ($i === $catPos[0] - 1 && $j === $catPos[1] - 1)
                    || ($i === $catPos[0] - 1 && $j === $catPos[1] + 1)
                )) {
                    $maze[$i][$j] = 2;
                }
            }
        }
        ?>
        <?php if (isset($gameOver) && $gameOver === true) :
            session_destroy();
        ?>
            <p id="gagne">Bravo ! Vous avez attrapé la souris !</p>
            <form method="post">
                <input id="rejou" type="submit" name="replay" value="Rejouer">
            </form>
        <?php else : ?>
            <table class="tab">
                <?php
                foreach ($maze as  $row) {
                    echo ('<tr>');
                    foreach ($row as $value) {
                        echo ('<td style="width: 120px; text-align : center ; height: 100px;">');
                        if ($value == 5) {
                            echo "<img src='./assets/images/chatnoir.jpg' width='50px' height='50px'";
                        } else if ($value == 4) {
                            echo "<img src='./assets/images/souris.jpg' width='50px' height='50px'";
                        } else if ($value == 1) {
                            echo "<img src='./assets/images/mur.jpg' width='50px' height='100%'";
                        } else if ($value == 2) {
                            echo "<img src='./assets/images/nuage.jpg' width='50px' height='100%'";
                        } else {
                            echo "";
                        }
                        echo ('</td>');
                    }
                    echo ('</tr>');
                }

                ?>
            </table>
        <?php endif; ?>
    </main>
    <footer class="foot">
        <p>Copyright : Mixyz.dev</p>
    </footer>
</body>

</html>
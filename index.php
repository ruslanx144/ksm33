<?php
// Підключення классу гри.
require_once(dirname(__FILE__) . '/classes.php');

session_start();

// Получаємо з сесії теперішню гру
// Створює нову гру, якщо гра ще не запущена
$game = isset($_SESSION['game'])? $_SESSION['game']: null;
if(!$game || !is_object($game)) {
    $game = new TicTacGame();
}

// Обробляє запрос юзера, виконує потрібну дію.
$params = $_GET + $_POST;
if(isset($params['action'])) {
    $action = $params['action'];
    
    if($action == 'move') {
        // Обробляє хід юзера.
        $game->makeMove((int)$params['x'], (int)$params['y']);
        
    } else if($action == 'newGame') {
        // Користувач почав нову гру
        $game = new TicTacGame();
    }
}
// Додавання заново створеної гри в сесію
$_SESSION['game'] = $game;


// Відображення поточної гри в вигляді хтмл-сторінки
$width = $game->getFieldWidth();
$height = $game->getFieldHeight();
$field = $game->getField();
$winnerCells = $game->getWinnerCells();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" version="XHTML+RDFa 1.0" dir="ltr">
<head profile="http://www.w3.org/1999/xhtml/vocab">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Хрестики-нолики</title>
</head>

<body align="center">

<!-- CSS-стилі, які задають зовн. вигляд хтмл-елементів -->
<style type="text/css">
    .ticTacField {overflow:hidden;}
    .ticTacRow {clear:both;}
    .ticTacCell {float:left; left:640px; border: 1px solid #000000; width: 20px; height:20px;
                position:relative; text-align:center;} 
    .ticTacCell a {position:absolute; left:0;top:0;right:0;bottom:0}
    .ticTacCell a:hover { background: grey; }
    
    
    .icon { display:inline-block; }
    .player1:after { content: 'X'; }
    .player2:after { content: 'O'; }
    A {
    text-decoration:none;    
    color: darkblue; 
   }
   A:visited {
    color: darkblue; 
   }
   A:active {
    color: darkblue; 
   }
</style>
    <font><h2>Хрестики - нолики</h2></font>
<?php if($game->getCurrentPlayer()) { ?>
    Хід гравця 
    <div class="icon player<?php echo $game->getCurrentPlayer() ?>">"</div>"<br><br>
<?php } ?>

<?php if($game->getWinner()) { ?>
    <!-- Повідомлення про переможця -->
    Виграв гравець
    <div class="icon player<?php echo $game->getWinner() ?>">"</div>"!
<?php } ?>

<!-- Підсвітка зробленних ходів переможця -->    
<div class="ticTacField">
    <?php for($y=0; $y < $height; $y++) { ?>
        <div class="ticTacRow">
            <?php for($x=0; $x < $width; $x++) {
                // $player - іконка гравця, який зайняв клітинку , або null, якщо клітинка вільна
                // $winner - мітка, яка означає, що клітинка має бути підсвічена в разі перемоги 
                $player = isset($field[$x][$y])? $field[$x][$y]: null;
                $class = ($player? ' player' . $player: '') . ($winner? ' winner': '');
                ?>
                <div class="ticTacCell<?php echo $class ?>">
                    <?php if(!$player) { ?>
                        <a href="?action=move&amp;x=<?php echo $x ?>&amp;y=<?php echo $y ?>"></a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
            
<br/><a color="red" href="?action=newGame">Почати нову гру</a>
            
</body>
</html>

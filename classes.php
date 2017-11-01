<?php

class TicTacGame {
    
    /*
     * Field size
     */
    private $fieldWidth = 3;
    private $fieldHeight = 3;
    
    /**
     * Number of points to win
     */
    private $countToWin = 3;
    
    /**
     * Масив зроблених ходів типу $field[$x][$y] = $player;
     */
    private $field = array();
    
    /**
     * аналогічно $field, але зберігає тільки клітинки, які
     * виділяються кольором при перемозі
     */
    private $winnerCells = array();
    
    private $currentPlayer = 1; // 1 або 2
    private $winner = null; // містить в собі 1 або 2
    
    /**
     * Обробка наст. ходу. Ставить в вказані координати символ поточного гравця
     * Передає хід наст. гравцю або в разі перемоги визначає переможця.
     */
    public function makeMove($x, $y) {
        // Враховуємо хід, якщо виконуються умови:
        // 1) гра триває
        // 2) клітка в межах ігрового поля
        // 3) в клітинці все ще пусто.
        if(
                $this->currentPlayer
                &&
                $x >= 0 && $x < $this->fieldWidth
                &&
                $y >= 0 && $y < $this->fieldHeight
                &&
                empty($this->field[$x][$y]))
        {
                $current = $this->currentPlayer;

                $this->field[$x][$y] = $current;
                $this->currentPlayer = ($current == 1) ? 2 : 1;
                
                $this->checkWinner();
        }
    }
    
    /**
     * Робить пошук виграшної комбінації, перевіряючи 4 напрямки: горизонт. вертикал. і дві 
     * діагоналі.
     */
    private function checkWinner() {
        for($y = 0; $y < $this->fieldHeight; $y++) {
            for($x = 0; $x < $this->fieldWidth; $x++) {
                $this->checkLine($x, $y, 1, 0);
                $this->checkLine($x, $y, 1, 1);
                $this->checkLine($x, $y, 0, 1);
                $this->checkLine($x, $y, -1, 1);
            }
        }
        if($this->winner) {
            $this->currentPlayer = null;
        }
    }
    
    /**
     * Перевіряє чи не знаходиться в цьому місці виграшна комбінація і запамятовує
     * переможця і саму виграшну комб. в масиві winnerCells.
     * @param $startX начальна точка, від якї перевіряється наявність комбінації
     * @param $startY
     * @param $dx направлення, в якому шукати комбінацію
     * @param $dy
     */
    private function checkLine($startX, $startY, $dx, $dy) {
        $x = $startX;
        $y = $startY;
        $field = $this->field;
        $value = isset($field[$x][$y])? $field[$x][$y]: null;
        $cells = array();
        $foundWinner = false;
        if($value) {
            $cells[] = array($x, $y);
            $foundWinner = true;
            for($i=1; $i < $this->countToWin; $i++) {
                $x += $dx;
                $y += $dy;
                $value2 = isset($field[$x][$y])? $field[$x][$y]: null;
                if($value2 == $value) {
                    $cells[] = array($x, $y);
                } else {
                    $foundWinner = false;
                    break;
                }
            }
        }
        if($foundWinner) {
            foreach($cells as $cell) {
                $this->winnerCells[$cell[0]][$cell[1]] = $value;
            }
            $this->winner = $value;
        }
    }

    /*
     * Функції для отримання теперішнього стану гри і ігрового поля.
     */
    
    public function getCurrentPlayer() { return $this->currentPlayer; }
    public function getWinner()        { return $this->winner; }
    public function getField()         { return $this->field; }
    public function getWinnerCells()   { return $this->winnerCells; }
    public function getFieldWidth()    { return $this->fieldWidth; }
    public function getFieldHeight()   { return $this->fieldHeight; }
}

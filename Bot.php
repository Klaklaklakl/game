<?php

class Bot {
    protected $STRATEGIES = [[0, 1, 2], [3, 4, 5], [6, 7, 8], [0, 3, 6], [1, 4, 7], [2, 5, 8], [0, 4, 8], [2, 4, 6]];
	protected $aa;
    protected $bb;
    
    public function __construct($aa, $bb) {
        $this->aa = $aa ? $aa : array();
        $this->bb = $bb ? $bb : array();
    }
    
    public function getResponse() {
        if($this->isLine())
            return ')';
        if (count($this->bb)>1) {
            $victoriousCell = $this->getVictoriousCell();
            if($victoriousCell)
                return '(' . $victoriousCell;
        }
        for($i = 0; $i < count($this->aa); $i++) {
            $number = $this->hasX($this->aa[$i], 0);
            while ($number !== false) {//echo $number;
                $response = $this->isResponse($number);
                if($response)
                    return $response;
                $number = $this->hasX($this->aa[$i], $number + 1);
            }
        }
        return $this->getFreeCell();
    }
    
    function isResponse($number) {//echo $number;
        $countA = $this->getCountX($number, $this->aa);//echo $countA;
        if ($countA == 3)
            return ')';
        if ($countA == 1)
            return false;
        $countB = $this->getCountX($number, $this->bb);//echo $countB;
        if($countB)
            return false;
        return $this->getFreeCellInArray($number, $this->aa);
    }
    
    function getCountX($number, $xx) {//echo '_'.$number;print_r($xx);
        $countX = 0;
        for($i = 0; $i < 3; $i++)
            if (in_array($this->STRATEGIES[$number][$i], $xx))
                $countX++;// echo $countX;} echo '<br />';
        return $countX;
    }
    
    function getFreeCellInArray($number, $xx) {
        if (!in_array($this->STRATEGIES[$number][0], $xx))
            return $this->STRATEGIES[$number][0];
        if (!in_array($this->STRATEGIES[$number][1], $xx))
            return $this->STRATEGIES[$number][1];
        return $this->STRATEGIES[$number][2];
    }
    
    function getFreeCell() {
        for($i = 0; $i < 8; $i++)
            if (!in_array($i, $this->aa) && !in_array($i, $this->bb))
                return $i;
        return 'end';
    }
        
        function hasX($aax, $begin) {
            for($j = $begin; $j < 8; $j++)
                if(in_array($aax, $this->STRATEGIES[$j]))
                    return $j;
            return false;
        }
    
    function getVictoriousCell() {
        for($i = 0; $i < 8; $i++) {
            $countX = $this->getCountX($i, $this->bb);
            if ($countX == 2)
                if(!$this->getCountX($i, $this->aa))
                    return $this->getFreeCellInArray($i, $this->bb);
        }
    }
    function isLine() {
        if (count($this->aa) < 3)
            return false;
        for($i = 0; $i < 8; $i++) {
            $countX = $this->getCountX($i, $this->aa);
            if ($countX == 3) {
                return true;
                break;
            }
        }
        return false;
    }
}
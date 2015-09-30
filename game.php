<?php

class GameNumberSet{
    private $__numbers = [];

    public function __construct($numbers){

        if(!is_array($numbers) or count($numbers) != 4){
            throw new Exception('bad number set');
        }
        foreach($numbers as $number){
            if(!is_numeric($number) or $number > 9){
                throw new Exception('0-9 allowed, got '.$number);
            }
            if(in_array($number, $this->__numbers)){
                throw new Exception('duplicate number');
            }
            $this->__numbers[] = $number;
        }
    }

    public function getNumbers(){
        return $this->__numbers;
    }

}

class Game{
    protected $_numberSetAPlayer;
    protected $_hits = 0;

    public function setPlayerNumbers(GameNumberSet $numberSet){
        $this->_numberSetAPlayer = $numberSet;
        return $this;
    }

    public function getHits(GameNumberSet $numberSet){
        $hits = 0;
        $blows = 0;
        $this->_hits++ ;
        foreach($this->_numberSetAPlayer->getNumbers() as $idx => $number){
            if($numberSet->getNumbers()[$idx] == $number){
                $hits++;
            }
            elseif(in_array($number, $numberSet->getNumbers())){
                $blows ++;
            }
        }
        return [$hits, $blows];
    }

    public function getTries(){
        return $this->_hits;
    }
}

function getSecret($stars = false)
{
    // Get current style
    $oldStyle = shell_exec('stty -g');

    if ($stars === false) {
        shell_exec('stty -echo');
        $password = rtrim(fgets(STDIN), "\n");
    } else {
        shell_exec('stty -icanon -echo min 1 time 0');

        $password = '';
        while (true) {
            $char = fgetc(STDIN);

            if ($char === "\n") {
                break;
            } else if (ord($char) === 127) {
                if (strlen($password) > 0) {
                    fwrite(STDOUT, "\x08 \x08");
                    $password = substr($password, 0, -1);
                }
            } else {
                fwrite(STDOUT, "*");
                $password .= $char;
            }
        }
    }

    // Reset old style
    shell_exec('stty ' . $oldStyle);

    // Return the password
    return $password;
}

function getLine($fp){
    if(false !== ($str= fgets($fp))){
        return str_replace("\n", '', $str);
    }
    return false;
}

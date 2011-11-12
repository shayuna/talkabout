<?php

    $brwsrType=strtolower($_SERVER['HTTP_USER_AGENT']);
    if (is_numeric(strpos($brwsrType,'iphone'))) header("Location:mIndex.php");


class test1
{
  private $kooloo;
  protected $nana="mistake";
  public $mokmok= array("12q","15");
  public function getMok()
  {
      $mymy= implode("***",$this->mokmok);
      return $mymy;
  }
  function __construct ($tVar)
  {
         $this->kooloo=$tVar;
  }
  function getKooloo()
  {
      return $this->kooloo;
  }
    
}

class test1Strong extends test1
{
     public function setNana($str)
     {
         $this->nana=$str;
     }
     public function getNana()
     {
        return $this->nana;
     }
}

$t=new test1Strong("mercury");
$t->setNana("howdy");
print($t->getNana());



?>
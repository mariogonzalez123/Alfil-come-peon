<?php
function cordenadaInicialUsu(&$tablero){
    $coordInicial = array();
    $encontrado = false;
    while(!$encontrado){
        $x = rand(0,BOARD_SIZE -1);
        $y =  rand(0,BOARD_SIZE -1);
        if(empty($tablero[$x][$y])){
            $tablero[$x][$y] = "*";
            $encontrado = true;
            $coordInicial = [$x,$y];
        }
    }
    return $coordInicial;
    
}

function cordenadaInicialCpu(&$tablero){
    
    $coordInicialCpu = array();
    $encontrado = false;
    while(!$encontrado){
        $x = 6;
        $y = rand(0,BOARD_SIZE -1);
        if(empty($tablero[$x][$y])){
            $tablero[$x][$y] ="+";
            $encontrado = true;
            $coordInicialCpu = [$x,$y];
            
        }
        return $coordInicialCpu;
        
    }
    
}
function comprobarJugadaUsuario($X,$Y,$XUsuario , $YUsuario){
    $valido = false;
    if($X == $XUsuario -1 && $Y == $YUsuario -1 || $X == $XUsuario -1 && $Y == $YUsuario +1  || $X == $XUsuario +1 && $Y == $YUsuario -1 || $X == $XUsuario +1 && $Y == $YUsuario +1|| 
       $X == $XUsuario -2 && $Y == $YUsuario -2 || $X == $XUsuario -2 && $Y == $YUsuario +2  || $X == $XUsuario +2 && $Y == $YUsuario -2 || $X == $XUsuario +2 && $Y == $YUsuario +2||
       $X == $XUsuario -3 && $Y == $YUsuario -3 || $X == $XUsuario -3 && $Y == $YUsuario +3  || $X == $XUsuario +3 && $Y == $YUsuario -3 || $X == $XUsuario +3 && $Y == $YUsuario +3|| 
       $X == $XUsuario -4 && $Y == $YUsuario -4 || $X == $XUsuario -4 && $Y == $YUsuario +4  || $X == $XUsuario +4 && $Y == $YUsuario -4 || $X == $XUsuario +4 && $Y == $YUsuario +4|| 
       $X == $XUsuario -5 && $Y == $YUsuario -5 || $X == $XUsuario -5 && $Y == $YUsuario +5  || $X == $XUsuario +5 && $Y == $YUsuario -5 || $X == $XUsuario +5 && $Y == $YUsuario +5||
       $X == $XUsuario -6 && $Y == $YUsuario -6 || $X == $XUsuario -6 && $Y == $YUsuario +6  || $X == $XUsuario +6 && $Y == $YUsuario -6 || $X == $XUsuario +6 && $Y == $YUsuario +6||
       $X == $XUsuario -7 && $Y == $YUsuario -7 || $X == $XUsuario -7 && $Y == $YUsuario +7  || $X == $XUsuario +7 && $Y == $YUsuario -7 || $X == $XUsuario +7 && $Y == $YUsuario +7){
       $valido = true;
    } 
    return $valido;
}

function moverCpu($tablero,$XCpu,$YCpu){
  
  
  $movimientos = [];
 
      
       if(isset($tablero[$XCpu-1][$YCpu])){
        return $movimientos = [$XCpu- 1,$YCpu];
       }
        
     }
function comprobar_llegada($XMovCPU , $YMovCPU){
    $result = "";
      if($XMovCPU == 0 && $YMovCPU== 0 || $XMovCPU == 0 && $YMovCPU == 1 ||$XMovCPU== 0 && $YMovCPU == 2 ||
         $XMovCPU == 0 && $YMovCPU == 3|| $XMovCPU == 0 && $YMovCPU == 4 ||$XMovCPU== 0 && $YMovCPU == 5 ||  
         $XMovCPU == 0 && $YMovCPU == 6 ||$XMovCPU == 0 && $YMovCPU == 7 ){
        $result = -1;
    }
    return $result;
}



session_start();

require '.\vendor\autoload.php';
use eftec\bladeone\bladeone;

$Views = __DIR__ . '\Views';
$Cache = __DIR__ . '\Cache';
$Blade = new BladeOne($Views, $Cache);

define("BOARD_SIZE",8);


if (empty($_POST)) {

    $tablero = array_fill(0, BOARD_SIZE, array_fill(0, BOARD_SIZE, ""));
    
    $coordInicUsuario = cordenadaInicialUsu($tablero);
    //Cordenadas Usuario
    $_SESSION['XUsuario'] = $coordInicUsuario[0];
    $_SESSION['YUsuario'] = $coordInicUsuario[1];
    $coordInicCPU = cordenadaInicialCpu($tablero);
    //Coordenadas CPU
    $_SESSION['XCpu'] = $coordInicCPU[0];
    $_SESSION['YCpu'] = $coordInicCPU[1];
    $_SESSION['tablero'] = $tablero;
  
    

    echo $Blade->run('board', ['XUsuario' => $_SESSION['XUsuario'], 'YUsuario' => $_SESSION['YUsuario'], 'XCpu' => $_SESSION['XCpu'], 'YCpu' => $_SESSION['YCpu']]);

} else {
    
    $tablero = $_SESSION['tablero'];
    $XUsuario = $_SESSION['XUsuario'];
    $YUsuario = $_SESSION['YUsuario'];
    $XCpu = $_SESSION['XCpu'];
    $YCpu = $_SESSION['YCpu'];
   
    $X = filter_input(INPUT_POST, 'f');
    $Y = filter_input(INPUT_POST, 'c');

    $movValido = comprobarJugadaUsuario($X, $Y, $XUsuario, $YUsuario);
     
   
    if ($movValido) {
        $tablero[$XUsuario][$YUsuario] = "";
        $tablero[$X][$Y] = "*";
        $result["X"] = $X;
        $result["Y"] = $Y;
        $result["XUsuario"] = $XUsuario;
        $result["YUsuario"] = $YUsuario;

        if ($X == $XCpu && $Y == $YCpu) {
            $result["gameRes"] = 1;
        } else {
            $coordMovCPU = moverCPU($tablero, $XCpu, $YCpu);
            $XMovCPU = $coordMovCPU[0];
            $YMovCPU = $coordMovCPU[1];
            $tablero[$XCpu][$YCpu] = "";
            $tablero[$XMovCPU][$YMovCPU] = "+";
            $result["XMovCPU"] = $XMovCPU;
            $result["YMovCPU"] = $YMovCPU;
            $result["XCpu"] = $XCpu;
            $result["YCpu"] = $YCpu;
            if(comprobar_llegada($XMovCPU, $YMovCPU)){
                 $result["final"] = -1;
            }
            if ($XMovCPU == $XUsuario && $YMovCPU == $YUsuario) {
                $result["final"] = -1;
            } else {
                $_SESSION['XUsuario'] = $X;
                $_SESSION['YUsuario'] = $Y;
                $_SESSION['XCpu'] = $XMovCPU;
                $_SESSION['YCpu'] = $YMovCPU;
                $_SESSION['tablero'] = $tablero;
            }
        }
    } else {
        $result["invalido"] = "¡NO PUEDES MOVERTE AHI!";
    }
      
    header('Content-type: application/json');
    echo json_encode($result);
    
    }
  




?>
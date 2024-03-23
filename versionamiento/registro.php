<?php

require_once "configuracion.php";


$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $username_err = "Ingrese un nombre de usuario.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Este mombre ya está en uso.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Upps! Algo salió mal, intenta nuevmente.";
            }

             // Close statement
             mysqli_stmt_close($stmt);
            }
        }


        if(empty(trim($_POST["password"]))){
            $password_err = "Ingrese una contraseña.";
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Esta requiere minimo 6 caracteres.";
        } else{
            $password = trim($_POST["password"]);
        }


        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Confirme su contaseña.";
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Su contraseña no coincide.";
            }
        }


        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

     
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    
            if($stmt = mysqli_prepare($link, $sql)){
             
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
    
        
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); 


                if(mysqli_stmt_execute($stmt)){
            
                    header("location: ingresar.php");
                } else{
                    echo "Algo salió mal, intenta nuevamente.";
                }
    
                mysqli_stmt_close($stmt);
            }
        }


        mysqli_close($link);
    }
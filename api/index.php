<?php
//header("Access-Control-Allow-Origin: *");

require 'config.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/login','login'); /* User login */
$app->post('/signup','signup'); /* User Signup  */
$app->post('/editUser','editUser'); /* User Edit User  */
$app->post('/feed','feed'); /* User Feeds  */
$app->post('/feedUpdate','feedUpdate'); /* User Feeds  */
$app->post('/feedDelete','feedDelete'); /* User Feeds  */
//$app->post('/userDetails','userDetails'); /* User Details */
$app->post('/getUserById','getUserById');
$app->post('/orders','orders');
$app->post('/saveOrder','saveOrder');
$app->post('/deleteOrder','deleteOrder');
$app->post('/getOrderByIdClient','getOrderByIdClient');
$app->post('/products','products');
$app->post('/test','test');



$app->run();

/************************* USER LOGIN *************************************/
/* ### User login ### */
function login() {
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    try {
        
        $db = getDB();
        $userData ='';
        $sql = "SELECT * FROM users WHERE (username=:username or email=:username) and password=:password ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $data->username, PDO::PARAM_STR);
        $password=hash('sha256',$data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount=$stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(!empty($userData))
        {
            $user_id=$userData->user_id;
            $userData->token = apiToken($user_id);
        }
        
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Bad request wrong username and password"}}';
            }

           
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


/* ### User registration ### */
function signup() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;
    $name=$data->name;
    $username=$data->username;
    $password=$data->password;
    
    try {
        
        $username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
        $emain_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
        $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);
        
        
        if (strlen(trim($username))>0 && strlen(trim($password))>0 && strlen(trim($email))>0 && $emain_check>0 && $username_check>0 && $password_check>0)
        {
            $db = getDB();
            $userData = '';
            $sql = "SELECT user_id FROM users WHERE username=:username or email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("username", $username,PDO::PARAM_STR);
            $stmt->bindParam("email", $email,PDO::PARAM_STR);
            $stmt->execute();
            $mainCount=$stmt->rowCount();
            $created=time();
            if($mainCount==0)
            {
                
                /*Inserting user values*/
                $sql1="INSERT INTO users(username,password,email,name,userType,permissionType,address,latLng,complement,cpf,cnpj,phone)VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                $stmt1 = $db->prepare($sql1);
                $password=hash('sha256',$data->password);
                $stmt1->execute(array($username,$password,$email,$name,$data->userType,$data->permissionType,$data->address,$data->latLng,$data->complement,$data->cpf,$data->cnpj,$data->phone));
                
                $userData=internalUserDetails($email);
                
            }
            
            $db = null;
         

            if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Enter valid data"}}';
            }

           
        }
        else{
            echo '{"error":{"text":"Enter valid data"}}';
        }
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function editUser(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;
    $name=$data->name;
    $username=$data->username;
        try {
        
        $username_check = preg_match('~^[A-Za-z0-9_]{3,20}$~i', $username);
        $emain_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
        
        if (strlen(trim($username))>0 && strlen(trim($email))>0 && $emain_check>0 && $username_check>0)
        {
            $db = getDB();
         
            /*Inserting user values*/
            $sql1="UPDATE users SET username=?,email=?,name=?,permissionType=?,address=?,latLng=?,complement=?,cpf=?,cnpj=?,phone=? where user_id=?;";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute(array($username,$email,$name,$data->permissionType,$data->address,$data->latLng,$data->complement,$data->cpf,$data->cnpj,$data->phone,$data->user_id));
                
            $userData=internalUserDetails($email);
                
            if($userData){
                $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
                echo '{"error":{"text":"Erro ao salvar usuario"}}';
            }
          
            $db = null;
        }
        else{
            echo '{"error":{"text":"Entre com campos validos"}}';
        }
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getUserById(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
    
    if($systemToken == $token){
    try {
            $db = getDB();
            $sql = "SELECT * FROM users WHERE user_id=:user_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $userData = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if(!empty($userData))
            {
                echo '{"user": ' .json_encode($userData) . '}';
            }else{
                echo '{"error":{"text":"Usuario n達o encontrado"}}';
            }
           
            $db = null;;
            
        
       
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    } else{
        echo '{"error":{"text":"No access"}}';
    }
}

function orders(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $db = getDB();
            $sql = "SELECT * FROM orders;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $vetor = array();
            $sqlProductsOrder = "SELECT * FROM productOrder WHERE orderId=?";
            $stmtProductsOrder = $db->prepare($sqlProductsOrder);
            while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmtProductsOrder->execute(array($line['id']));
                $productOrder = $stmtProductsOrder->fetchAll(PDO::FETCH_ASSOC);
                if($line){
                    $line['products'] = $productOrder;
                }
                $vetor[] = array_map(null, $line);
            }
            // $ordersData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"orders": ' . json_encode($vetor) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":"'. $e->getMessage() .'"}}';
    }
    
}

function saveOrder(){
   $request = \Slim\Slim::getInstance()->request();
   $dataResponse = json_decode($request->getBody());
 
   $data = $dataResponse->order;
   $dataUser = $dataResponse->user;

   $user_id=$dataUser->user_id;
   $token=$dataUser->token;
    
   $systemToken=apiToken($user_id);
    
    if($systemToken == $token){
   
    $description=$data->description;
    $userEmployee_id=$data->userEmployee_id;
    $latLng=$data->latLng;
    $timeSevered = $data->timeSevered;
    $finishedTime = $data->finishedTime;
    try {
            $db = getDB();
            if($data->id){
                $stmt1 = $db->prepare("UPDATE orders SET description=?,userEmployee_id=?,latLng=?,timeSevered=?,finishedTime=? where id=?;");
                if($userEmployee_id){
                    if($timeSevered){
                        $finishedTime = time();
                    }else{
                        $timeSevered = time();
                    }
                }
                $stmt1->execute(array($description,$userEmployee_id,$latLng,$timeSevered,$finishedTime,$data->id));
            }else{
                $stmt1 = $db->prepare("INSERT INTO orders(description,userClient_id,userEmployee_id,createdHour,latLng)VALUES(?,?,?,?,?);");
                $created = time();
                
                $stmt1->execute(array($description,$user_id,$userEmployee_id,$created,$latLng));
            }
            
            $orderData = internalOrderDetails($data->userClient_id,$finishedTime);
            if(!empty($orderData))
            {
                if(!$userEmployee_id){
                    foreach($data->deletedProducts as $value){
                     $stmt1 = $db->prepare("DELETE FROM productOrder WHERE id=?;");
                     $stmt1->execute(array($value->id));   
                    }
                    foreach($data->products as $value){
                      if($value->id){
                          $stmt1 = $db->prepare("UPDATE productOrder SET amount=? where id=?;");
                          $stmt1->execute(array($value->amount,$value->id));
                       }else{
                          $stmt1 = $db->prepare("INSERT INTO productOrder(orderId,productId,amount)VALUES(?,?,?);");
                          $stmt1->execute(array($orderData->id,$value->productId,$value->amount));
                       }
                   
                    }
                }
                $sql = "SELECT * FROM productOrder WHERE orderId=?";
                $stmt = $db->prepare($sql);
                $stmt->execute(array($orderData->id));
                $productOrder = $stmt->fetchAll(PDO::FETCH_OBJ);
                if($productOrder){
                        $orderData->products = $productOrder;
                }
                echo '{"orderResponse": ' .json_encode($orderData) . '}';
            }else{
                echo '{"error":{"text":"Orden não encontrado"}}';
            }
           
            $db = null;;
            
        
       
        } catch(PDOException $e) {
            echo '{"error":{"text":"'. $e->getMessage() .'"}}';
        }
    } else{
        echo '{"error":{"text":"No access"}}';
    }
}

function deleteOrder(){
   $request = \Slim\Slim::getInstance()->request();
   $dataResponse = json_decode($request->getBody());
 
   $data = $dataResponse->order;
   $dataUser = $dataResponse->user;

   $user_id=$dataUser->user_id;
   $token=$dataUser->token;
    
   $systemToken=apiToken($user_id);
    
    if($systemToken == $token){
   
    $orderId=$data->id;
    try {
            $db = getDB();
            
            foreach($data->products as $value){
                $stmt1 = $db->prepare("DELETE FROM productOrder WHERE id=?;");
                $stmt1->execute(array($value->id));
            }
           
           $stmt1 = $db->prepare("DELETE FROM orders WHERE id=?;");
           $stmt1->execute(array($orderId));
           
          
            $orderData = internalOrderDetails($data->userClient_id,"0");
            if(empty($orderData))
            {
                echo '{"status": {"text":"Orden deletado"}}';
            }else{
                echo '{"error":{"text":"Orden não deletado"}}';
            }
           
            $db = null;;
            
        
       
        } catch(PDOException $e) {
            echo '{"error":{"text":"'. $e->getMessage() .'"}}';
        }
    } else{
        echo '{"error":{"text":"No access"}}';
    }
}


function getOrderByIdClient(){
  $request = \Slim\Slim::getInstance()->request();
  $data = json_decode($request->getBody());
  $user_id=$data->user_id;
  $token=$data->token;
  if($data->employee_id){
      $systemToken=apiToken($data->employee_id);
  }else{
      $systemToken=apiToken($user_id);
  }
    
   
    
  if($systemToken == $token){
    try {
            $db = getDB();
            $sql = "SELECT * FROM orders WHERE userClient_id=? and finishedTime=0";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($user_id));
            $orderData = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if(!empty($orderData))
            {
                $sql = "SELECT * FROM productOrder WHERE orderId=?";
                $stmt = $db->prepare($sql);
                $stmt->execute(array($orderData[0]->id));
                $productOrder = $stmt->fetchAll(PDO::FETCH_OBJ);
                if($productOrder){
                    $orderData[0]->products = $productOrder;
                }
                echo '{"orderResponse": ' .json_encode($orderData[0]) . '}';
            }else{
                echo '{"error":{"text":"Orden n達o encontrado'.$data->employee_id.'"}}';
            }
           
            $db = null;;
            
        
       
        } catch(PDOException $e) {
            echo '{"error":{"text":"'. $e->getMessage() .'"}}';
        }
    } else{
        echo '{"error":{"text":"No access"}}';
    }
}

function test(){
   $request = \Slim\Slim::getInstance()->request();
   $data = $request->getBody();
   
   
    $db = getDB();
            $sql = "SELECT * FROM orders WHERE userClient_id=8 and finishedTime=0";
            $stmt = $db->prepare($sql);
            $stmt->execute(array($user_id));
            $orderData = $stmt->fetchAll(PDO::FETCH_OBJ);
   
   echo '{"error":' .json_encode($orderData) . '}';
}

function products(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "SELECT * FROM product;";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $productsData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"products": ' . json_encode($productsData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":"'. $e->getMessage() .'"}}';
    }
}

/* ### internal Username Details ### */
function internalUserDetails($input) {
    
    try {
        $db = getDB();
        $sql = "SELECT * FROM users WHERE username=:input or email=:input";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("input", $input,PDO::PARAM_STR);
        $stmt->execute();
        $usernameDetails = $stmt->fetch(PDO::FETCH_OBJ);
        $usernameDetails->token = apiToken($usernameDetails->user_id);
        $db = null;
        return $usernameDetails;
        
    } catch(PDOException $e) {
        echo '{"error":{"text":"'. $e->getMessage() .'"}}';
    }
    
}

function internalOrderDetails($input,$time) {
    
    try {
        $db = getDB();
        $sql = "SELECT * FROM orders WHERE userClient_id=? and finishedTime=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($input,$time));
        $orderDetails = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $orderDetails;
        
    } catch(PDOException $e) {
        echo '{"error":{"text":"'. $e->getMessage() .'"}}';
    }
    
}

function feed(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);
           
            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
    
    
}

function feedUpdate(){

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    $feed=$data->feed;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
         
            
            $feedData = '';
            $db = getDB();
            $sql = "INSERT INTO feed ( feed, created, user_id_fk) VALUES (:feed,:created,:user_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("feed", $feed, PDO::PARAM_STR);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $created = time();
            $stmt->bindParam("created", $created, PDO::PARAM_INT);
            $stmt->execute();
            


            $sql1 = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }

}

function feedDelete(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $token=$data->token;
    $feed_id=$data->feed_id;
    
    $systemToken=apiToken($user_id);
   
    try {
         
        if($systemToken == $token){
            $feedData = '';
            $db = getDB();
            $sql = "Delete * FROM feed WHERE user_id_fk=:user_id AND feed_id=:feed_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("feed_id", $feed_id, PDO::PARAM_INT);
            $stmt->execute();
            
           
            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
        } else{
            echo '{"error":{"text":"No access"}}';
        }
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    
    
    
}






?>
<?php
require_once __DIR__ . '/../../classes/database/Connect.php';

class Request extends Connect{
    Public $email;
    private $pass;
    public function __construct($email, $pass){
        $this->email = $email;
        $this->pass = $pass;
    }
    public function getUser(){
        $email = $this->email;
        $pass = md5($this->pass);
        $sql = "SELECT * FROM users WHERE email= :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $row = $stmt->fetch();
            if($row['password'] == $pass){
                $this->status($row['unique_id'], 'Active');
                session_start();

                $_SESSION['unique_id']= $row['unique_id'];
                $_SESSION['fname'] = $row['fname'];
                $_SESSION['lname'] = $row['lname'];
                $_SESSION['img'] = $row['img'];
                $_SESSION['status'] = 'Active';
                return true;
            }
        }else{
            return false;
        }
    }

    public function logOut(){
        if($this->status($_SESSION['unique_id'], 'Offline')){
            session_unset();
            session_destroy();
            header('Location: http://localhost/signinup/');
        }
    }

    private function status($uniqueId, $userStatus){

        $status = "UPDATE users SET status = :userStatus WHERE unique_id = :uniqueId";
        $stmt = $this->connect()->prepare($status);
        $stmt->bindParam(':userStatus', $userStatus, PDO::PARAM_STR);
        $stmt->bindParam(':uniqueId', $uniqueId, PDO::PARAM_INT);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function getUserToChat($unique_id){
        $sql = "SELECT * FROM users WHERE unique_id = :unique_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':unique_id', $unique_id);
        $stmt->execute();
        if($stmt->rowCount() == 0){
            header('Location: http://localhost/signinup/');
            //TODO : add a request URI to handle redirect and pass it to the js file. Create a code that will get the request URI value in js.
        }
        return $stmt->fetch();
    }

    public function getMessages($outgoing_id, $incoming_id){
        $sql = "SELECT msg_id, unique_id, msg, img, outgoing_msg_id FROM messages 
                LEFT JOIN users 
                ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = :outgoing_id 
                AND incoming_msg_id = :incoming_id)
                OR (outgoing_msg_id = :incoming_id 
                AND incoming_msg_id = :outgoing_id) 
                ORDER BY msg_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam( 'outgoing_id', $outgoing_id );
        $stmt->bindParam( 'incoming_id', $incoming_id);
        $stmt->execute();

        $output = '';
        if($stmt->rowCount() > 0){
            $i =0;
            while($i < $stmt->rowCount()){
                $row = $stmt->fetch();
                $msg = $row['msg'];
                $img = $row['img'];
                $uniqueId = $row['unique_id'];
                if($incoming_id == $uniqueId){
                    $msgId = $row['msg_id'];
                    $this->msg_status($msgId);
                }
                if($row['outgoing_msg_id'] == $outgoing_id){
                    $output .= "<div class='chat outgoing'>
                                    <div class='details'>
                                        <p>{$msg}</p>
                                    </div>
                                </div>";
                }else{
                    $output .="<div class='chat incoming'>
                                    <img src='http://localhost/signinup/assets/img/{$img}' alt=''>
                                    <div class='details'>
                                        <p>{$msg}</p>
                                    </div>
                                </div>";
                }
                $i++;
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    }

    private function msg_status($msgId){
        $read = 'read';
        $sql = "UPDATE messages SET msg_status = :reads
                WHERE msg_id = :msg_id";
        $msgStatus = $this->connect()->prepare($sql);
        $msgStatus->bindParam(':msg_id', $msgId, PDO::PARAM_INT);
        $msgStatus->bindParam(':reads', $read, PDO::PARAM_STR);
        
        if($msgStatus->execute()){
            return;
        }

        return;

        // $status = "UPDATE users SET status = :userStatus WHERE unique_id = :uniqueId";
        // $stmt = $this->connect()->prepare($status);
        // $stmt->bindParam(':userStatus', $userStatus, PDO::PARAM_STR);
        // $stmt->bindParam(':uniqueId', $uniqueId, PDO::PARAM_INT);
        // if($stmt->execute()){
        //     return true;
        // }
        // return false;
    }

    public function sendMsg($message, $incomingId, $sessionId){
        
        // $outgoing_id = isset($_SESSION['unique_id']);
        $sql = 'INSERT INTO messages 
                (incoming_msg_id, outgoing_msg_id, msg, msg_status)
                VALUES (?,?,?,?)';
        $stmt = $this->connect()->prepare($sql);
        if($stmt->execute([$incomingId, $sessionId, $message, 'unread'])){
            return json_encode(['message'=> 'sent'], 200);
        }
    }

    public function getAllUser($sessionId) {

        $sql = "SELECT * FROM users 
                WHERE NOT unique_id = :sessionId 
                ORDER BY status ASC";
    
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':sessionId', $sessionId, PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $output = "";
    
        if (count($users) == 0) {
            $output .= "No users are available to chat";
        } elseif (count($users) > 0) {
            foreach ($users as $row) {
                $sql2 = "SELECT * FROM messages 
                        WHERE (incoming_msg_id = :incomingMsgId
                        OR outgoing_msg_id = :outgoingMsgId) 
                        AND (outgoing_msg_id = :sessionId 
                        OR incoming_msg_id = :sessionId) 
                        ORDER BY msg_id DESC LIMIT 1";
    
                $stmt2 = $this->connect()->prepare($sql2);
                $stmt2->bindParam(':incomingMsgId', $row['unique_id'], PDO::PARAM_INT);
                $stmt2->bindParam(':outgoingMsgId', $row['unique_id'], PDO::PARAM_INT);
                $stmt2->bindParam(':sessionId', $sessionId, PDO::PARAM_INT);
                $stmt2->execute();
                $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
                $msg_status = $row2['msg_status'] ?? '';
    
                $result = $stmt2->rowCount() > 0 ? $row2['msg'] : "No message available";
                $msg = strlen($result) > 28 ? substr($result, 0, 28) . '...' : $result;
    
                $unread = '';
                $msgClass = '';
    
                if (isset($row2['outgoing_msg_id']) && $sessionId == $row2['outgoing_msg_id']) {
                    $you = "You: ";
                    $unread = '';
                } else {
                    $you = '';
                    $msg_status === "unread" ? $unread = "Unread :" : $unread = '';
                    $msg_status === "unread" ? $msgClass = 'unread' : $msgClass = '';
                }
    
                $offline = ($row['status'] == "Offline") ? "offline" : "";
    
                $output .= '<a href="http://localhost/signinup/?user=chat&user_id=' . $row['unique_id'] . '">
                            <div class="content">
                            <img src="http://localhost/signinup/assets/img/' . $row['img'] . '" alt="">
                            <div class="details">
                                <span>' . $row['fname'] . " " . $row['lname'] . '</span>
                                <p class="' . $msgClass . '">' . $unread . $you . $msg . '</p>
                            </div>
                            </div>
                            <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                        </a>';
            }
    
            return $output;
        }
    }
    
    // public function getAllUser($sessionId){

    //     $sql = "SELECT * FROM users 
    //             WHERE NOT unique_id = {$sessionId} 
    //             ORDER BY status ASC";

    //     $query = mysqli_query($this->mysql_conn(), $sql);
    //     $output = "";

    //     if(mysqli_num_rows($query) == 0){
    //         $output .= "No users are available to chat";
    //     }elseif(mysqli_num_rows($query) > 0){
    //         while($row = mysqli_fetch_assoc($query)){
    //             $sql2 = "SELECT * FROM messages 
    //                     WHERE (incoming_msg_id = {$row['unique_id']}
    //                     OR outgoing_msg_id = {$row['unique_id']}) 
    //                     AND (outgoing_msg_id = {$sessionId} 
    //                     OR incoming_msg_id = {$sessionId}) 
    //                     ORDER BY msg_id DESC LIMIT 1";

    //             $query2 = mysqli_query($this->mysql_conn(), $sql2);
    //             $row2 = mysqli_fetch_assoc($query2);
    //             $msg_status = $row2['msg_status'] ?? '';

    //             (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="No message available";
    //             (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
    //             $unread ='';
    //             $msgClass = '';
    //             if(isset($row2['outgoing_msg_id']) && $sessionId == $row2['outgoing_msg_id']){
    //                 $you = "You: ";
    //                 $unread ='';
    //             }else{
    //                 $you = '';
    //                 $msg_status === "unread" ? $unread = "Unread :" : $unread ='';
    //                 $msg_status === "unread" ? $msgClass = 'unread' : $msgClass = '';
    //             }

    //             $row['status'] == "Offline" ? $offline = "offline" : $offline = "";
        
    //             $output .= '<a href="http://localhost/signinup/?user=chat&user_id='. $row['unique_id'] .'">
    //                         <div class="content">
    //                         <img src="http://localhost/signinup/assets/img/'. $row['img'] .'" alt="">
    //                         <div class="details">
    //                             <span>'. $row['fname']. " " . $row['lname'] .'</span>
    //                             <p class="'.$msgClass.'">'. $unread . $you . $msg .'</p>
    //                         </div>
    //                         </div>
    //                         <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
    //                     </a>';
    //         }

    //         return $output;
    //     }
    // }

    // public function getLatestMsg($outgoing, $incoming){
    //     $sql = "SELECT * FROM messages 
    //             WHERE incoming_msg_id = :unique_id
    //             OR outgoing_msg_id = :unique_id
    //             AND outgoing_msg_id = :sessionId
    //             OR incoming_msg_id = :sessionId
    //             ORDER BY msg_id DESC LIMIT 1";
    //     return $stmt = $this->connect()->prepare($sql);
    //     $stmt->bindParam(':unique_id', $incoming);
    //     $stmt->bindParam(':user_id', $outgoing);
    //     $stmt->execute();
    //     return $stmt->fetch();
    // }

    public function searchChatUser($searchTerm, $sessionId){
        $search = "SELECT * FROM users
                    WHERE NOT unique_id = {$sessionId}
                    AND fname LIKE '%{$searchTerm}%'
                    OR lname LIKE '%{$searchTerm}%'
                    ORDER BY status ASC";
        // $stmt = $this->connect()->prepare($search);
        // $stmt->bindParam(':sessionId', $sessionId);
        // $stmt->bindParam(':searchTerm', $searchTerm);
        // $stmt->execute();
        $stmt = mysqli_query($this->mysql_conn(), $search);
        
        $row_count = mysqli_num_rows($stmt);
        $output = "";
        $i= 0;
        if($row_count > 0){
            while($i< $row_count){
                $row = mysqli_fetch_assoc($stmt);
                $sql2 = "SELECT * FROM messages 
                            WHERE (incoming_msg_id = {$row['unique_id']}
                            OR outgoing_msg_id = {$row['unique_id']}) 
                            AND (outgoing_msg_id = {$sessionId} 
                            OR incoming_msg_id = {$sessionId}) 
                            ORDER BY msg_id DESC LIMIT 1";

                    $query2 = mysqli_query($this->mysql_conn(), $sql2);
                    $row2 = mysqli_fetch_assoc($query2);
                    $msg_status = $row2['msg_status'] ?? '';

                    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="No message available";
                    (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
                    $unread ='';
                    $msgClass = '';
                    if(isset($row2['outgoing_msg_id']) && $sessionId == $row2['outgoing_msg_id']){
                        $you = "You: ";
                        $unread ='';
                    }else{
                        $you = '';
                        $msg_status === "unread" ? $unread = "Unread :" : $unread ='';
                        $msg_status === "unread" ? $msgClass = 'unread' : $msgClass = '';
                    }

                    $row['status'] == "Offline" ? $offline = "offline" : $offline = "";
            
                    $output .= '<a href="http://localhost/signinup/?user=chat&user_id='. $row['unique_id'] .'">
                                <div class="content">
                                <img src="http://localhost/signinup/assets/img/'. $row['img'] .'" alt="">
                                <div class="details">
                                    <span>'. $row['fname']. " " . $row['lname'] .'</span>
                                    <p class="'.$msgClass.'">'. $unread . $you . $msg .'</p>
                                </div>
                                </div>
                                <div class="status-dot '. $offline .'"><i class="fas fa-circle">'.$row['status'].'</i></div>
                            </a>';
                    $i++;
                }
        }else{
            $output .= "<span style='margin: 0 auto;text-align:center;display:block;'>No User found!</span>";
        }
        echo $output;
    }
}
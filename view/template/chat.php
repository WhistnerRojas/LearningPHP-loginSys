<div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
            $getUser = new Request('', '');
            if(isset($_GET['user_id'])){
                $userId = $_GET['user_id'];
        ?>
        <a href="http://localhost/signinup/" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="./assets/img/<?php echo $getUser->getUserToChat($userId)['img']; ?>" alt="">
        <div class="details">
          <span><?php 
            echo $getUser->getUserToChat($userId)['fname']. " " . $getUser->getUserToChat($userId)['lname'] 
          ?></span>
          <p><?php 
            echo $getUser->getUserToChat($userId)['status'];
          ?></p>
        </div>
      </header>
      <div class="chat-box">
              
      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $userId; }?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
</div>

<script src="./assets/js/chat.js"></script>
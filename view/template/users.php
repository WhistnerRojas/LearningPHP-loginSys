
    <div class="wrapper">
        <section class="users">
            <header>
                <div class="content">
                    <div class="dp_img">
                        <img src="./assets/img/<?php 
                                            echo $_SESSION['img'];
                                        ?>" alt="">
                    </div>
                    <div class="details">
                        <span><?php 
                                    echo $_SESSION['fname'].' '.$_SESSION['lname'];
                                ?></span>
                        <p><?php 
                            echo $_SESSION['user_status']; 
                            ?><span class="status-dot"><i class="fas fa-circle"></i></span></p>
                    </div>
                </div>
                <a href="./?user=logout" class="logout">Logout</a>
            </header>
            <div class="search">
                <span class="text">Select a user to start chat</span>
                <input type="text" placeholder="Enter name to search...">
                <button><i class="fas fa-search"></i></button>
            </div>
            <div class="users-list">
        
            </div>
        </section>
    </div>
    <script src="javascript/users.js"></script>
    <script src="./assets/js/chatSearch.js"></script>

    <div class="wrapper">
        <section class="users">
        <header>
            <div class="content">
            <?php 
                // $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                // if(mysqli_num_rows($sql) > 0){
                // $row = mysqli_fetch_assoc($sql);
                // }
            ?>
            <img src="./assets/img/<?php 
                                    // echo $row['img']; 
                                ?>" alt="">
            <div class="details">
                <span><?php 
                            // echo $row['fname']. " " . $row['lname'] 
                        ?></span>
                <p><?php 
                    // echo $row['status']; 
                    ?></p>
            </div>
            </div>
            <a href="./?user=logout" class="logout">Logout</a>
        </header>
        <div class="search">
            <span class="text">Select an user to start chat</span>
            <input type="text" placeholder="Enter name to search...">
            <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">
    
        </div>
        </section>
    </div>

    <script src="javascript/users.js"></script>

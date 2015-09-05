<div class="login-box">
    <h2>Bentornato</h2>
    <div class="sub-login-box">
        <form method="post" action="login">
            <input type="hidden" name="cmd" value="login"/>    
            <label for="username">Username</label>
            <input class="text" name="username" id="username" placeholder="Username" autofocus required><br>
            <label for="password">Password</label>
            <input class="text" name="password" id="password" type="password" placeholder="<?php for ($i=0; $i<8; $i++) echo '&#8226;' ?>" required>
            <input type="submit" value="Login">
        </form>
    </div>
</div>
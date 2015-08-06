{block name="main_content"}
    <h1>Log In</h1>
    <form action="/user/signup" method="post">
        <div class="form-group">
            Username:<br>
            <input type="text" name="username" placeholder="Username" />
        </div>
        
        <div class="form-group">
            Password:<br>
            <input type="password" name="password" placeholder="Password" />
        </div>
        
        <div class="form-group">
            <button type="submit" name="signup">Log In</button>
        </div>
    </form>
{/block}

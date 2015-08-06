{block name="main_content"}
    <h1>Sign up</h1>
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
            Full Name:<br>
            <input type="text" name="name" placeholder="Full Name" />
        </div>
        <div class="form-group">
            <button type="submit" name="signup">Sign Up</button>
        </div>
    </form>
{/block}

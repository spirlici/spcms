{block name="main_content"}
    <h1>Log In</h1>
    
    {if $errors}
        <h3 class="errors">You have some errors!</h3>
        <ol class="errors">
            {foreach $errors as $error}
                <li>{$error}</li>
            {/foreach}
        </ol>
    {/if}
    
    <form action="/user/login" method="POST">
        <div class="form-group">
            Email:<br>
            <input type="text" name="email" placeholder="Email" />
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

{block name="main_content"}
    
    <h1>Hi {$user->get('name')}!</h1>
    
    {if $errors}
        <h3 class="errors">You have some errors!</h3>
        <ol class="errors">
            {foreach $errors as $error}
                <li>{$error}</li>
            {/foreach}
        </ol>
    {/if}

    <form action="/user/profile" method="post">
        <div class="form-group">
            Email:<br>
            <input type="text" name="email" placeholder="Email" value="{$user->get('email')}" />
        </div>

        <div class="form-group">
            Full Name:<br>
            <input type="text" name="name" placeholder="Full Name" value="{$user->get('name')}" />
        </div>
        <div class="form-group">
            Password:<br>
            <input type="password" name="password" placeholder="Password" />
        </div>
        <div class="form-group">
            Confirm password:<br>
            <input type="password" name="password_confirm" placeholder="Confirm password" />
        </div>
        <div class="form-group">
            <button type="submit" name="signup">Save</button>
        </div>
        
        
    </form>
{/block}

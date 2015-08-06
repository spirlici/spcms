<html>
    <head>
        <style>
            button,
            input[type=text],
            input[type=password]
            {
                border-radius: 5px; 
                padding: 5px;
                border: 1px solid #bbb;
            }
            .form-group {
                padding-bottom: 20px;
            }
        </style>
    </head>
    <body>
    <div class="navbar">
        {if not $user->is_log()}
            <a href="/user/login">Log In</a>
            &nbsp;|&nbsp;
            <a href="/user/signup">Sign Up</a>
        {else}
            <a href="/user/profile">Profile</a>
        {/if}
        &nbsp;|&nbsp;
        <a href="https://github.com/spirlici/spcms" target="_blank">GitHub</a>
        
    </div>
        {block name="main_content"}{/block}
    </body>
</html>
<html>
    <head>
        <style>
            body, html{
                padding: none;
                margin: 10px;
            }
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
            .navbar {
                padding: 5px 0;
                border-bottom: 1px solid #AAA;
            }
            .errors {
                color: red;
            }
        </style>
    </head>
    <body>
    <div class="navbar">
        <a href="/">Home</a>&nbsp;|&nbsp;
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
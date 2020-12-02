<style>
	#login-form{
		margin-top: 2em;
		margin-bottom: 5em;
	}
	#UserEmail, #loginButton {
		margin-bottom: 1.5em;
	}
	article {
		margin-bottom: 10em;
	}
</style>
<article class="app_content">
	<div class="row">
        <div id="login-form" class="span2 offset7">
            <form  method="post" action="<?=$CFG["base_url"];?>user/login">
                <input name="login" type="text" required class="input span2" value="" placeholder="Login" autofocus/>
                <input name="pass" type="password" required class="input span2" value="" id="pass" placeholder="Password"/>
                <input type="submit" class="btn btn-primary btn-large btn-block" id="loginButton" value="Войти" />
            </form>

            <p class="text-center">
                Have problems with log in?<br>
                <a href="mailto:stacmv@gmail.com">Email to support team.</a>
            </p>
        </div>
    </div>
</article> <!-- /.app_content -->

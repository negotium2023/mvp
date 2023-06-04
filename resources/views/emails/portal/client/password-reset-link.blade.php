<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password</title>
    </head>
    <body>
        <h2>Hello!</h2>
        <p>
            You are receiving this email because we received a password reset request for your account.
        </p>
        <p>
            <a target="_blank" rel="noopener noreferrer" href="{{env('APP_URL')}}/portal/client/password/reset/{{$token}}" class="button button-blue" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">Reset Password</a>
        </p>
        <p>
            If you did not request a password reset, no further action is required.
        </p>
        <p>
            Regards,<br/>
            Blackboard
        </p>
        <p>
            If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br/>
            {{env('APP_URL')}}/portal/client/password/reset/{{$token}}
        </p>
    </body>
</html>
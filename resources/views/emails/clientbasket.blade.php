<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<strong>Dear client,</strong>

<p>This message is sent to you on behalf of your Financial Advisor.</p>

<p>Please open the following link <a href="{{url('/client/'.$clientid.'/progress/'.$process_id.'/'.$step_id)}}">{{url('/client/'.$clientid.'/progress/'.$process_id.'/'.$step_id)}}</a> to complete the sections of your {{$process_name}} application.</p>

<p>Username: <strong>{{$email}}</strong></p>
<p>Password: <strong>{{$password}}</strong></p>

<p><strong>Thank you on behalf of your Financial Advisor.</strong></p>

</body>

</html>
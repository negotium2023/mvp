<div class="client-sidemenu elevation-3">
    <img src="{{route('portal.client.avatar',$client)}}" class="client-avatar"/>
    <dd class="seperator">
        &nbsp;
    </dd>
    <dt>
        Full Name
    </dt>
    <dd>
        {{$client->first_name}}
    </dd>
    <dt>
        Surname
    </dt>
    <dd>
        {{$client->last_name}}
    </dd>
    <dt>
        ID Number
    </dt>
    <dd>
        {{$client->id_number}}
    </dd>
    <dd class="seperator">
        &nbsp;
    </dd>
    <dt>
        Email Address
    </dt>
    <dd>
        {{$client->email}}
    </dd>
    <dt>
        Contact Number
    </dt>
    <dd>
        {{(substr($client->contact,0,1) == '0' ? '+27'.substr($client->contact,1) : $client->contact )}}
    </dd>
    <dt>
        Reference
    </dt>
    <dd>
        {{($client->reference == '' ? '&nbsp;' : $client->reference)}}
    </dd>
</div>
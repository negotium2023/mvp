<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /*Now the CSS*/
        html,body{
            text-align: center !important;
        }
        * {margin: 0; padding: 0;}

        .tree ul {
            padding-top: 20px; position: relative;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        .tree li {
            float: left; text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        .tree li > div{
            border:1px solid #ccc;
            display: inline-block;
            padding: 5px 10px;
            min-width: 300px;
            width: 300px;
            border-radius: 5px;
            min-height:150px;
        }

        /*.tree > ul > li > div {
            background-color: rgba(198,21,44,0.8);
            color:#FFFFFF;
            margin-right: 1em;
        }

        .tree ul > li > ul > li > div {
            background-color: rgba(221,5,52,0.8);
            color:#FFFFFF;
        }

        !*** TERTIARY ***!
        .tree ul > li > ul > li > ul > li > div {
            background-color: rgba(229,51,49,0.8);
            color:#FFFFFF;
        }

        !*** QUATERNARY ***!
        .tree ul > li > ul > li > ul > li > ul > li > div {
            background-color: rgba(233,87,38,0.8);
            color:#FFFFFF;
        }

        !*** QUINARY ***!
        .tree ul > li > ul > li > ul > li > ul > li > ul > li > div {
            background-color: rgba(239,117,32,0.8);
            color:#FFFFFF;
        }*/

        /*We will use ::before and ::after to draw the connectors*/

        .tree li::before, .tree li::after{
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 2px solid #000;
            width: 50%; height: 20px;
        }
        .tree li::after{
            right: auto; left: 50%;
            border-left: 2px solid #000;
        }

        /*We need to remove left-right connectors from elements without
        any siblings*/
        .tree li:only-child::after, .tree li:only-child::before {
            display: none;
        }

        /*Remove space from the top of single children*/
        .tree li:only-child{ padding-top: 0;}

        /*Remove left connector from first child and
        right connector from last child*/
        .tree li:first-child::before, .tree li:last-child::after{
            border: 0 none;
        }
        /*Adding back the vertical connector to the last nodes*/
        .tree li:last-child::before{
            border-right: 2px solid #000;

        }
        .tree li:first-child::after{
        }

        /*Time to add downward connectors from parents*/
        .tree ul ul::before{
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 2px solid #000;
            width: 0; height: 20px;
        }

        .tree li a{
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-decoration: none;
            color: #666;
            font-family: arial, verdana, tahoma;
            font-size: 11px;
            display: inline-block;

            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;

            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }

        /*Time for some hover effects*/
        /*We will apply the hover effect the the lineage of the element also*/
        .tree li a:hover, .tree li a:hover+ul li a {
            background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
        }
        /*Connector styles on hover*/
        .tree li a:hover+ul li::after,
        .tree li a:hover+ul li::before,
        .tree li a:hover+ul::before,
        .tree li a:hover+ul ul::before{
            border-color:  #94a0b4;
        }


        .primary{
            background-color:#ccffcc !important;
            color: #000000;
        }

        .exposure{
            background-color:#ffe4cf !important;
            color: #000000;
        }

        .noexposure{
            background-color:#d9d9d9 !important;
            color: #000000;
        }
    </style>
    <link rel="stylesheet" href="{!! asset('adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/custom.css') !!}">
</head>
<body>
@php

    function PHPtoOrgChart(array $arr,array $client,$title='') {

    echo '


    <ul id="orgtree">
		<li>
                    <div class="primary">
                        <div class="relationship" style="display:block;position:relative">Primary</div>
                        <div class="name pn" style="display:block;position:relative">'.($client['company'] != null ? $client['company'] : $client['first_name'].' '.$client['last_name']).'</div>
                        <div class="role" style="display:block;position:relative">'.($client['cif_code'] != null ? '('.$client['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($client['company_registration_number'] ? $client['company_registration_number'] : '').'</div>
                    </div>';
            echo '<ul class="'.(count($arr) == 1 ? 'single' : '').'"">';

                    foreach ($arr as $rp){
                    if(isset($rp['children'])){
                    //var_dump($rp);
                    echo '<li><div class="user '.($rp["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                        <div class="relationship" style="display:block;position:relative">'.$rp['description'].'</div>
                        <div class="name" style="display:block;position:relative">'.$rp['name'].'</div>
                        <div class="role" style="display:block;position:relative">'.($rp['cif_code'] != null ? '('.$rp['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($rp['company_registration_number'] != null ? $rp['company_registration_number'] : '').'</div>
                    </div>';

                    echo '<ul class="'.(count($rp['children']) == 1 ? 'single' : '').'">';
                        foreach ($rp['children'] as $rpc){
                        echo '<li>
                            <div class="user '.($rpc["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                                <div class="relationship" style="display:block;position:relative">'.$rpc['description'].'</div>
                                <div class="name" style="display:block;position:relative">'.$rpc['name'].'</div>
                                <div class="role" style="display:block;position:relative">'.($rpc['cif_code'] != null ? '('.$rpc['cif_code'].')' : '').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rpc['company_registration_number'] != null ? $rpc['company_registration_number'] : '').'</div>
                            </div>';
                            if(isset($rpc['children'])){
                            echo '<ul class="'.(count($rpc['children']) == 1 ? 'single' : '').'">';
                                foreach ($rpc['children'] as $rpc2){
                                echo '<li>
                                    <div class="user '.($rpc2["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                                        <div class="relationship" style="display:block;position:relative">'.$rpc2['description'].'</div>
                                        <div class="name" style="display:block;position:relative">'.$rpc2['name'].'</div>
                                        <div class="role" style="display:block;position:relative">'.($rpc2['cif_code'] != null ? '('.$rpc2['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($rpc2['company_registration_number'] != null ? $rpc2['company_registration_number'] : '').'</div>
                                    </div>';
                                    if(isset($rpc2['children'])){
                                    echo '<ul class="'.(count($rpc2['children']) == 1 ? 'single' : '').'">';
                                        foreach ($rpc2['children'] as $rpc3){
                                        echo '<li>
                                            <div class="user '.($rpc3["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                                                <div class="relationship" style="display:block;position:relative">'.$rpc3['description'].'</div>
                                                <div class="name" style="display:block;position:relative">'.$rpc3['name'].'</div>
                                                <div class="role" style="display:block;position:relative">'.($rpc3['cif_code'] != null ? '('.$rpc3['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($rpc3['company_registration_number'] != null ? $rpc3['company_registration_number'] : '').'</div>
                                            </div>';
                                            if(isset($rpc3['children'])){
                                            echo '<ul class="'.(count($rpc3['children']) == 1 ? 'single' : '').'">';
                                                foreach ($rpc3['children'] as $rpc4){
                                                echo '<li>
                                                    <div class="user '.($rpc4["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                                                        <div class="relationship" style="display:block;position:relative">'.$rpc4['description'].'</div>
                                                        <div class="name" style="display:block;position:relative">'.$rpc4['name'].'</div>
                                                        <div class="role" style="display:block;position:relative">'.($rpc4['cif_code'] != null ? '('.$rpc4['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($rpc4['company_registration_number'] != null ? $rpc4['company_registration_number'] : '').'</div>
                                                    </div>
                                                </li>';
                                                }
                                                echo '</ul>';
                                            }
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                    }
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                            echo '</li>';
                        }
                        echo '</li>';
                        echo '</ul>';
                    } else {
                    echo '<li><div class="user '.($rp["exposure"] > 0 ? 'exposure' : 'noexposure').'">
                        <div class="relationship" style="display:block;position:relative">'.$rp['description'].'</div>
                        <div class="name" style="display:block;position:relative">'.$rp['name'].'</div>
                        <div class="role" style="display:block;position:relative">'.($rp['cif_code'] != null ? '('.$rp['cif_code'].')' : '').'</div>
                        <div class="role" style="display:block;position:relative">'.($rp['company_registration_number'] != null ? $rp['company_registration_number'] : '').'</div>
                    </div></li>';
                    }

           // echo '</ul>';
            }
            echo '</li>
            </ul>
        ';
    }

@endphp
<input type="hidden" id="client_id" value="{{ $client['id'] }}">
        <div class="tree" id="tree">
        @php
            PHPtoOrgChart($orgo,$client,'');
        @endphp
        </div>
@if(count($orgo) <= 0)
    <style>
        .tree ul ul::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            border-left: 0px solid #000;
            width: 0;
            height: 20px;
        }

        .single{
            padding-top:0px;
        }
    </style>
@endif

<div class="blackboard-fab mr-3 mb-3">
    <button class="btn btn-info btn-lg form-inline" id="view_org">Download Treeview</button>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="{!! asset('js/html2canvas.js') !!}"></script>

<script>
    var $ul = $("ul");

    var ulWidth = 0;
    var count = 0;
    $("li").each(function() {
        ulWidth = ulWidth + $(this).width()
        count = count + 1;
    });
    var ulWidth2 = ulWidth / 2;

    $( document ).ready(function() {


        let treeheight = $(window).height();
        let treewidth = $(window).width();

        $('#tree').css('height', treeheight);

        if(ulWidth > treewidth) {

            $('#tree').css('width', ulWidth);
        }

        var $el = $("#tree");
        var elHeight = $el.outerHeight();
        var elWidth = $el.outerWidth();

        var inner = $('#orgtree li:first');

        var w = inner.outerWidth(true);
        var h = inner.outerHeight(true);
        $('#tree').css('width', w);
        $('#tree').css('height', h+20);
        var $wrapper = $("#tree");

        var scale, origin;

        scale = Math.min(
            treewidth / w,
            treeheight / elHeight
        );

        if(w > treewidth) {

            var pad_x = ((w * scale) - w) / 2;
            var pad_y = ((h * scale) - h) / 2;

        $('#tree').css({transformorigin: "50% 50% 0"});
        $('#tree').css({transform: "translate("+pad_x+"px, "+pad_y+"px) scale(" + scale + ")"});

            $('#tree').css('position','absolute');
            $('#tree').css('left','10px');
            $('#tree').css('top','10px');
        /*$('#tree').css("transform-origin","0 0");*/



        }

        //make it as html5 canvas

        div_content = document.querySelector("#tree");

        html2canvas(div_content,{scale:5}).then(function(canvas) {
            data = canvas.toDataURL('image/jpeg');
            let client = {!! $client['id'] !!}
            //then call a super hero php to save the image
            save_img(data,client);
            $('#view_org').show();

        });
        setTimeout(function(){/*window.close()*/},2000);
        //here is the hero, after the capture button is clicked
        //he will take the screen shot of the div and save it as image.
        $('#view_org').click(function(){
            //get the div content
            $('#view_org').hide();

            //make it as html5 canvas
            $('#tree ul.single').css('padding-top','0');

            div_content = document.querySelector("#tree");

            html2canvas(div_content,{scale:5}).then(function(canvas) {
                let data = canvas.toDataURL('image/jpeg');

                //then call a super hero php to save the image
                //save_img(data);
                $('#view_org').show();
                let a = document.createElement('a');
                a.href = canvas.toDataURL();
                a.download = $('body').find('.pn').html()+'.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                $('#view_org').show();
                $('#tree ul.single').css('padding-top','20px');
            });
        });
    });


    //to save the canvas image
    function save_img(data,client){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/save_jpg',
            type: "POST",
            data: {data:data, client:client, _token: '{{csrf_token()}}' },
            success: function (data) {
                $('#view_org').show();
                var a = document.createElement('a');
                a.setAttribute('href', data.urlpath);
            }
        })
        //ajax method.

    }

    /*function downloadFile(data, fileName, type="text/plain") {
        // Create an invisible A element
        const a = document.createElement("a");
        a.style.display = "none";
        document.body.appendChild(a);

        // Set the HREF to a Blob representation of the data to be downloaded
        a.href = window.URL.createObjectURL(
            new Blob([data], { type })
        );

        // Use download attribute to set set desired file name
        a.setAttribute("download", fileName);

        // Trigger the download by simulating click
        a.click();

        // Cleanup
        window.URL.revokeObjectURL(a.href);
        document.body.removeChild(a);
    }*/
</script>
</body>
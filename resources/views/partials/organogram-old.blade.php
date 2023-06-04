<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        *,
        *:before,
        *:after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #1d1f20;
            font-family: 'Helvetica Neue', 'Helvetica', Arial, sans-serif;
        }

        #wrapper {
            margin-left: auto;
            margin-right: auto;
            max-width: 80em;
        }

        #container {
            float: left;
            padding: 1em;
            width: 100%;
        }

        ol.organizational-chart,
        ol.organizational-chart ol,
        ol.organizational-chart li,
        ol.organizational-chart li > div {
            position: relative;
        }

        ol.organizational-chart,
        ol.organizational-chart ol {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        ol.organizational-chart {
            text-align: center;
        }

        ol.organizational-chart ol {
            padding-top: 1em;
        }

        ol.organizational-chart ol:before,
        ol.organizational-chart ol:after,
        ol.organizational-chart li:before,
        ol.organizational-chart li:after,
        ol.organizational-chart > li > div:before,
        ol.organizational-chart > li > div:after {
            background-color: #b7a6aa;
            content: '';
            position: absolute;
        }

        ol.organizational-chart ol > li {
            padding: 1em 0 0 1em;
        }

        ol.organizational-chart > li ol:before {
            height: 1em;
            left: 50%;
            top: 0;
            width: 3px;
        }

        ol.organizational-chart > li ol:after {
            height: 3px;
            left: 3px;
            top: 1em;
            width: 50%;
        }

        ol.organizational-chart > li ol > li:not(:last-of-type):before {
            height: 3px;
            left: 0;
            top: 2em;
            width: 1em;
        }

        ol.organizational-chart > li ol > li:not(:last-of-type):after {
            height: 100%;
            left: 0;
            top: 0;
            width: 3px;
        }

        ol.organizational-chart > li ol > li:last-of-type:before {
            height: 3px;
            left: 0;
            top: 2em;
            width: 1em;
        }

        ol.organizational-chart > li ol > li:last-of-type:after {
            height: 2em;
            left: 0;
            top: 0;
            width: 3px;
        }

        ol.organizational-chart li > div {
            background-color: #fff;
            border-radius: 5px;
            padding: 1em;
            min-height:150px;
        }

        /*** PRIMARY ***/
        ol.organizational-chart > li > div {
            background-color: rgba(140,64,64,0.8);
            color:#FFFFFF;
            margin-right: 1em;
        }

        ol.organizational-chart > li > div:before {
            bottom: 2em;
            height: 3px;
            right: -1em;
            width: 1em;
        }

        ol.organizational-chart > li > div:first-of-type:after {
            bottom: 0;
            height: 2em;
            right: -1em;
            width: 3px;
        }

        ol.organizational-chart > li > div + div {
            margin-top: 1em;
        }

        ol.organizational-chart > li > div + div:after {
            height: calc(100% + 1em);
            right: -1em;
            top: -1em;
            width: 3px;
        }

        /*** SECONDARY ***/
        ol.organizational-chart > li > ol:before {
            left: inherit;
            right: 0;
        }

        ol.organizational-chart > li > ol:after {
            left: 0;
            width: 100%;
        }

        ol.organizational-chart > li > ol > li > div {
            background-color: rgba(154,76,76,0.8);
            color:#FFFFFF;
        }

        /*** TERTIARY ***/
        ol.organizational-chart > li > ol > li > ol > li > div {
            background-color: rgba(175,93,93,0.8);
            color:#FFFFFF;
        }

        /*** QUATERNARY ***/
        ol.organizational-chart > li > ol > li > ol > li > ol > li > div {
            background-color: rgba(196,111,111,0.8);
            color:#FFFFFF;
        }

        /*** QUINARY ***/
        ol.organizational-chart > li > ol > li > ol > li > ol > li > ol > li > div {
            background-color: rgba(222,144,144,0.8);
            color:#FFFFFF;
        }

        /*** MEDIA QUERIES ***/
        @media only screen and ( min-width: 64em ) {

            ol.organizational-chart {
                margin-left: -1em;
                margin-right: -1em;
            }

            /* PRIMARY */
            ol.organizational-chart > li > div {
                display: inline-block;
                float: none;
                margin: 0 1em 1em 1em;
                vertical-align: bottom;
            }

            ol.organizational-chart > li > div:only-of-type {
                margin-bottom: 0;
                /*width: calc((100% / 1) - 2em - 4px);*/
            }

            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(2),
            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(2) ~ div {
                /*width: calc((100% / 2) - 2em - 4px);*/
            }

            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(3),
            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(3) ~ div {
                width: calc((100% / 3) - 2em - 4px);
            }

            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(4),
            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(4) ~ div {
                width: calc((100% / 4) - 2em - 4px);
            }

            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(5),
            ol.organizational-chart > li > div:first-of-type:nth-last-of-type(5) ~ div {
                width: calc((100% / 5) - 2em - 4px);
            }

            ol.organizational-chart > li > div:before,
            ol.organizational-chart > li > div:after {
                bottom: -1em!important;
                top: inherit!important;
            }

            ol.organizational-chart > li > div:before {
                height: 1em!important;
                left: 50%!important;
                width: 3px!important;
            }

            ol.organizational-chart > li > div:only-of-type:after {
                display: none;
            }

            ol.organizational-chart > li > div:first-of-type:not(:only-of-type):after,
            ol.organizational-chart > li > div:last-of-type:not(:only-of-type):after {
                bottom: -1em;
                height: 3px;
                width: calc(50% + 1em + 3px);
            }

            ol.organizational-chart > li > div:first-of-type:not(:only-of-type):after {
                left: calc(50% + 3px);
            }

            ol.organizational-chart > li > div:last-of-type:not(:only-of-type):after {
                left: calc(-1em - 3px);
            }

            ol.organizational-chart > li > div + div:not(:last-of-type):after {
                height: 3px;
                left: -2em;
                width: calc(100% + 4em);
            }

            /* SECONDARY */
            ol.organizational-chart > li > ol {
                display: flex;
                flex-wrap: nowrap;
            }

            ol.organizational-chart > li > ol:before,
            ol.organizational-chart > li > ol > li:before {
                height: 1em!important;
                left: 50%!important;
                top: 0!important;
                width: 3px!important;
            }

            ol.organizational-chart > li > ol:after {
                display: none;
            }

            ol.organizational-chart > li > ol > li {
                flex-grow: 1;
                padding-left: 1em;
                padding-right: 1em;
                padding-top: 1em;
            }

            ol.organizational-chart > li > ol > li:only-of-type {
                padding-top: 0;
            }

            ol.organizational-chart > li > ol > li:only-of-type:before,
            ol.organizational-chart > li > ol > li:only-of-type:after {
                display: none;
            }

            ol.organizational-chart > li > ol > li:first-of-type:not(:only-of-type):after,
            ol.organizational-chart > li > ol > li:last-of-type:not(:only-of-type):after {
                height: 3px;
                top: 0;
                width: 50%;
            }

            ol.organizational-chart > li > ol > li:first-of-type:not(:only-of-type):after {
                left: 50%;
            }

            ol.organizational-chart > li > ol > li:last-of-type:not(:only-of-type):after {
                left: 0;
            }

            ol.organizational-chart > li > ol > li + li:not(:last-of-type):after {
                height: 3px;
                left: 0;
                top: 0;
                width: 100%;
            }

        }
    </style>
    <link rel="stylesheet" href="{!! asset('adminlte/dist/css/adminlte.min.css') !!}">
    <link rel="stylesheet" href="{!! asset('css/custom.css') !!}">
</head>
<body>
@php

    function PHPtoOrgChart(array $arr,array $client,$title='') {
    echo '<div id="wrapper">
        <div id="container">

            <ol class="organizational-chart">
                <li>
                    <div>
                        <div class="relationship" style="display:block;position:relative">Primary</div>
                        <div class="name" style="display:block;position:relative">'.($client['company'] != null ? $client['company'] : $client['first_name'].' '.$client['last_name']).'</div>
                        <div class="role" style="display:block;position:relative">( '.($client['cif_code'] != null ? $client['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($client['id_number'] != null ? $client['id_number'] : 'N/A').'</div>
                    </div>
            <ol>';

                    foreach ($arr as $rp){
                    if(isset($rp['children'])){
                    //var_dump($rp);
                    echo '<li><div class="user">
                        <div class="relationship" style="display:block;position:relative">'.$rp['description'].'</div>
                        <div class="name" style="display:block;position:relative">'.$rp['name'].'</div>
                        <div class="role" style="display:block;position:relative">( '.($rp['cif_code'] != null ? $rp['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rp['id_number'] != null ? $rp['id_number'] : 'N/A').'</div>
                    </div>';

                    echo '<ol>';
                        foreach ($rp['children'] as $rpc){
                        echo '<li>
                            <div class="user">
                                <div class="relationship" style="display:block;position:relative">'.$rpc['description'].'</div>
                                <div class="name" style="display:block;position:relative">'.$rpc['name'].'</div>
                                <div class="role" style="display:block;position:relative">( '.($rpc['cif_code'] != null ? $rpc['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rpc['id_number'] != null ? $rpc['id_number'] : 'N/A').'</div>
                            </div>';
                            if(isset($rpc['children'])){
                            echo '<ol>';
                                foreach ($rpc['children'] as $rpc2){
                                echo '<li>
                                    <div class="user">
                                        <div class="relationship" style="display:block;position:relative">'.$rpc2['description'].'</div>
                                        <div class="name" style="display:block;position:relative">'.$rpc2['name'].'</div>
                                        <div class="role" style="display:block;position:relative">( '.($rpc2['cif_code'] != null ? $rpc2['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rpc2['id_number'] != null ? $rpc2['id_number'] : 'N/A').'</div>
                                    </div>';
                                    if(isset($rpc2['children'])){
                                    echo '<ol>';
                                        foreach ($rpc2['children'] as $rpc3){
                                        echo '<li>
                                            <div class="user">
                                                <div class="relationship" style="display:block;position:relative">'.$rpc3['description'].'</div>
                                                <div class="name" style="display:block;position:relative">'.$rpc3['name'].'</div>
                                                <div class="role" style="display:block;position:relative">( '.($rpc3['cif_code'] != null ? $rpc3['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rpc3['id_number'] != null ? $rpc3['id_number'] : 'N/A').'</div>
                                            </div>
                                        </li>';
                                        }
                                        echo '</ol>';
                                    }
                                    echo '</li>';
                                }
                                echo '</ol>';
                            }
                            echo '</li>';
                        }
                        echo '</li>';
                        echo '</ol>';
                    } else {
                    echo '<li><div class="user">
                        <div class="relationship" style="display:block;position:relative">'.$rp['description'].'</div>
                        <div class="name" style="display:block;position:relative">'.$rp['name'].'</div>
                        <div class="role" style="display:block;position:relative">( '.($rp['cif_code'] != null ? $rp['cif_code'] : 'N/A').' )</div>
                        <div class="role" style="display:block;position:relative">'.($rp['id_number'] != null ? $rp['id_number'] : 'N/A').'</div>
                    </div></li>';
                    }

           // echo '</ul>';
            }
            echo '</li>
            </ol>
        </div>
    </div>';
    }

@endphp

<div class="col-sm-12">
    <div class="orgchart">
        @php
            PHPtoOrgChart($orgo,$client,'');
        @endphp
    </div>
</div>
<div class="blackboard-fab mr-3 mb-3">
    <button class="btn btn-info btn-lg form-inline" id="view_org">Download Organogram</button>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="{!! asset('js/html2canvas.js') !!}"></script>

<script>
    $(function(){
        //here is the hero, after the capture button is clicked
        //he will take the screen shot of the div and save it as image.
        $('#view_org').click(function(){
            //get the div content
            $('#view_org').hide();
            div_content = document.querySelector("body")
            //make it as html5 canvas
            html2canvas(document.body).then(function(canvas) {
                let a = document.createElement('a');
                a.href = canvas.toDataURL();
                a.download = 'organogram.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                $('#view_org').show();
                /*html2canvas(div_content,{
                    onrendered: function (canvas) {
                        let a = document.createElement('a');
                        a.href = canvas.toDataURL();
                        a.download = 'organogram.png';
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }*/
            });
        });
    });


    //to save the canvas image
    function save_img(data){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: '/save_jpg',
            type: "POST",
            data: {data:data, _token: '{{csrf_token()}}' },
            success: function (data) {
                $('#view_org').show();
                var a = document.createElement('a');
                a.setAttribute('href', data.urlpath);
                a.setAttribute('download', 'organogram.jpg');

                var aj = $(a);
                aj.appendTo('body');
                aj[0].click();
                aj.remove();
            }
        })
        //ajax method.
        /*$.post('/save_jpg', {data: data}, function(res){
            //if the file saved properly, trigger a popup to the user.
            if(res != ''){
                yes = confirm('File saved in output folder, click ok to see it!');
                if(yes){
                    location.href =document.URL+'output/'+res+'.jpg';
                }
            }
            else{
                alert('something wrong');
            }
        });*/
    }

    function downloadFile(data, fileName, type="text/plain") {
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
    }
</script>
</body>
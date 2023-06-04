<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <style>
        /*! CSS Used from: http://127.0.0.1:8000/fontawesome/css/all.css */
        .fa,.fas,.far{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
        .fa-address-card:before{content:"\f2bb";}
        .fa-angle-right:before{content:"\f105";}
        .fa-bars:before{content:"\f0c9";}
        .fa-bell:before{content:"\f0f3";}
        .fa-chart-bar:before{content:"\f080";}
        .fa-circle:before{content:"\f111";}
        .fa-clock:before{content:"\f017";}
        .fa-cog:before{content:"\f013";}
        .fa-cogs:before{content:"\f085";}
        .fa-comments:before{content:"\f086";}
        .fa-file:before{content:"\f15b";}
        .fa-file-alt:before{content:"\f15c";}
        .fa-file-export:before{content:"\f56e";}
        .fa-file-pdf:before{content:"\f1c1";}
        .fa-globe:before{content:"\f0ac";}
        .fa-id-badge:before{content:"\f2c1";}
        .fa-phone:before{content:"\f095";}
        .fa-plus:before{content:"\f067";}
        .fa-project-diagram:before{content:"\f542";}
        .fa-question-circle:before{content:"\f059";}
        .fa-search:before{content:"\f002";}
        .fa-tachometer-alt:before{content:"\f3fd";}
        .fa-tasks:before{content:"\f0ae";}
        .fa-users:before{content:"\f0c0";}
        .far{font-family:'Font Awesome 5 Free';font-weight:400;}
        .fa,.fas{font-family:'Font Awesome 5 Free';font-weight:900;}
        /*! CSS Used from: http://127.0.0.1:8000/adminlte/dist/css/adminlte.min.css */
        :root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#ffffff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:"Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;}
        *,::after,::before{box-sizing:border-box;}
        html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-overflow-style:scrollbar;-webkit-tap-highlight-color:transparent;}
        aside,footer,nav{display:block;}
        body{margin:0;font-family:"Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff;}
        [tabindex="-1"]:focus{outline:0!important;}
        hr{box-sizing:content-box;height:0;overflow:visible;}
        h3,h5{margin-top:0;margin-bottom:.5rem;}
        p{margin-top:0;margin-bottom:1rem;}
        ul{margin-top:0;margin-bottom:1rem;}
        ul ul{margin-bottom:0;}
        strong{font-weight:bolder;}
        a{color:#007bff;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects;}
        a:hover{color:#0056b3;text-decoration:none;}
        a:not([href]):not([tabindex]){color:inherit;text-decoration:none;}
        a:not([href]):not([tabindex]):focus,a:not([href]):not([tabindex]):hover{color:inherit;text-decoration:none;}
        a:not([href]):not([tabindex]):focus{outline:0;}
        img{vertical-align:middle;border-style:none;}
        label{display:inline-block;margin-bottom:.5rem;}
        button{border-radius:0;}
        button:focus{outline:1px dotted;outline:5px auto -webkit-focus-ring-color;}
        button,input,textarea{margin:0;font-family:inherit;font-size:inherit;line-height:inherit;}
        button,input{overflow:visible;}
        button{text-transform:none;}
        button,html [type=button]{-webkit-appearance:button;}
        [type=button]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none;}
        textarea{overflow:auto;resize:vertical;}
        h3,h5{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit;}
        h3{font-size:1.75rem;}
        h5{font-size:1.25rem;}
        hr{margin-top:1rem;margin-bottom:1rem;border:0;border-top:1px solid rgba(0,0,0,.1);}
        .container-fluid{width:100%;padding-right:7.5px;padding-left:7.5px;margin-right:auto;margin-left:auto;}
        .row{display:flex;flex-wrap:wrap;margin-right:-7.5px;margin-left:-7.5px;}
        .col,.col-md-10,.col-md-2,.col-md-4,.col-md-6,.col-md-8,.col-sm-12,.col-sm-2,.col-sm-3{position:relative;width:100%;min-height:1px;padding-right:7.5px;padding-left:7.5px;}
        .col{flex-basis:0;flex-grow:1;max-width:100%;}
        @media (min-width:576px){
            .col-sm-2{flex:0 0 16.666667%;max-width:16.666667%;}
            .col-sm-3{flex:0 0 25%;max-width:25%;}
            .col-sm-12{flex:0 0 100%;max-width:100%;}
        }
        @media (min-width:768px){
            .col-md-2{flex:0 0 16.666667%;max-width:16.666667%;}
            .col-md-4{flex:0 0 33.333333%;max-width:33.333333%;}
            .col-md-6{flex:0 0 50%;max-width:50%;}
            .col-md-8{flex:0 0 66.666667%;max-width:66.666667%;}
            .col-md-10{flex:0 0 83.333333%;max-width:83.333333%;}
        }
        .form-control{display:block;width:100%;padding:.375rem .75rem;font-size:1rem;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;box-shadow:inset 0 0 0 transparent;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
        @media screen and (prefers-reduced-motion:reduce){
            .form-control{transition:none;}
        }
        .form-control::-ms-expand{background-color:transparent;border:0;}
        .form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:inset 0 0 0 transparent,0 0 0 .2rem rgba(0,123,255,.25);}
        .form-control::placeholder{color:#6c757d;opacity:1;}
        .form-control:disabled{background-color:#e9ecef;opacity:1;}
        .input-group-sm>.form-control,.input-group-sm>.input-group-prepend>.input-group-text{padding:.25rem .5rem;font-size:.875rem;line-height:1.5;border-radius:.2rem;}
        .form-group{margin-bottom:1rem;}
        .form-row{display:flex;flex-wrap:wrap;margin-right:-5px;margin-left:-5px;}
        .form-row>[class*=col-]{padding-right:5px;padding-left:5px;}
        .form-inline{display:flex;flex-flow:row wrap;align-items:center;}
        @media (min-width:576px){
            .form-inline .form-control{display:inline-block;width:auto;vertical-align:middle;}
            .form-inline .input-group{width:auto;}
        }
        .btn{display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;user-select:none;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem;transition:color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
        @media screen and (prefers-reduced-motion:reduce){
            .btn{transition:none;}
        }
        .btn:focus,.btn:hover{text-decoration:none;}
        .btn:focus{outline:0;box-shadow:0 0 0 .2rem rgba(0,123,255,.25);}
        .btn:disabled{opacity:.65;box-shadow:none;}
        .btn-primary{color:#fff;background-color:#007bff;border-color:#007bff;box-shadow:0 1px 1px rgba(0,0,0,.075);}
        .btn-primary:hover{color:#fff;background-color:#0069d9;border-color:#0062cc;}
        .btn-primary:focus{box-shadow:0 1px 1px rgba(0,0,0,.075),0 0 0 .2rem rgba(0,123,255,.5);}
        .btn-primary:disabled{color:#fff;background-color:#007bff;border-color:#007bff;}
        .btn-dark{color:#fff;background-color:#343a40;border-color:#343a40;box-shadow:0 1px 1px rgba(0,0,0,.075);}
        .btn-dark:hover{color:#fff;background-color:#23272b;border-color:#1d2124;}
        .btn-dark:focus{box-shadow:0 1px 1px rgba(0,0,0,.075),0 0 0 .2rem rgba(52,58,64,.5);}
        .btn-dark:disabled{color:#fff;background-color:#343a40;border-color:#343a40;}
        .btn-sm{padding:.25rem .5rem;font-size:.875rem;line-height:1.5;border-radius:.2rem;}
        .fade{transition:opacity .15s linear;}
        @media screen and (prefers-reduced-motion:reduce){
            .fade{transition:none;}
        }
        .fade:not(.show){opacity:0;}
        .dropdown{position:relative;}
        .dropdown-menu{position:absolute;top:100%;left:0;z-index:1000;display:none;float:left;min-width:10rem;padding:.5rem 0;margin:.125rem 0 0;font-size:1rem;color:#212529;text-align:left;list-style:none;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.15);border-radius:.25rem;box-shadow:0 .5rem 1rem rgba(0,0,0,.175);}
        .dropdown-menu-right{right:0;left:auto;}
        .dropdown-divider{height:0;margin:.5rem 0;overflow:hidden;border-top:1px solid #e9ecef;}
        .dropdown-item{display:block;width:100%;padding:.25rem 1rem;clear:both;font-weight:400;color:#212529;text-align:inherit;white-space:nowrap;background-color:transparent;border:0;}
        .dropdown-item:focus,.dropdown-item:hover{color:#16181b;text-decoration:none;background-color:#f8f9fa;}
        .dropdown-item:active{color:#fff;text-decoration:none;background-color:#007bff;}
        .dropdown-item:disabled{color:#6c757d;background-color:transparent;}
        .input-group{position:relative;display:flex;flex-wrap:wrap;align-items:stretch;width:100%;}
        .input-group>.form-control{position:relative;flex:1 1 auto;width:1%;margin-bottom:0;}
        .input-group>.form-control:focus{z-index:3;}
        .input-group>.form-control:not(:last-child){border-top-right-radius:0;border-bottom-right-radius:0;}
        .input-group>.form-control:not(:first-child){border-top-left-radius:0;border-bottom-left-radius:0;}
        .input-group-prepend{display:flex;}
        .input-group-prepend{margin-right:-1px;}
        .input-group-text{display:flex;align-items:center;padding:.375rem .75rem;margin-bottom:0;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;text-align:center;white-space:nowrap;background-color:#e9ecef;border:1px solid #ced4da;border-radius:.25rem;}
        .input-group>.input-group-prepend>.input-group-text{border-top-right-radius:0;border-bottom-right-radius:0;}
        .nav{display:flex;flex-wrap:wrap;padding-left:0;margin-bottom:0;list-style:none;}
        .nav-link{display:block;padding:.5rem 1rem;}
        .nav-link:focus,.nav-link:hover{text-decoration:none;}
        .navbar{position:relative;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;padding:.5rem .5rem;}
        .navbar-nav{display:flex;flex-direction:column;padding-left:0;margin-bottom:0;list-style:none;}
        .navbar-nav .nav-link{padding-right:0;padding-left:0;}
        .navbar-nav .dropdown-menu{position:static;float:none;}
        .navbar-expand{flex-flow:row nowrap;justify-content:flex-start;}
        .navbar-expand .navbar-nav{flex-direction:row;}
        .navbar-expand .navbar-nav .dropdown-menu{position:absolute;}
        .navbar-expand .navbar-nav .nav-link{padding-right:1rem;padding-left:1rem;}
        .navbar-light .navbar-nav .nav-link{color:rgba(0,0,0,.5);}
        .navbar-light .navbar-nav .nav-link:focus,.navbar-light .navbar-nav .nav-link:hover{color:rgba(0,0,0,.7);}
        .card{position:relative;display:flex;flex-direction:column;min-width:0;word-wrap:break-word;background-color:#fff;background-clip:border-box;border:0 solid rgba(0,0,0,.125);border-radius:.25rem;}
        .card-body{flex:1 1 auto;padding:1.25rem;}
        .card-title{margin-bottom:.75rem;}
        .card-header{padding:.75rem 1.25rem;margin-bottom:0;background-color:rgba(0,0,0,.03);border-bottom:0 solid rgba(0,0,0,.125);}
        .card-header:first-child{border-radius:calc(.25rem - 0) calc(.25rem - 0) 0 0;}
        .badge{display:inline-block;padding:.25em .4em;font-size:75%;font-weight:700;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:.25rem;}
        .badge:empty{display:none;}
        .badge-pill{padding-right:.6em;padding-left:.6em;border-radius:10rem;}
        .badge-danger{color:#fff;background-color:#dc3545;}
        .media{display:flex;align-items:flex-start;}
        .media-body{flex:1;}
        .list-group{display:flex;flex-direction:column;padding-left:0;margin-bottom:0;}
        .list-group-item{position:relative;display:block;padding:.75rem 1.25rem;margin-bottom:-1px;background-color:#fff;border:1px solid rgba(0,0,0,.125);}
        .list-group-item:first-child{border-top-left-radius:.25rem;border-top-right-radius:.25rem;}
        .list-group-item:last-child{margin-bottom:0;border-bottom-right-radius:.25rem;border-bottom-left-radius:.25rem;}
        .list-group-item:focus,.list-group-item:hover{z-index:1;text-decoration:none;}
        .list-group-item:disabled{color:#6c757d;background-color:#fff;}
        .list-group-flush .list-group-item{border-right:0;border-left:0;border-radius:0;}
        .list-group-flush:first-child .list-group-item:first-child{border-top:0;}
        .list-group-flush:last-child .list-group-item:last-child{border-bottom:0;}
        .close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5;}
        .close:focus,.close:hover{color:#000;text-decoration:none;opacity:.75;}
        button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none;}
        .modal{position:fixed;top:0;right:0;bottom:0;left:0;z-index:1050;display:none;overflow:hidden;outline:0;}
        .modal-dialog{position:relative;width:auto;margin:.5rem;pointer-events:none;}
        .modal.fade .modal-dialog{transition:transform .3s ease-out;transform:translate(0,-25%);}
        @media screen and (prefers-reduced-motion:reduce){
            .modal.fade .modal-dialog{transition:none;}
        }
        .modal-content{position:relative;display:flex;flex-direction:column;width:100%;pointer-events:auto;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;box-shadow:0 .25rem .5rem rgba(0,0,0,.5);outline:0;}
        .modal-header{display:flex;align-items:flex-start;justify-content:space-between;padding:1rem;border-bottom:1px solid #e9ecef;border-top-left-radius:.3rem;border-top-right-radius:.3rem;}
        .modal-header .close{padding:1rem;margin:-1rem -1rem -1rem auto;}
        .modal-title{margin-bottom:0;line-height:1.5;}
        .modal-body{position:relative;flex:1 1 auto;padding:1rem;}
        @media (min-width:576px){
            .modal-dialog{max-width:500px;margin:1.75rem auto;}
            .modal-content{box-shadow:0 .5rem 1rem rgba(0,0,0,.5);}
        }
        .bg-info{background-color:#17a2b8!important;}
        .bg-white{background-color:#fff!important;}
        .border-bottom{border-bottom:1px solid #dee2e6!important;}
        .d-none{display:none!important;}
        .d-block{display:block!important;}
        .d-flex{display:flex!important;}
        .flex-column{flex-direction:column!important;}
        .float-right{float:right!important;}
        .mr-1{margin-right:.25rem!important;}
        .ml-1{margin-left:.25rem!important;}
        .mt-2{margin-top:.5rem!important;}
        .mt-3{margin-top:1rem!important;}
        .mr-3,.mx-3{margin-right:1rem!important;}
        .card,.mb-3{margin-bottom:1rem!important;}
        .ml-3,.mx-3{margin-left:1rem!important;}
        .mb-4{margin-bottom:1.5rem!important;}
        .pb-3{padding-bottom:1rem!important;}
        .ml-auto{margin-left:auto!important;}
        .text-center{text-align:center!important;}
        .text-muted{color:#6c757d!important;}
        @media print{
            *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
            a:not(.btn){text-decoration:underline;}
            img{page-break-inside:avoid;}
            h3,p{orphans:3;widows:3;}
            h3{page-break-after:avoid;}
            body{min-width:992px!important;}
            .navbar{display:none;}
            .badge{border:1px solid #000;}
        }
        .wrapper,body,html{min-height:100%;overflow-x:hidden;}
        .wrapper{position:relative;}
        @media (min-width:768px){
            .content-wrapper,.main-footer,.main-header{transition:margin-left .3s ease-in-out;margin-left:250px;z-index:3000;}
        }
        @media screen and (min-width:768px) and (prefers-reduced-motion:reduce){
            .content-wrapper,.main-footer,.main-header{transition:none;}
        }
        @media (max-width:991.98px){
            .content-wrapper,.content-wrapper:before,.main-footer,.main-footer:before,.main-header,.main-header:before{margin-left:0;}
        }
        .content-wrapper{background:#f4f6f9;}
        .main-sidebar{position:fixed;top:0;left:0;bottom:0;}
        .main-sidebar,.main-sidebar:before{transition:margin-left .3s ease-in-out,width .3s ease-in-out;width:250px;}
        @media screen and (prefers-reduced-motion:reduce){
            .main-sidebar,.main-sidebar:before{transition:none;}
        }
        @media (max-width:991.98px){
            .main-sidebar,.main-sidebar:before{box-shadow:none!important;margin-left:-250px;}
        }
        .main-footer{padding:15px;color:#555;border-top:1px solid #dee2e6;background:#fff;}
        .main-header{z-index:1000;}
        .main-header .navbar-nav .nav-item{margin:0;}
        .main-header .nav-link{position:relative;height:2.5rem;}
        .brand-link{padding:.8125rem .5rem;font-size:1.25rem;display:block;line-height:1.5;white-space:nowrap;}
        .brand-link:hover{color:#fff;text-decoration:none;}
        [class*=sidebar-dark] .brand-link{color:rgba(255,255,255,.8);border-bottom:1px solid #4b545c;}
        .main-sidebar{z-index:1100;height:100vh;overflow-y:hidden;}
        .sidebar{padding-bottom:0;padding-top:0;padding-left:.5rem;padding-right:.5rem;overflow-y:auto;height:calc(100% - 4rem);}
        .user-panel{position:relative;}
        [class*=sidebar-dark] .user-panel{border-bottom:1px solid #4f5962;}
        .user-panel,.user-panel .info{overflow:hidden;white-space:nowrap;}
        .user-panel .image{padding-left:.8rem;display:inline-block;}
        .user-panel img{width:2.1rem;height:auto;}
        .user-panel .info{display:inline-block;padding:5px 5px 5px 10px;}
        .nav-sidebar .nav-item>.nav-link{margin-bottom:.2rem;}
        .nav-sidebar .nav-item>.nav-link .right{transition:transform ease-in-out .3s;}
        @media screen and (prefers-reduced-motion:reduce){
            .nav-sidebar .nav-item>.nav-link .right{transition:none;}
        }
        .nav-sidebar .nav-link>p>.right{position:absolute;right:1rem;top:12px;}
        .nav-sidebar>.nav-item{margin-bottom:0;}
        .nav-sidebar>.nav-item .nav-icon{text-align:center;width:1.6rem;font-size:1.2rem;margin-right:.2rem;}
        .nav-sidebar .nav-treeview{display:none;list-style:none;padding:0;}
        .nav-sidebar .nav-treeview>.nav-item>.nav-link>.nav-icon{width:1.6rem;}
        .nav-sidebar .nav-link p{display:inline-block;margin:0;}
        .sidebar-dark-primary{background-color:#343a40;}
        .sidebar-dark-primary .user-panel a:hover{color:#fff;}
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:active,.sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:focus{color:#c2c7d0;}
        .sidebar-dark-primary .nav-sidebar>.nav-item:hover>.nav-link{color:#fff;background-color:rgba(255,255,255,.1);}
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-treeview{background:0 0;}
        .sidebar-dark-primary .sidebar a{color:#c2c7d0;}
        .sidebar-dark-primary .sidebar a:hover{text-decoration:none;}
        .sidebar-dark-primary .nav-treeview>.nav-item>.nav-link{color:#c2c7d0;}
        .sidebar-dark-primary .nav-treeview>.nav-item>.nav-link:hover{color:#fff;background-color:rgba(255,255,255,.1);}
        @media (min-width:992px){
            .sidebar-mini .nav-sidebar,.sidebar-mini .nav-sidebar .nav-link{white-space:nowrap;overflow:hidden;}
        }
        .nav-sidebar{position:relative;}
        .nav-sidebar:hover{overflow:visible;}
        .nav-sidebar .nav-item>.nav-link{position:relative;}
        .sidebar .nav-link p,.sidebar .user-panel .info{transition:margin-left .3s linear,opacity .5s ease;}
        @media screen and (prefers-reduced-motion:reduce){
            .sidebar .nav-link p,.sidebar .user-panel .info{transition:none;}
        }
        label:not(.form-check-label):not(.custom-file-label){font-weight:700;}
        .card{box-shadow:0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);}
        .card-body::after,.card-header::after{display:block;clear:both;content:"";}
        .card-header{position:relative;background-color:transparent;border-bottom:1px solid rgba(0,0,0,.125);border-top-left-radius:.25rem;border-top-right-radius:.25rem;}
        .card-title{font-size:1.25rem;font-weight:400;margin:0;}
        .btn-default{background-color:#f4f4f4;color:#444;border-color:#ddd;}
        .btn-default:active,.btn-default:hover{background-color:#e7e7e7;}
        .bg-info{color:#fff!important;}
        .bg-white{background-color:#fff;color:#1f2d3d!important;}
        .img-circle{border-radius:50%;}
        @media print{
            .main-header,.main-sidebar{display:none!important;}
            .content-wrapper,.main-footer{margin-left:0!important;min-height:0!important;-webkit-transform:translate(0,0);-ms-transform:translate(0,0);transform:translate(0,0);}
        }
        .text-sm{font-size:.875rem;}
        .elevation-2{box-shadow:0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.23);}
        .elevation-4{box-shadow:0 14px 28px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.22);}
        /*! CSS Used from: http://127.0.0.1:8000/css/custom.css */
        .content-wrapper a{color:#357ca5!important;}
        a.btn{color:#FFF!important;}
        h3{display:inline-block;width:fit-content;}
        .container-title{padding-top:10px;}
        .content-wrapper{background:#FFFFFF!important;}
        .nav{padding-left:0;margin-bottom:0;list-style:none;}
        .nav > li{position:relative;display:block;}
        .nav > li > a{position:relative;display:block;padding:5px 15px;}
        .nav > li > a:hover,.nav > li > a:focus{text-decoration:none;background-color:#fff;}
        .blackboard-avatar{-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;}
        .blackboard-avatar-navbar-img{height:32px;width:32px;}
        .nav-sidebar>.nav-item .nav-icon{font-size:1rem!important;}
        .nav-sidebar .nav-item>.nav-link{font-size:0.95rem;}
        .sidebar .header{color:#c2c7d0!important;border-bottom:1px solid #4f5962;border-top:1px solid #4f5962;padding:5px 10px;font-size:14px;}
        .main-footer{display:none;}
        .blackboard-avatar{text-align:center;border-radius:50%;}
        .nav-sidebar>.nav-item .nav-treeview .nav-icon{font-size:0.5rem!important;}
        .blackboard-scrollbar::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 6px rgba(0, 0, 0, 0.3);background-color:#F5F5F5;overflow:hidden;}
        .blackboard-scrollbar::-webkit-scrollbar{width:0px;background-color:#F5F5F5;overflow:hidden;.:: -webkit-scrollbar-thumb;blackboard-scrollbar     background-color:#555;border:2px solid #555555;}
        .list-group-item{border-radius:0.25rem;margin:7px 0px;background:#f5f5f5;padding:.75rem .75rem;}
        a.btn:focus,a.btn:active,a.btn:hover{outline:none!important;border:none!important;}
        .btn{outline-style:none!important;}
        @media (max-width: 991px){
            .nav > li > a{position:relative;display:block;padding:5px 8px;}
        }
        a.dropdown-item{border-bottom:1px solid #efefef;}
        a.dropdown-item:last-child{border-bottom:0px;}
        .user a.dropdown-item{border-bottom:0px;}
        .media-body p{margin-bottom:0px;white-space:normal;}
        .main-header{z-index:1101;}
        .main-sidebar{z-index:1100;}
        .badge{vertical-align:top;}
        .modal{z-index:1102!important;}
        .card-header{background-color:#343a40;color:#FFFFFF;}
        .main-sidebar{z-index:1102!important;}
        .nav-sidebar .nav-item .nav .nav-item .nav .nav-link{padding-left:25px;}
        /*! CSS Used from: http://127.0.0.1:8000/css/absa.css */
        body.sidebar-mini{background:#ffffff;}
        a{color:#000000;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects;}
        a:hover{color:#000000;text-decoration:none;}
        .nav-link{color:#000000!important;display:inline-block;margin:0;}
        .sidebar .nav-link:after{display:block;content:'';border-bottom:solid 2px #DD0033;transform:scaleX(0);transition:transform 250ms ease-in-out;}
        .nav-link:hover:after{transform:scaleX(1);}
        .btn-dark{background:#DD0033;border-color:#DD0033;}
        .sidebar .header{color:#2E3135!important;border-bottom:1px solid #2E3135;border-top:1px solid #2E3135;}
        [class*=sidebar-dark] .brand-link{border-bottom:none;}
        [class*=sidebar-dark] .user-panel{padding-top:1rem!important;border-top:1px solid #000000;border-bottom:1px solid #000000;}
        .btn-dark:hover,.btn-dark:active{background:#b7002a;border-color:#b7002a;}
        .sidebar-dark-primary{background:#FFFFFF;}
        .main-sidebar .logo-lg{display:block;text-align:center;-webkit-transition:width .3s ease-in-out;-o-transition:width .3s ease-in-out;transition:width .3s ease-in-out;}
        .badge{vertical-align:top;}
        .modal{z-index:1102!important;}
        .card-header{background-color:#DD0033;color:#FFFFFF;}
        .main-sidebar{z-index:1102!important;}
        .nav-sidebar .nav-item .nav .nav-item .nav .nav-link{padding-left:25px;}
        .modal-header{color:#FFFFFF!important;background:#DD0033;}
        #overlay{background:#ffffff;color:#666666;position:fixed;height:100%;width:100%;z-index:5000;top:0;left:0;float:left;text-align:center;padding-top:25%;opacity:.80;}
        .spinner{margin:0 auto;height:64px;width:64px;animation:rotate 0.8s infinite linear;border:5px solid firebrick;border-right-color:transparent;border-radius:50%;}
        .nav > li.admin-menu{display:none;}
        /*! CSS Used from: Embedded */
        .wrapper,body,html{min-height:100%;overflow-x:unset;}
        /*! CSS Used keyframes */
        @keyframes rotate{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
        /*! CSS Used fontfaces */
        @font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:400;font-display:auto;src:url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.eot");src:url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.eot#iefix") format("embedded-opentype"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.woff2") format("woff2"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.woff") format("woff"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.ttf") format("truetype"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-regular-400.svg#fontawesome") format("svg");}
        @font-face{font-family:'Font Awesome 5 Free';font-style:normal;font-weight:900;font-display:auto;src:url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.eot");src:url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.eot#iefix") format("embedded-opentype"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.woff2") format("woff2"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.woff") format("woff"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.ttf") format("truetype"), url("http://127.0.0.1:8000/fontawesome/webfonts/fa-solid-900.svg#fontawesome") format("svg");}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7qsDJB9cme_xc.woff2) format('woff2');unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7jsDJB9cme_xc.woff2) format('woff2');unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7rsDJB9cme_xc.woff2) format('woff2');unicode-range:U+1F00-1FFF;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7ksDJB9cme_xc.woff2) format('woff2');unicode-range:U+0370-03FF;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7osDJB9cme_xc.woff2) format('woff2');unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7psDJB9cme_xc.woff2) format('woff2');unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;}
        @font-face{font-family:'Source Sans Pro';font-style:italic;font-weight:400;src:local('Source Sans Pro Italic'), local('SourceSansPro-Italic'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK1dSBYKcSV-LCoeQqfX1RYOo3qPZ7nsDJB9cme.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmhdu3cOWxy40.woff2) format('woff2');unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwkxdu3cOWxy40.woff2) format('woff2');unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmxdu3cOWxy40.woff2) format('woff2');unicode-range:U+1F00-1FFF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwlBdu3cOWxy40.woff2) format('woff2');unicode-range:U+0370-03FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmBdu3cOWxy40.woff2) format('woff2');unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwmRdu3cOWxy40.woff2) format('woff2');unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:300;src:local('Source Sans Pro Light'), local('SourceSansPro-Light'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ik4zwlxdu3cOWxw.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNa7lujVj9_mf.woff2) format('woff2');unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qPK7lujVj9_mf.woff2) format('woff2');unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNK7lujVj9_mf.woff2) format('woff2');unicode-range:U+1F00-1FFF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qO67lujVj9_mf.woff2) format('woff2');unicode-range:U+0370-03FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qN67lujVj9_mf.woff2) format('woff2');unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qNq7lujVj9_mf.woff2) format('woff2');unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:400;src:local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xK3dSBYKcSV-LCoeQqfX1RYOo3qOK7lujVj9w.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmhdu3cOWxy40.woff2) format('woff2');unicode-range:U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwkxdu3cOWxy40.woff2) format('woff2');unicode-range:U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmxdu3cOWxy40.woff2) format('woff2');unicode-range:U+1F00-1FFF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwlBdu3cOWxy40.woff2) format('woff2');unicode-range:U+0370-03FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmBdu3cOWxy40.woff2) format('woff2');unicode-range:U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+1EA0-1EF9, U+20AB;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwmRdu3cOWxy40.woff2) format('woff2');unicode-range:U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;}
        @font-face{font-family:'Source Sans Pro';font-style:normal;font-weight:700;src:local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v13/6xKydSBYKcSV-LCoeQqfX1RYOo3ig4vwlxdu3cOWxw.woff2) format('woff2');unicode-range:U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;}
    </style>
    <title>Analyst: {{$user->first_name.' '.$user->last_name}}</title>
</head>
<body>
    <div class="container-fluid container-title">
        <h3> Analyst QA Report </h3>
        <hr>
    </div>
    <div class="container-fluid">
            <div class="row bg-info">
                <div class="col-sm-3"><strong>Analyst Name: {{$user->first_name.' '.$user->last_name}}</strong></div>
                <div class="col-sm-3"><strong>Period Selected: {{ $_GET['date_from']??null }} - {{ $_GET['date_to']??null }}</strong></div>
                <div class="col-sm-2"><strong>Total Passed For Period: {{$total_passed}}</strong></div>
                <div class="col-sm-2"><strong>Total Failed For Period: {{$total_failed}}</strong></div>
                <div class="col-sm-2"><strong>Total Not Reviewed For Period: {{$total_not_reviewed}}</strong></div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Special title treatment</h5>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-6 row">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Strapline</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$strapline["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$strapline["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$strapline["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Correct ME listed</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$correct_me_listed["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$correct_me_listed["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$correct_me_listed["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Family Tree</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$family_tree["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$family_tree["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$family_tree["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Clients With Exposure And No Exposure Identified Correctly</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$client_exposure_not_identified_correctly["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$client_exposure_not_identified_correctly["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 row ml-1">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Footer updated
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$footer_updated["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$footer_updated["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$footer_updated["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Page Numbers updated
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$page_numbers_updated["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$page_numbers_updated["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$page_numbers_updated["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">All RP included </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_rp_included["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_rp_included["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$all_rp_included["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Overview information</h5>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-6 row">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Client information</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$client_information["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$client_information["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$client_information["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">KYC date</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$kyc_date["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$kyc_date["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$kyc_date["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">PEP</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$pep["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$pep["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$pep["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">STR</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$str["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$str["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$str["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Adverse media</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$adverse_media["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$adverse_media["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$adverse_media["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 row ml-1">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Relationship
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$relationship["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$relationship["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$relationship["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Casa
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$casa["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$casa["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$casa["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Sanctions </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$sanctions["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$sanctions["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$sanctions["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Litigation </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$litigation["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$litigation["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$litigation["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">In line with V5 of Standard </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$v5_standard["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$v5_standard["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$v5_standard["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Product Exposure</h5>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-6 row">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">All products inclued</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_products_included["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_products_included["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$all_products_included["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Wimi & WFS account listed</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$wimi_wfs_account_listed["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$wimi_wfs_account_listed["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 row ml-1">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Linked accounts included
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$linked_accounts_included["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$linked_accounts_included["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$linked_accounts_included["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Email sent to Carissa for CIB, WIMI & WFS Clients
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$email_sent_cib_wimi_wfs_clients["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$email_sent_cib_wimi_wfs_clients["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">STR And TA</h5>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-6 row">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">All info included</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_info_included["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$all_info_included["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$all_info_included["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Expected account activity</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$expected_account_activity["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$expected_account_activity["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$expected_account_activity["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 row ml-1">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Review date correct
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$review_date_correct["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$review_date_correct["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$review_date_correct["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            TA has a conclusion
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$ta_has_conclusion["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$ta_has_conclusion["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$ta_has_conclusion["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Adverse Media</h5>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-6 row">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">Listed in chronological order</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$listed_in_chronological_order["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$listed_in_chronological_order["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$listed_in_chronological_order["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item"  style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">RB-Summary of article</div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$rb_summary_article["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$rb_summary_article["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$rb_summary_article["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6 row ml-1">
                            <ul class="list-group list-group-flush" style="width: 100%">
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            EB-exact extract from article
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$eb_exact_extract_from_article["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$eb_exact_extract_from_article["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" style="margin: 0 0!important;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            Does it align with the background and TA
                                        </div>
                                        <div class="col-md-8 row">
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Passed:</strong></div>
                                                <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["passed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-8"><strong>Failed:</strong></div>
                                                <div class="col-md-4"><strong>{{$does_it_align_with_background_ta["failed"]}}</strong></div>
                                            </div>
                                            <div class="col row">
                                                <div class="col-md-10"><strong>Not Reviewed:</strong></div>
                                                <div class="col-md-2"><strong>{{$does_it_align_with_background_ta["not_reviewed"]}}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
    </div>

</body>
</html>

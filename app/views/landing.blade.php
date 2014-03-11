<!DOCTYPE html>
<html>
<head>

    <title>The University of Akron Emailer</title>

    <link async rel="StyleSheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" type="text/css" />
    <script src="{{URL::to('/js/zeroclipboard/ZeroClipboard.min.js')}}"></script>
    <script type="text/javascript">
        document.write("    \<script src='//code.jquery.com/jquery-latest.min.js' type='text/javascript'>\<\/script>");
    </script>

    <style>

        body {
            background-color:#00285e;
            color:#fff;
        }


        .submit {
            color:#000;
            padding:.5em;
        }

        .panel {
            background-color:#4071B3;
            box-shadow:0 0 20px rgba(0,0,0,0.3);
            font-weight:700;
        }

        input {
            color:#222;
            box-shadow:0 0 30px rgba(0,0,0,0.3);
            border:0px;
            padding:1px;
            font-weight:700;
        }

        button {
            color:#222;
            box-shadow:0 0 30px rgba(0,0,0,0.6);
            border:0px;
            padding:1px;
        }

        .error {
            color:red;
            font-weight:bold;
        }

        #logo {
            margin-bottom:1.5em;
        }

        .centerMe {
            margin:0em auto;
            text-align:center;
        }

        h1 {
            text-shadow: 0 0 15px rgba(0,0,0,1);
        }

        .digestLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #B2E6FF;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .zipmailLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #00285E;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .waynemailLogo {
            margin-bottom:1.5em;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            padding:5px;

            border-color: #910F0F;
            border-width: 5px;
            border-style: solid;
            width:210px;
            overflow:hidden;
        }

        .logo:hover {
            box-shadow: 0 0 30px rgba(255,255,255,.6);
        }

    </style>

</head>
<body>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <br/>
            <br/>
            <h1 class="centerMe"><strong>The University of Akron Emailer</strong></h1>
            <br/>
            <br/>
            <div class="panel panel-default">
                <div class="panel-heading">Please Choose an Email Service</div>
                <div class="panel-body">
                    <br/>
                    <div class="col-xs-4 centerMe">
                        <a href="{{URL::to('/edit/digest')}}">
                            <div class="digestLogo logo">
                                <img class="" src="{{URL::to('img/ua_digest.png')}}"/>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-4 centerMe">
                        <a href="{{URL::to('/edit/zipmail')}}">
                            <div class="zipmailLogo logo">
                                <img class="" src="{{URL::to('img/ua_zipmail.png')}}"/>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-4 centerMe">
                        <a href="{{URL::to('/edit/waynemail')}}">
                            <div class="waynemailLogo logo">
                                <img class="" src="{{URL::to('img/ua_waynemail.png')}}"/>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
</script>
</body>
</html>
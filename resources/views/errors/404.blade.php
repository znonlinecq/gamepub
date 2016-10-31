<!DOCTYPE html>
<html>
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>404页面没有找到</title>


        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
<script type="text/javascript">     
    function countDown(secs){     
    var host = 'http://' + window.location.host;

    var jumpTo = document.getElementById('jumpTo');
    var urlText = document.getElementById('urlText');
    jumpTo.innerHTML=secs;  
    urlText.innerHTML=host;  
    if(--secs>0){     
        setTimeout("countDown("+secs+")",1000);     
    }     
    else{       
        location.href=host;     
    }     
 }     
</script> 
    </head>
    <body>
        <div class="container">
        <h1>页面没有找到</h1>
        <span id="jumpTo">5</span>秒后自动跳转到: <span id="urlText">http://admin.game.87870.com </span>
        </div>
        <script type="text/javascript">countDown(5);</script>  
    </body>
</html>

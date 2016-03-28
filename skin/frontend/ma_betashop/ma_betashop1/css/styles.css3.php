<?php
    header('Content-type: text/css; charset: UTF-8');
    header('Cache-Control: must-revalidate');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
    $url = $_REQUEST['url'];
?>

@-webkit-keyframes moveFromTop {
    from {
        -webkit-transform: translateY(-100%);
    }
    to {
        -webkit-transform: translateY(0%);
    }
}
@-moz-keyframes moveFromTop {
    from {
        -moz-transform: translateY(-100%);
    }
    to {
        -moz-transform: translateY(0%);
    }
}
@-ms-keyframes moveFromTop {
    from {
        -ms-transform: translateY(-100%);
    }
    to {
        -ms-transform: translateY(0%);
    }
}

@-webkit-keyframes smallToBig{
    from {
        -webkit-transform: scale(0.1);
    }
    to {
        -webkit-transform: scale(1);
    }
}
@-moz-keyframes smallToBig{
    from {
        -moz-transform: scale(0.1);
    }
    to {
        -moz-transform: scale(1);
    }
}
@-ms-keyframes smallToBig{
    from {
        -ms-transform: scale(0.1);
    }
    to {
        -ms-transform: scale(1);
    }
}

@keyframes rotate{
    0% { transform: scale(1) rotate(0);}
    50% { transform: scale(0.5) rotate(180deg);}
    100% { transform: scale(1) rotate(360deg);}
}
@-webkit-keyframes rotate{
    0% { -webkit-transform: scale(1) rotate(0);}
    50% { -webkit-transform: scale(0.5) rotate(180deg);}
    100% { -webkit-transform: scale(1) rotate(360deg);}
}
@-moz-keyframes rotate{
    0% { -moz-transform: scale(1) rotate(0);}
    50% { -moz-transform: scale(0.5) rotate(180deg);}
    100% { -moz-transform: scale(1) rotate(360deg);}
}
menu-recent .bx-wrapper img:hover{
    -ms-filter: "progid: DXImageTransform.Microsoft.Alpha(Opacity=80)";
    filter: alpha(opacity=80);
    opacity: 0.8;
}

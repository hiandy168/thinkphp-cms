

<!DOCTYPE html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo isset($navTabs['title'])?$navTabs['title']:'后台操作系统'?></title>
    <script type="text/javascript">
        //全局变量
        var GV = {
            ROOT: "/appadmin/",
            WEB_ROOT: "__STATIC__",
            JS_ROOT: "/default/",
            APP:'Portal'/*当前应用名*/
        };
    </script>
    <link href="__STATIC__/admin/css/bootstrap.min.css" rel="stylesheet">
    <link href="__STATIC__/admin/css/site.css" rel="stylesheet">
    <link href="__STATIC__/default/artDialog/skins/default.css" rel="stylesheet" />
    <script src="__STATIC__/admin/js/jquery.min.js"></script>
    <script src="__STATIC__/default/headjs.js"></script>

</head>


<body style="min-width:790px;" >
<style>
    .alert{
        position: fixed !important;z-index: 1000;width: 98%;top: 2%;
    }
</style>
<div class="container-fluid">
    <div id="alert"></div>
    [__REPLACE__]
</div>
<script src="__STATIC__/admin/js/common.js"></script>
<script src="__STATIC__/admin/js/cmsinfo.js"></script>
<script src="__STATIC__/admin/js/bootstrap.min.js"></script>
</body>
</html>





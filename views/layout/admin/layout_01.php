<!DOCTYPE html PUBLIC  "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Admin zone</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <base href="<?= BASE_HREF?>"/>
    <link href="css/style.css" type="text/css" rel="stylesheet"/>
    <?=$js_list;?>
</head>

<body>

<div class="header_bg">
</div>

<div class="header">
    <a href="/">Client zone</a> / Admin zone
</div>

<div class="sidebar">
    <?=$sections['left'];?>
</div>

<div class="content">
    <?=$sections['center'];?>
</div>

<div class="footer">
    &copy; Loginov Leonid (2011)
</div>


</body>
</html>
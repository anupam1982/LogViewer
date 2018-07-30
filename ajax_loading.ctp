<style>
#<?php echo $divid; ?>{
    margin:0px 0px 0px 0px;
    position:fixed;
    height: 100%;
    z-index:9999;
    padding-top:200px;
    width:100%;
    clear:none;
    background-color:#ffffff;
    margin-left:-20px;
display:none;
}
/*IE will need an 'adjustment'*/
* html #<?php echo $divid; ?>{
    position: absolute;
    height: expression(document.body.scrollHeight &gt; document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px');
}
</style>
<div id="<?php echo $divid; ?>"><img src="../img/ajax-loader.gif" style="display:block; margin-left:auto; margin-right:auto;"/></div>

<!-- Baidu Button BEGIN -->
<!-- <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare">
    <a class="bds_tsina"></a>
    <a class="bds_tqq"></a>
    <a class="bds_mail"></a>
<span class="bds_more">更多</span>
<a class="shareCount"></a>
</div>
<script type="text/javascript" id="bdshare_js" data="type=tools" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script> -->
<!-- Baidu Button END -->

<div class="bdsharebuttonbox">
    <a href="#" class="bds_tsina"
       data-cmd="tsina"
       title="分享到新浪微博"></a>
    <a href="#"
       class="bds_tqq"
       data-cmd="tqq"
       title="分享到腾讯微博"></a>
    <a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
    <a href="#" class="bds_mail" data-cmd="mail"
       title="分享到邮件分享"></a>
    <a href="#" class="bds_more" data-cmd="more"></a>
</div>
<?php $share_text = get_share_content(); ?>
<script>
    window._bd_share_config
        = {"common": {"bdSnsKey": {}, "bdText": "<?php echo $share_text; ?>", "bdMini": "2", "bdMiniList": false,
        "bdPic": "", "bdStyle": "1", "bdSize": "16"}, "share": {}};
    with (document)0[
        (getElementsByTagName('head')[0] || body).appendChild(createElement('script'))
            .src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
</script>
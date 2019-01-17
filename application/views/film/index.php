<script type="text/javascript" src="/public/js/ckplayer.js"></script>
<div id="video" style="width:600px;height:400px;"></div>
<script type="text/javascript">
    var config = {
        container: '#video',//“#”代表容器的ID，“.”或“”代表容器的class
        variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
        flashplayer:false,//如果强制使用flashplayer则设置成true
        video:'/public/video/hunter001.mp4'//视频地址
    };
    var player=new ckplayer(config);
</script>

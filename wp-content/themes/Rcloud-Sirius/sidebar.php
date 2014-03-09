<div id="sidebar">
<div id="sidebarFX">
	<?php
		if(is_single() || is_page()){
            echo '<div class="widget"><div class="widget-con"><h3 class="widget-title">讲座信息</h3>';
            echo '<ul><li style="color:black;">时间：'.get_start_time_show_string().' - '.get_end_time_show_string().'</li>';
            echo '<li style="color:black;">地址：'.get_post_meta(get_the_ID(), 'address', true).'</li>';
            echo '</ul></div></div>';
            echo '<div style="text-align:center;"><div id="baoming"> <a title="报名参加：'.the_title('','',false).'" href="'.get_post_meta(get_the_ID(), 'url', true).'" id="baoming" target="_blank">立即报名</a></div></div>';
            dynamic_sidebar('内页侧边');
		}else{
			dynamic_sidebar('侧边');
		}
	?>
</div></div>
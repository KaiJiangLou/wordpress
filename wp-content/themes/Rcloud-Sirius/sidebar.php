<div id="sidebar">
<div id="sidebarFX">
	<?php
		if(is_single() || is_page()){
            echo '<h2>讲座信息</h2>';
            echo '<ul><li>时间：'.get_start_time_show_string().' - '.get_end_time_show_string().'</li>';
            echo '<li>地址：'.get_post_meta(get_the_ID(), 'address', true).'</li>';
            echo '</ul>';
            echo '<a title="报名参加：'.the_title('','',false).'" href="'.get_post_meta(get_the_ID(), 'url', true).'">立即报名</a>';
            dynamic_sidebar('内页侧边');
		}else{
			dynamic_sidebar('侧边');
		}
	?>
</div></div>
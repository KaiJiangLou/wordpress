<?php
	$postnun = 1;	
	if(have_posts()):while(have_posts()):the_post();
?>
<?php
	//广告
	if($postnun == 2 && dopt('Rcloud_list_ad_c')){
		echo '<div class="post-list ad"><center>'.dopt('Rcloud_list_ad').'</center></div>';
	}
?>
<?php if(has_post_format('quote')): // 引语 ?>
<div class="post-list">
	<h2 class="post-list-title">
		<a class="fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		<div class="cc"></div>
	</h2>
	<div class="post-list-text"><?php the_excerpt(); ?></div>
	<ul class="post-list-info">
		<li><a href="<?php the_permalink(); ?>">阅读全文</a> | </li>
		<li>分类：<?php the_category(' '); ?> | </li>
		<li><span class="fr">发表于：<?php the_time('m月d日'); ?> | </span></li>
		<li>浏览：<?php the_view(); ?> | </li>
		<li><span class="pl_num"><?php comments_popup_link('0', '1 ', '% ', '', '评论已关闭'); ?></span>&nbsp;条评论</li>
	</ul>
</div>

<?php elseif(has_post_format('status')): //状态 ?>
<div class="post-list status">
	<div class="avatar"><a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>" title="<?php echo get_the_author_meta( 'display_name' ); ?>"><?php echo get_avatar(get_the_author_meta('user_email'),'40'); ?></a></div>
	<ul class="post-list-info">
		<li>评论：<?php comments_popup_link('0条', '1 条', '% 条', '', '评论已关闭'); ?></li>
	</ul>
	<div class="post-list-text"><?php the_content(); ?></div>
</div>

<?php elseif(has_post_format('audio')): //音乐 ?>
<div class="post-list audio">
	<h2 class="post-list-title">
		<a class="fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		<span class="fr"><?php the_time('m月d日'); ?></span>
		<div class="cc"></div>
	</h2>
	<div class="post-list-audio">
		<?php
			$music_url = get_post_meta($post->ID,'play_url',true);
			$music_box = auto_player_urls($music_url);
			echo $music_box;
		?>
	</div>
	<div class="post-list-text"><?php the_excerpt(); ?></div>
</div>

<?php elseif(has_post_format('video')): //视频 ?>
<div class="post-list video">
	<div class="post-list-video">
		<?php
			$play_url = get_post_meta($post->ID,'play_url',true);
			echo '<embed class="swf_player" src="'.$play_url.'" width="648" height="400" type="application/x-shockwave-flash" wmode="transparent"></embed>';
		?>
	</div>
	<h2 class="post-list-title">
		<a class="fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		<span class="fr"><?php the_time('m月d日'); ?></span>
		<div class="cc"></div>
	</h2>
	<div class="post-list-text"><?php the_excerpt(); ?></div>
</div>

<?php else: //默认 ?>
<div class="post-list">
	<h2 class="post-list-title">
		<a class="fl" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		<?php if(get_the_img()){ ?>
		        &nbsp;<img src="<?php bloginfo('template_url');?>/images/img.gif">
		<?php } ?>
		<div class="cc"></div>
	</h2>
	<!--判断，如果文章有图片则提取第一张显示在列表中-->
	<!--?php if(get_the_img()){
		echo '<div class="post-list-img"><a href="'.get_permalink().'" title="'.get_the_title().'">';
		the_img();
		echo '</a></div>';
	} ?-->

	<div class="post-list-text"><?php echo cut_str(strip_tags(apply_filters('the_content',$post->post_content)),200); ?></div>
    <ul class="post-list-meta">
        <li>开讲时间：
            <?php
                //echo get_post_meta(get_the_ID(), 'start_time', true).' - '.get_post_meta(get_the_ID(), 'end_time', true);
            echo get_start_time_show_string().' - '.get_end_time_show_string();
            ?> </li>
        <li>开讲地址：<?php echo get_post_meta(get_the_ID(), 'address', true); ?> </li>
    </ul>
    <ul class="post-list-info">
		<!-- <li><a href="<?php the_permalink(); ?>">阅读全文</a> | </li> -->
		<!-- <li><span class="fr">发表于：<?php the_time('Y-m-d'); ?> | </span></li> -->
		<li>浏览：<?php the_view(); ?> | </li>
        <li>分类：<?php the_category(' '); ?> | </li>
		<li><span class="pl_num">评论数：<?php comments_popup_link('0', '1 ', '% ', '', '评论已关闭'); ?></span></li>
	</ul>
</div>	
<?php endif; ?>	
<?php $postnun++; endwhile; else: ?>
	<h1 style="border:1px solid #ccc; border-radius: 3px; padding: 50px; font-size: 20px; color: #f00; text-align: center; background: #fff;">没有更多的讲座喽!</h1>
<?php endif; ?>
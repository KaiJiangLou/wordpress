<?php get_header(); ?>
<div id="content"><div class="wrap">
	<div id="single">
		<div class="part">
		<?php if(have_posts()):while(have_posts()):the_post(); ?>
			<h1 class="single-title"><?php the_title(); ?></h1>
			<!-- 文章信息 -->
			<div class="single-info">
				<!--<span>作者：<?php the_author() ?></span>&nbsp;|&nbsp;
                <span>时间：<?php echo get_start_time_show_string().' - '.get_end_time_show_string();?></span>&nbsp;|&nbsp; -->
				<span>浏览：<?php the_view(); ?></span>&nbsp;|&nbsp;
				<span>评论：<span class="pl_num"><?php comments_popup_link('0', '1 ', '% ', '', '评论已关闭'); ?></span></span>
				<?php if( current_user_can( 'manage_options' ) ) { ?>
				<a href="<?php echo get_edit_post_link(); ?>" target="_blank">编辑文章</a>
				<?php } ?>
			</div>
			<!-- 内容 -->
			<div id="single-con">
				<?php
					//广告
					if(dopt('Rcloud_signleTop_ad_c')){
						echo '<div class="Rcloud_signleTop_ad"><center>'.dopt('Rcloud_signleTop_ad').'</center></div>';
					}
				?>
				<?php include_once 'template/media.php'; ?>
				<?php the_content(); ?>
			</div>
			<div class="single-tags">标签：<?php the_tags('',' '); ?></div>
		<?php endwhile; endif; ?>
			<?php
				//广告
				if(dopt('Rcloud_signleBot_ad_c')){
					echo '<div class="Rcloud_signleBot_ad"><center>'.dopt('Rcloud_signleTop_ad').'</center></div>';
				}
			?>
			<div class="single-copyright">
				<div class="fl">本站遵循CC协议<a href="http://creativecommons.org/licenses/by-nc-sa/3.0/deed.zh" rel="external nofollow" target="_blank">署名-非商业性使用-相同方式共享 </a></div>
				<div class="fr">转载请注明来自：<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></div>
				<div class="cc"></div>
			</div>
		</div>
		<div class="part">
			<ul class="otherPost">
				<li><?php next_post_link('下一篇： %link','%title',false); ?></li>
				<li><?php previous_post_link('上一篇： %link','%title',false); ?></li>
			</ul>
		</div>
		<?php include_once 'template/relevance.php'; ?>
		<!-- 评论 -->
		<div class="part"><?php comments_template(); ?></div>
	</div>
	<?php get_sidebar(); ?>
	<div class="cc"></div>
</div></div>
<?php get_footer(); ?>
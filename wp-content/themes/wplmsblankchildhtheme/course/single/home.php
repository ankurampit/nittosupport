<?php
get_header();
require_once get_stylesheet_directory() . '/header-inner.php';
the_post();

$course_id = get_the_ID();
$user_id = get_current_user_id();

// WPLMS-specific data
$curriculum = bp_course_get_curriculum($course_id);
$progress = bp_course_get_user_progress($user_id, $course_id);
$course_categories = get_the_term_list($course_id, 'course-cat', '', ', ', '');
?>

<section class="custom-course-page">
	<div class="container-old course-container">
		<main>
			<div class="breadcrumbs">
				<?php if (function_exists('vibe_breadcrumbs')) vibe_breadcrumbs(); ?>
			</div>

			<div class="hero-image course-hero">
				<?php if (has_post_thumbnail()) {
					the_post_thumbnail('full');
				} ?>
			</div>

			<div class="course-header">
				<h1 class="course-title"><?php the_title(); ?></h1>

				<div class="course-meta">
					<div class="meta-item">
						<strong>Category</strong>
						<span><?php echo $course_categories; ?></span>
					</div>

					<div class="meta-item">
						<strong>Toyo Potential $</strong>
						<span><?php echo get_post_meta($course_id, 'toyo_dollars', true) ?: '0'; ?></span>
					</div>

					<div class="meta-item progress-wrapper">
						<strong>Progress</strong>
						<div class="progress-flex">
							<div class="progress-bar-container">
								<div class="progress-fill" style="width: <?php echo $progress; ?>%;"></div>
							</div>
							<span class="progress-percentage"><?php echo $progress; ?>%</span>
						</div>
					</div>

					<div class="meta-item btn-wrapper">
						<?php the_course_button(); ?>
					</div>
				</div>
			</div>

			<div class="course-details">
				<div class="course-description">
					<h3>Course Description</h3>
					<?php the_content(); ?>
				</div>
				<div class="course-info">
					<h3>Course information</h3>
					<p><strong>REVISED:</strong> <?php the_modified_date('Y-m-d'); ?></p>
				</div>
			</div>

			<div class="curriculum">
				<h3>COURSE CURRICULUM</h3>
				<?php
				if (!empty($curriculum)):
					foreach ($curriculum as $item):
						if (is_numeric($item)): // It's a Unit or Quiz 
				?>
							<div class="lesson">
								<div class="lesson-left">
									<?php
									$post_type = get_post_type($item);
									$icon = ($post_type == 'quiz') ? 'dashicons-edit' : 'dashicons-media-document';
									?>
									<span class="dashicons <?php echo $icon; ?>"></span>
									<span><?php echo get_the_title($item); ?></span>
								</div>
								<a href="<?php echo get_permalink($item); ?>" class="lesson-btn">
									<?php echo ($post_type == 'quiz') ? 'QUIZ' : 'LESSON'; ?>
								</a>
							</div>
						<?php else: // It's a Section Header 
						?>
							<div class="section">
								<h4><?php echo $item; ?></h4>
							</div>
				<?php endif;
					endforeach;
				endif; ?>
			</div>
		</main>

		<aside>
			<div class="sidebar-widget search-widget">
				<h3 style="color: white !important;">SEARCH</h3>

				<form role="search" method="get" action="<?php echo home_url('/'); ?>">
					<div class="search-box">

						<div class="search-input-wrapper">
							<input type="text" name="s" placeholder="Type Keywords.." value="<?php echo get_search_query(); ?>">
							<span class="search-icon"><span class="search-icon"><span class="dashicons dashicons-search"></span></span>
						</div>

						<input type="hidden" name="post_type" value="course" />

						<button type="submit">SEARCH</button>

					</div>
				</form>
			</div>

			<?php if (is_active_sidebar('course')) : ?>
				<?php dynamic_sidebar('course'); ?>
			<?php endif; ?>

			<div class="sidebar-widget course-categories">
				<h3 class="widget-title">Courses</h3>
				<ul>
					<?php
					$terms = get_terms(array('taxonomy' => 'course-cat', 'hide_empty' => true));
					foreach ($terms as $term): ?>
						<li>
							<a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>
							<span class="count"><?php echo $term->count; ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="sidebar-widget latest-news">
				<h3 class="widget-title">Latest Courses</h3>

				<ul class="latest-course-list">

					<?php
					$latest_courses = new WP_Query(array(
						'post_type' => 'course',
						'posts_per_page' => 4
					));

					if ($latest_courses->have_posts()):
						while ($latest_courses->have_posts()): $latest_courses->the_post(); ?>

							<li class="news-item">

								<a href="<?php the_permalink(); ?>" class="course-thumb">
									<?php
									if (has_post_thumbnail()) {
										the_post_thumbnail('thumbnail');
									} else {
										echo '<img src="' . get_template_directory_uri() . '/assets/images/default-course.png" alt="Course">';
									}
									?>
								</a>

								<div class="course-info">
									<a href="<?php the_permalink(); ?>" class="news-title"><?php the_title(); ?></a>
									<div class="news-date"><?php echo get_the_date('F j, Y'); ?></div>
								</div>

							</li>

					<?php endwhile;
						wp_reset_postdata();
					endif;
					?>

				</ul>
			</div>
		</aside>
	</div>
</section>

<?php get_footer();

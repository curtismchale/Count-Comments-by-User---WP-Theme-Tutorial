=== WP Theme Tutorial - Count Comments by User ===

Contributors: Curtis McHale
Tags: count comments, admin
Requires at least: 3.4
Tested up to: 3.4
Stable tag: 1.0

~Current Version:1.0~

Adds a function that can count posts made by registered users. Adds
custom admin column to users.php that shows the number of comments made
be each registered user.

== Description ==

Adds a function that can count posts made by registered users. Adds
custom admin column to users.php that shows the number of comments made
be each registered user.

== Installation ==

1. Extract to your wp-content/plugins/ folder.

2. Activate the plugin.

3. Add <?php echo theme_t_wp_count_user_comments( $userid ); ?> to your
theme where you want to display the number of comments made by a user

== Changelog ==

= 1.0 =

- initial working plugin commit

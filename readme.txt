=== Selective status list ===
Contributors: publicvoid
Tags: twitter, tweets, status, filtered
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 0.4

Widget shows list of tweets from specified user which are marked by a specified keyword.

== Description ==

The plugin provides a widget, which shows a list of tweets from the specified author, filtered by a specified hashtag. User name and hashtag are customizable. The plugin uses AJAX to fetch status notices, hence it does'nt work for users without javascript enabled at the moment, but keeps the page load small. 

== Installation ==

1. Upload the 'selectiv-tweet-list.php' and 'selective-tweet-list.js' to your '/wp-content/plugins/' directory inside a 'selective-tweets' subdirectory
1. Alternatively use your wordpress installations plugin install functions in the admin area
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Drag the widget to the sidebar and fill in your desired settings (microblogging service, user name, filter hashtag, )

== Frequently Asked Questions ==

= Why are no tweets showing up? =

Make sure your user screen name is right. Check whether you have tweets containing your specified keyword in your timeline. Verify that the number of tweets fetched is set high enought to include some of those. You can check your settings by typing http://twitter.com/statuses/user_timeline.xml?screen_name=<your_screen_name>&count=<number_of_tweets_fetched> in your browsers address bar and see whether there are matching tweets.

= What about identi.ca, caching and status digests? =

Coming soon.

== Changelog ==

= 0.1 =
Initial release

= 0.2 =
- improved input validation and escaping
- use twitter search api
- allow querying status.net (identi.ca) api - usertime line batch fetching

= 0.3 =
bug fixes: widget layout, js path

= 0.4 =
bug fix: absolute js url via plugin url


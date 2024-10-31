/*
Part of Selective tweet list plugin for wordpress
Check out this plugin: http://wordpress.org/extend/plugins/selective-tweets/
*/

var selectiveTweetList = {
	init : function(params) {
		for (var p in params) {
			this[p] = params[p];
		}
    this.pagesFetched = 0;
    this.tweetsGrabbed = 0;
    var searchQuery = "from:" + encodeURIComponent(this.twitterName) + "+" + encodeURIComponent(this.marker);
		this.baseUrl = this.statusNet ? 
      "http://identi.ca/api/statuses/user_timeline.json?callback=selectiveTweetList.batchGrabTweets&count=" + Math.min(this.count*5, 200) + "&screen_name=" + encodeURIComponent(this.twitterName) :
      "http://search.twitter.com/search.json?callback=selectiveTweetList.searchGrabTweets&q=" + searchQuery + "&rpp=" + this.count;
    this.requestJSON();
	},
  requestJSON : function() {
    this.pagesFetched++;
		var script = document.createElement("script");
    script.src = this.baseUrl + "&page=" + this.pagesFetched;
		document.body.appendChild(script);
  },
  searchGrabTweets : function(response) {
    if (response.error) {
			this.makeListEntry("tweetlist", this.errorText);
		} else {
    	for ( var tweet = 0; tweet<response.results.length; tweet++) {
				this.makeListEntry("tweetlist", response.results[tweet].text.replace(this.marker, ""));
      }
    }
  },
  batchGrabTweets : function(data) {
    if (data.error) {
			this.makeListEntry("tweetlist", this.errorText);
		} else {
      this.grabTweets(data);
      if(this.tweetsGrabbed<this.count && this.pagesFetched < 10) { this.requestJSON(); }
    }
  },
	grabTweets : function(data) {
  	for ( var tweet = 0; tweet<data.length && this.tweetsGrabbed<this.count; tweet++) {
	  	tweetstext = data[tweet].text;
		  var markerRegExp = new RegExp(this.marker);
			if (tweetstext.search(markerRegExp) >= 0) {
        this.tweetsGrabbed++;
				this.makeListEntry("tweetlist", tweetstext.replace(markerRegExp, ""));
			}
		}
	},
	makeListEntry : function(listId, txt) {
		var findUrl = /(http:\/\/\S*)/g;
		var foundUrl;
		var lastMatch = 0;
		var entry = document.createElement("li");
    var urlTxt = txt.replace(findUrl, '<a href="$1">$1</a>');
    entry.innerHTML = urlTxt;
		document.getElementById(this.listId).appendChild(entry);
	}
}

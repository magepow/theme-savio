(function ($) {
    $.fn.socialstream = function (options) {
        var defaults = {
            socialnetwork: 'instagram',
            username: 'aloteams',
            limit: 6,
            lazy: true,
            srcSize: 320,
            srcMedia: 'media_url',
            overlay: true,
            apikey: false,
            accessToken: '',
            picasaAlbumId: '',
            tags: '',
            afterload: function() {},
            callback: function() {}
        };
        var options = $.extend(defaults, options);
        var methods = {
            init : function(object) {
                switch (options.socialnetwork) {
                    case 'flickr':
                        methods.flickr(object);
                        break;
                    case 'pinterest':
                        methods.pinterest(object);
                        break;
                    case 'instagram':
                        methods.instagram(object);
                        break;
                    case 'dribbble':
                        methods.dribbble(object);
                        break;
                    case 'deviantart':
                        methods.deviantart(object);
                        break;
                    case 'picasa':
                        methods.picasa(object);
                        break;
                    case 'youtube':
                        methods.youtube(object);
                        break;
                    case 'newsfeed':
                        methods.newsfeed(object);
                        break;
                }
            },
            flickr: function(object){
                object.append("<ul class=\"flickr-list social-list\"></ul>")
                $.getJSON("https://api.flickr.com/services/rest/?method=flickr.people.findByUsername&username=" + options.username + "&format=json&api_key=32ff8e5ef78ef2f44e6a1be3dbcf0617&jsoncallback=?", function (data) {
                    var user_id = data.user.nsid;
                    $.getJSON("https://api.flickr.com/services/rest/?method=flickr.photos.search&user_id=" + user_id + "&format=json&api_key=85145f20ba1864d8ff559a3971a0a033&per_page=" + options.limit + "&page=1&extras=url_sq&jsoncallback=?", function (data) {
                        $.each(data.photos.photo, function (num, photo) {
                            var photo_author = photo.owner;
                            var photo_title = photo.title;
                            var photo_src = photo.url_sq;
                            var photo_id = photo.id;
                            var photo_url = "https://www.flickr.com/photos/" + photo_author + "/" + photo_id;
                            var photo_container = $('<img/>').attr({
                                src: photo_src,
                                alt: photo_title
                            });
                            var url_container = $('<a/>').attr({
                                href: photo_url,
                                target: '_blank',
                                title: photo_title
                            });

                            var tmp = $(url_container).append(photo_container);
                            if (options.overlay) {
                                var overlay_div = $('<div/>').addClass('img-overlay');
                                $(url_container).append(overlay_div);
                            }
                            var li = $('<li/>').append(tmp);
                            $("ul", object).append(li);
                        })

                        options.afterload.call(object);

                    });
                });                
            },
            pinterest: function(object){
                var url = 'http://pinterest.com/' + options.username + '/feed.rss'
                var api = "http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&callback=?&q=" + encodeURIComponent(url) + "&num=" + options.limit + "&output=json_xml";

                // Send request
                $.getJSON(api, function (data) {
                    if (data.responseStatus == 200) {
                        var photofeed = data.responseData.feed;
                        var overlay_div = "";
                        if (!photofeed) {
                            return false;
                        }
                        var html_code = '<ul class=\"pinterest-listt social-list\">';

                        for (var i = 0; i < photofeed.entries.length; i++) {
                            var entry = photofeed.entries[i];
                            var $container = $("<div></div>");
                            $container.append(entry.content);
                            var url = "http://www.pinterest.com" + $container.find('a').attr('href');
                            var photo_url = $container.find('img').attr('src');
                            var photo_title = $container.find('p:nth-child(2)').html();
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + photo_url + '"/>' + overlay_div + '</a></li>'
                        }
                        html_code += '</ul>';

                        $(object).append(html_code);

                        options.afterload.call(object);

                    }
                });
            },
            instagram: function(object){
                object.append("<ul class=\"instagram-list social-list\"></ul>");
                var imagesize = [150, 240, 320, 480, 640];
                var size      = parseInt(options.srcSize);
                var imgsize   = (imagesize.indexOf(size) != -1) ? imagesize.indexOf(size) : 1 ;

                // check if access token is set
                if ((typeof (options.accessToken) != "undefined") && options.accessToken != "") {
                    var url          = "https://graph.instagram.com/me/media";
                    var access_token = options.accessToken;
                    var limit        = options.limit;
                    $.getJSON( url, {'access_token': access_token, 'limit': limit, 'fields': 'id, caption, comments_count, like_count, media_type, media_url, thumbnail_url, permalink' }, function (data) {
                        $.each(data.data, function (i, shot) {
                            if (shot.media_type === 'VIDEO') return;
                            var photo_src = shot.media_url;
                            var photo_url = shot.permalink;
                            var photo_title = "";
                            if (shot.caption != null) {
                                photo_title = shot.caption;
                            }
                            if(options.lazy){
                                var photo_container = $('<img/>').attr({
                                    'data-src'  : photo_src,
                                    src         : 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==',
                                    class       : 'lazyload',
                                    alt         : photo_title,
                                    width       : imagesize[imgsize],
                                    height      : imagesize[imgsize]
                                });
                            }else {
                                var photo_container = $('<img/>').attr({
                                    src: photo_src,
                                    alt: photo_title
                                });
                            }
                            var url_container = $('<a/>').attr({
                                href: photo_url,
                                target: '_blank',
                                title: photo_title
                            });

                            var tmp = $(url_container).append(photo_container);
                            if (options.overlay) {
                                var overlay_div = $('<div/>').addClass('img-overlay');
                                $(url_container).append(overlay_div);
                            }
                            var li = $('<li/>').append(tmp);
                            var sub =  (shot.like_count !=  undefined )     ? '<span class="likes">' + shot.like_count + '</span>' : '';
                            sub     += (shot.comments_count != undefined)   ? '<span class="comments">' + shot.comments_count + '</span>' : '';
                            if(sub) li.append('<span class="sub">' + sub + '</span>');
                            $("ul", object).append(li);

                        });

                    }).done(function() {
                        if(options.lazy) object.trigger('contentUpdated');
                        options.afterload.call(object);  
                    }).fail(function() {
                        console.warn( "Request Instagram Access Failed: ");
                    });                            
                } else {
                    console.warn("Instagram Access Token is not set. Please enter it in plugin init call.");
                }
            },
            dribbble: function(object){
                object.append("<ul class=\"dribbble-list social-list\"></ul>")

                // check if access token is set
                if ((typeof (options.accessToken) != "undefined") && options.accessToken != "") {
                    var access_token = options.accessToken;
                } else {
                    console.warn("Dribbble Access Token is not set. Please enter it in plugin init call.");
                    return;
                }

                $.getJSON("https://api.dribbble.com/v1/users/" + options.username + "/shots?access_token=" + access_token + "&callback=?", function (data) {
                    $.each(data.data, function (num, shot) {
                        if (num < options.limit) {
                            var photo_title = shot.title;
                            var photo_container = $('<img/>').attr({
                                src: shot.images.teaser,
                                alt: photo_title
                            });
                            var url_container = $('<a/>').attr({
                                href: shot.html_url,
                                target: '_blank',
                                title: photo_title
                            });
                            var tmp = $(url_container).append(photo_container);
                            if (options.overlay) {
                                var overlay_div = $('<div/>').addClass('img-overlay');
                                $(url_container).append(overlay_div);
                            }
                            var li = $('<li/>').append(tmp);
                            $("ul", object).append(li);
                        }
                    });

                    options.afterload.call(object);

                });
            },
            deviantart: function(object){
                var url = 'http://backend.deviantart.com/rss.xml?type=deviation&q=by%3A' + options.username + '+sort%3Atime+meta%3Aall';
                var api = "http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&callback=?&q=" + encodeURIComponent(url) + "&num=" + options.limit + "&output=json_xml";

                $.getJSON(api, function (data) {
                    if (data.responseStatus == 200) {
                        var photofeed = data.responseData.feed;
                        var overlay_div = "";
                        if (!photofeed) {
                            return false;

                        }
                        var html_code = '<ul class=\"deviantart-list social-list\">';

                        for (var i = 0; i < photofeed.entries.length; i++) {
                            var entry = photofeed.entries[i];
                            var $container = $("<div></div>");
                            $container.append(entry.content);
                            var url = entry.link;
                            var photo_url = $container.find('img').attr('src');

                            // ignore smiley images
                            if (photo_url.indexOf("smile.gif") >= 0) {
                                continue;
                            }

                            var photo_title = entry.title.replace(/.jpg/g, "").replace(/-/g, " ").replace(/_/g, " ");
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + photo_url + '"/>' + overlay_div + '</a></li>'
                        }
                        html_code += '</ul>';

                        $(object).append(html_code);

                        options.afterload.call(object);

                    }
                });
            },
            picasa: function(object){
                var url = 'https://picasaweb.google.com/data/feed/base/user/' + options.username + '/album/' + options.picasaAlbumId + '?kind=photo&access=public&alt=json-in-script&imgmax=' + options.limit + '&callback=?';

                $.getJSON(url, function (data) {
                    if (data.feed.entry.length > 0) {

                        var photofeed = data.feed.entry;
                        var overlay_div = "";

                        var html_code = '<ul class=\"picasa-list social-list\">';

                        $.each(photofeed, function (i, pic) {
                            var thumb = pic.media$group.media$thumbnail[2].url;
                            var desc = pic.media$group.media$description.$t;
                            var title = pic.media$group.media$title.$t;

                            var url = pic.link[1].href;
                            var photo_title = title.replace(/.jpg/g, "").replace(/.JPG/g, "").replace(/-/g, " ").replace(/_/g, " ");
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + thumb + '"/>' + overlay_div + '</a></li>'
                        });

                        for (var i = 0; i < photofeed; i++) {
                            var entry = photofeed[i];
                            var $container = $("<div></div>");
                            $container.append(entry.content);
                            var url = entry.link;
                            var photo_url = $container.find('img').attr('src');
                            var photo_title = entry.title.replace(/.jpg/g, "").replace(/-/g, " ").replace(/_/g, " ");
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + photo_url + '"/>' + overlay_div + '</a></li>'
                        }
                        html_code += '</ul>';

                        $(object).append(html_code);

                        options.afterload.call(object);
                    }
                });
            },
            youtube: function(object){
                var pid;
                if (options.apikey) {

                    // Get Uploads Playlist
                    $.get(
                            "https://www.googleapis.com/youtube/v3/channels", {
                                part: 'contentDetails',
                                id: options.username,
                                key: options.apikey
                            },
                    function (data) {

                        $.each(data.items, function (i, item) {
                            //playlist id
                            pid = item.contentDetails.relatedPlaylists.uploads;
                            youtubeGetVids(pid);
                        });

                    }
                    );

                }

                //Get Videos
                function youtubeGetVids(pid) {
                    $.get(
                            "https://www.googleapis.com/youtube/v3/playlistItems", {
                                part: 'snippet',
                                maxResults: options.limit,
                                playlistId: pid,
                                key: options.apikey
                            },
                    function (data) {
                        var results;

                        var html_code = '<ul class=\"youtube-list social-list\">';

                        // loop through videos
                        $.each(data.items, function (i, item) {

                            var photofeed = item.snippet.thumbnails.default.url;
                            var overlay_div = "";
                            if (!photofeed) {
                                return false;
                            }

                            // create container
                            var $container = $("<div></div>");

                            // get image url
                            var url = 'https://www.youtube.com/watch?v=' + item.snippet.resourceId.videoId;

                            // video title
                            var photo_title = item.snippet.title;
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            // create html
                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + photofeed + '"/>' + overlay_div + '</a></li>'

                        });

                        html_code += '</ul>';

                        // append html
                        $(object).append(html_code);

                        options.afterload.call(object);
                    }
                    );
                }                
            },
            newsfeed: function(object){
                var api = "http://ajax.googleapis.com/ajax/services/feed/load?v=1.0&callback=?&q=" + encodeURIComponent(options.username) + "&num=" + options.limit + "&output=json_xml";

                $.getJSON(api, function (data) {
                    if (data.responseStatus == 200) {
                        var photofeed = data.responseData.feed;
                        var overlay_div = "";
                        if (!photofeed) {
                            return false;
                        }
                        var html_code = '<ul class=\"social-feed social-list\">';

                        for (var i = 0; i < photofeed.entries.length; i++) {
                            var entry = photofeed.entries[i];
                            var $container = $("<div></div>");
                            $container.append(entry.content);
                            var url = entry.link;
                            var photo_url = $container.find('img').attr('src');
                            var photo_title = entry.title.replace(/.jpg/g, "").replace(/-/g, " ").replace(/_/g, " ");
                            if (options.overlay) {
                                var overlay_div = '<div class="img-overlay"></div>';
                            }

                            html_code += '<li><a target="_blank" href="' + url + '" title="' + photo_title + '"><img src="' + photo_url + '"/>' + overlay_div + '</a></li>'
                        }
                        html_code += '</ul>';

                        $(object).append(html_code);

                        options.afterload.call(object);
                    }
                });                
            }
        };        
        return this.each(function () {
            var object = $(this);
            if ("IntersectionObserver" in window) {
                let socialObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            // let el = entry.target;
                            methods.init(object);
                            socialObserver.unobserve(entry.target);
                        }
                    });
                });

                object.each(function(index, el){
                    socialObserver.observe(el);
                });
            } else {
                methods.init(object);
            }

            options.callback.call(this);
        });
    };
})(jQuery);

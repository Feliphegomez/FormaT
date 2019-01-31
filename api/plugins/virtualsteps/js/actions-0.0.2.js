;
(function($, window, document, undefined) {
    var pluginName = "actions",
        defaults = {
            pointer: {
                start: {
                    top: 0,
                    left: 0
                }
            },
            overlay: {
                html: '<div class=\"wm-hl-tooltip\">{text}</div>'
            },
            zoomHelper: null,
            maxHeight: 0,
            maxWidth: 0
        },
        pointerTimer,
        frame = 0,
        activePointerImage,
        allowZoom = false,
        mainClass = 'wm-hl-main',
        oldClass = 'wm-hl-old',
        overlayClass = 'wm-hl-overlay',
        magnifierClass = 'wm-hl-magnifier',
        masterImagesClass = 'wm-hl-master-imagers',
        masterImageVClass = 'wm-hl-master-image-v',
        masterImageHClass = 'wm-hl-master-image-h',
        pointerClass = 'wm-hl-pointer',
        highlightsClass = 'wm-hl-highlights',
        highlightClass = 'wm-hl-highlight',
        keyHighlightsClass = 'wm-hl-keyhighlights',
        spinnerClass = 'wm-hl-spinner';


    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;

        this.templates = {};
        this.templates.oldContent = $('<div class="' + oldClass + '"></div>');
        this.templates.main = $('<div class="' + mainClass + '"></div>').css({
            position: 'relative',
            margin: '0 auto',
            display: 'none'
        });
        this.templates.keyHighlights = $('<div class="' + keyHighlightsClass + '"></div>').css({
            position: 'relative'
        });
        this.templates.masterImages = $('<div class="' + masterImagesClass + '"></div>');
        this.templates.masterImages.vertical = $('<div class="' + masterImageVClass + '"></div>').css({
            position: 'absolute'
        });
        this.templates.masterImages.horizontal = $('<div class="' + masterImageHClass + '"></div>').css({
            position: 'absolute'
        });
        this.templates.highlights = $('<div class="' + highlightsClass + '"></div>').css('position', 'absolute');
        this.templates.highlight = $('<div class="' + highlightClass + '"><img /></div>');
        this.templates.pointer = $('<div class="' + pointerClass + '"><img /></div>').css('position', 'absolute'); //pointerClass #CLASS POINTER
        this.templates.overlay = $('<div class="' + overlayClass + '"></div>').css('position', 'absolute');
        this.templates.magnifier = $('<div class="' + magnifierClass + '"></div>').css({
            'background-repeat': 'no-repeat',
            'position': 'absolute',
            'border-radius': '100%',
            'display': 'none',
            'box-shadow': '0 0 0 7px rgba(255, 255, 255, 0.85), 0 0 7px 7px rgba(0, 0, 0, 0.25), inset 0 0 40px 2px rgba(0, 0, 0, 0.25)'
        });

        this.init();
    }

    Plugin.prototype = {
        init: function() {
            $(this.element).wrapInner(this.templates.oldContent).append(this.templates.main.clone());

            var main = $(this.element).find('.' + mainClass);

            this._initMasterImages(main);
            this._initPointer(main);
            this._initOverlay(main);
            this._initMagnifier(main, typeof this.settings.magnify !== "undefined");
        },
        generateHighlight: function(data) {
            var html = $(this.element);

            var main = html.find('.' + mainClass).hide();
            var masterImages = main.find('.' + masterImagesClass).hide();
            var keyHighlights = main.find('.' + keyHighlightsClass).hide();
            var allMasterImages = masterImages.find('> div').hide().end();

            var identifier = this._getIdentifier(data);
            var highlight, scrData, pointerRatio, pointerOffsetLeft = 0,
                pointerOffsetTop = 0;

            if (data.display) {
                masterImages.show();
                var masterImage = allMasterImages.find('.wm-hl-master-image-' + (data.orientation == 0 ? 'v' : 'h')).show();
                scrData = masterImage.data();
                var highlights = masterImage.find('.' + highlightsClass);
                html.css({
                    width: scrData.width,
                    height: scrData.height
                });
                masterImages.css({
                    width: scrData.width,
                    height: scrData.height
                });

                highlight = this.templates.highlight.clone().addClass(identifier);
                highlight.find('img')
                    .attr('src', this._getUrl(data.display, scrData.screenWidth, scrData.screenHeight, null, data.pointer == ""))
                    .data({
                        'original-width': data.displayWidth,
                        'original-height': data.displayHeight
                    });
                highlights.append(highlight);

                pointerRatio = data.pointerType == 'key' ? scrData.ratio : calculateRatio(data.displayWidth, data.displayHeight, scrData.screenWidth, scrData.screenHeight);
                pointerOffsetLeft = data.pointerType == 'key' ? 0 : scrData.screenPositionLeft;
                pointerOffsetTop = data.pointerType == 'key' ? 0 : scrData.screenPositionTop;
            } else {
                keyHighlights.show();
                scrData = keyHighlights.data();
                highlight = keyHighlights.find('.' + identifier);
                var hlDimensions = calculateAspectRatioFit(data.masterWidth, data.masterHeight, this.settings.maxWidth, this.settings.maxHeight);

                html.css({
                    width: hlDimensions.width,
                    height: hlDimensions.height
                });
                main.css({
                    width: hlDimensions.width,
                    height: hlDimensions.height
                });
                pointerRatio = hlDimensions.ratio;

                highlight = this.templates.highlight.clone().addClass(identifier);
                highlight.find('img').attr('src', this._getUrl(data.masterImage, hlDimensions.width, hlDimensions.height, null, data.pointer == "")).data({
                    'original-width': data.masterWidth,
                    'original-height': data.masterHeight
                });
                highlight.find("img").css('width', hlDimensions.width + 'px');
                highlight.find("img").css('height', hlDimensions.height + 'px');
                keyHighlights.append(highlight);
            }

            highlight.show().siblings().hide();
            main.show();

            this._setPointer(html.find('.' + pointerClass), data, pointerRatio, pointerOffsetTop, pointerOffsetLeft, false);

            if (data.overlayText !== undefined) {
                var imgRatio = calculateRatio(data.displayWidth, data.displayHeight, scrData.screenWidth, scrData.screenHeight);
                this._setOverlay(html.find('.' + overlayClass), data.overlayText, (data.overlayTop * imgRatio) + (scrData.screenPositionTop || 0), (data.overlayLeft * imgRatio) + (scrData.screenPositionLeft || 0), data.overlayPosition);
            }
            //console.log(html); //HTML INDIVIDUAL
            return html;
        },
        show: function(data, animate) {
            var html = $(this.element);

            var old = html.find('.' + oldClass).hide();
            var overlay = html.find('.' + overlayClass).hide();
            var main = html.find('.' + mainClass).hide();
            var magnifier = html.find('.' + magnifierClass);
            var masterImages = main.find('.' + masterImagesClass).hide();
            var keyHighlights = main.find('.' + keyHighlightsClass).hide();
            var allMasterImages = masterImages.find('> div').hide().end();

            var identifier = this._getIdentifier(data);
            var highlight, scrData, pointerRatio, zoomImage, pointerOffsetLeft = 0,
                pointerOffsetTop = 0;

            this._toggleZoomHelper(false);

            if (typeof this.settings.magnify !== "undefined") {
                magnifier.hide();
            }
            if (data.display) {
                masterImages.show();
                var masterImage = allMasterImages.find('.wm-hl-master-image-' + (data.orientation == 0 ? 'v' : 'h')).show();
                scrData = masterImage.data();
                var highlights = masterImage.find('.' + highlightsClass);
                main.css({
                    width: scrData.width,
                    height: scrData.height
                });
                masterImages.css({
                    width: scrData.width,
                    height: scrData.height
                });

                highlight = highlights.find('.' + identifier);

                if (!highlight.length) {
                    highlight = this.templates.highlight.clone().addClass(identifier);
                    highlight.find('img')
                        .attr('src', this._getUrl(data.display, scrData.screenWidth, scrData.screenHeight, null, data.pointer == ""))
                        .data({
                            'original-width': data.displayWidth,
                            'original-height': data.displayHeight
                        });
                    highlights.append(highlight);
                }
                pointerRatio = data.pointerType == 'key' ? scrData.ratio : calculateRatio(data.displayWidth, data.displayHeight, scrData.screenWidth, scrData.screenHeight);
                pointerOffsetLeft = data.pointerType == 'key' ? 0 : scrData.screenPositionLeft;
                pointerOffsetTop = data.pointerType == 'key' ? 0 : scrData.screenPositionTop;

                if (data.pointerType != 'key') {
                    zoomImage = this._getUrl(data.display);
                }
            } else {
                keyHighlights.show();
                scrData = keyHighlights.data();
                highlight = keyHighlights.find('.' + identifier);
                var hlDimensions = calculateAspectRatioFit(data.masterWidth, data.masterHeight, this.settings.maxWidth, this.settings.maxHeight);

                main.css({
                    width: hlDimensions.width,
                    height: hlDimensions.height
                });
                pointerRatio = hlDimensions.ratio;

                if (!highlight.length) {
                    highlight = this.templates.highlight.clone().addClass(identifier);
                    highlight.find('img').attr('src', this._getUrl(data.masterImage, hlDimensions.width, hlDimensions.height, null, data.pointer == "")).data({
                        'original-width': data.masterWidth,
                        'original-height': data.masterHeight
                    });
                    highlight.find("img").css('width', hlDimensions.width + 'px');
                    highlight.find("img").css('height', hlDimensions.height + 'px');
                    keyHighlights.append(highlight);
                }
            }

            if (typeof this.settings.magnify !== "undefined") {
                if (zoomImage && zoomImage.length > 0) {
                    magnifier.css('background-image', 'url(' + zoomImage + ')');
                    this._toggleZoomHelper(true);
                }
            }

            highlight.show().siblings().hide();
            main.show();

            this._setPointer(html.find('.' + pointerClass), data, pointerRatio, pointerOffsetTop, pointerOffsetLeft, animate);

            if (data.overlayText !== undefined) {
                var imgRatio = calculateRatio(data.displayWidth, data.displayHeight, scrData.screenWidth, scrData.screenHeight);
                this._setOverlay(html.find('.' + overlayClass), data.overlayText, (data.overlayTop * imgRatio) + (scrData.screenPositionTop || 0), (data.overlayLeft * imgRatio) + (scrData.screenPositionLeft || 0), data.overlayPosition);
            }
        },
        getPointerPosition: function() {
            return pointer.position();
        },
        _initPointer: function(target) {
            target.append(this.templates.pointer.clone()
                .css({
                    top: this.settings.pointer.start.top,
                    left: this.settings.pointer.start.left
                }));
        },
        _initOverlay: function(target) {
            target.append(this.templates.overlay.clone());
        },
        _initMasterImages: function(target) {
            var masterImageDimensions = {
                width: this.settings.maxWidth,
                height: this.settings.maxHeight,
                portrait: {
                    origWidth: this.settings.maxWidth,
                    width: this.settings.maxWidth,
                    origHeight: this.settings.maxHeight,
                    height: this.settings.maxHeight,
                    screenPositionLeft: 0,
                    screenPositionTop: 0,
                    screenWidth: this.settings.maxWidth,
                    screenHeight: this.settings.maxHeight,
                    ratio: 1
                },
                landscape: {
                    origWidth: this.settings.maxHeight,
                    width: this.settings.maxHeight,
                    origHeight: this.settings.maxWidth,
                    height: this.settings.maxWidth,
                    screenPositionTop: 0,
                    screenPositionLeft: 0,
                    screenWidth: this.settings.maxHeight,
                    screenHeight: this.settings.maxWidth,
                    ratio: 1
                }
            };

            if (typeof this.settings.masterImage !== "undefined") {

                var dimensions = {};
                dimensions.portrait = calculateAspectRatioFit(this.settings.masterImage.width, this.settings.masterImage.height, this.settings.maxWidth, this.settings.maxHeight);
                dimensions.portrait.origWidth = this.settings.masterImage.width;
                dimensions.portrait.origHeight = this.settings.masterImage.height;
                dimensions.portrait.screenPositionTop = this.settings.masterImage.screenPositionTop * dimensions.portrait.ratio;
                dimensions.portrait.screenPositionLeft = this.settings.masterImage.screenPositionLeft * dimensions.portrait.ratio;
                dimensions.portrait.screenWidth = this.settings.masterImage.screenWidth * dimensions.portrait.ratio;
                dimensions.portrait.screenHeight = this.settings.masterImage.screenHeight * dimensions.portrait.ratio;
                dimensions.portrait.masterImage = $('<img />').attr('src', this._getUrl(this.settings.masterImage.name, dimensions.portrait.width, dimensions.portrait.height));

                dimensions.landscape = calculateAspectRatioFit(this.settings.masterImage.height, this.settings.masterImage.width, this.settings.maxWidth, this.settings.maxHeight);
                dimensions.landscape.origWidth = this.settings.masterImage.height;
                dimensions.landscape.origHeight = this.settings.masterImage.width;
                dimensions.landscape.screenPositionTop = this.settings.masterImage.screenPositionLeft * dimensions.landscape.ratio;
                dimensions.landscape.screenPositionLeft = this.settings.masterImage.screenPositionTop * dimensions.landscape.ratio;
                dimensions.landscape.screenWidth = this.settings.masterImage.screenHeight * dimensions.landscape.ratio;
                dimensions.landscape.screenHeight = this.settings.masterImage.screenWidth * dimensions.landscape.ratio;
                dimensions.landscape.masterImage = $('<img />').attr('src', this._getUrl(this.settings.masterImage.name, dimensions.landscape.width, dimensions.landscape.height, 270));

                $.extend(masterImageDimensions, dimensions);

            }

            var masterImage = this.templates.masterImages
                .clone()
                .css({
                    width: masterImageDimensions.width,
                    height: masterImageDimensions.height
                });

            var masterImagePortrait = this.templates.masterImages.vertical
                .clone()
                .css({
                    width: masterImageDimensions.portrait.width,
                    height: masterImageDimensions.portrait.height
                })
                .data({
                    width: masterImageDimensions.portrait.width,
                    height: masterImageDimensions.portrait.height,
                    screenPositionTop: masterImageDimensions.portrait.screenPositionTop,
                    screenPositionLeft: masterImageDimensions.portrait.screenPositionLeft,
                    screenWidth: masterImageDimensions.portrait.screenWidth,
                    screenHeight: masterImageDimensions.portrait.screenHeight,
                    originalWidth: masterImageDimensions.portrait.origWidth,
                    originalHeight: masterImageDimensions.portrait.origHeight,
                    ratio: masterImageDimensions.portrait.ratio
                })
                .append(masterImageDimensions.portrait.masterImage)
                .append(this.templates.highlights.clone().css({
                    left: masterImageDimensions.portrait.screenPositionLeft,
                    top: masterImageDimensions.portrait.screenPositionTop,
                    width: masterImageDimensions.portrait.screenWidth,
                    height: masterImageDimensions.portrait.screenHeight
                }));

            var masterImageLandscape = this.templates.masterImages.horizontal
                .clone()
                .css({
                    width: masterImageDimensions.landscape.width,
                    height: masterImageDimensions.landscape.height
                })
                .data({
                    width: masterImageDimensions.landscape.width,
                    height: masterImageDimensions.landscape.height,
                    screenPositionTop: masterImageDimensions.landscape.screenPositionTop,
                    screenPositionLeft: masterImageDimensions.landscape.screenPositionLeft,
                    screenWidth: masterImageDimensions.landscape.screenWidth,
                    screenHeight: masterImageDimensions.landscape.screenHeight,
                    originalWidth: masterImageDimensions.landscape.origWidth,
                    originalHeight: masterImageDimensions.landscape.origHeight,
                    ratio: masterImageDimensions.landscape.ratio
                })
                .append(masterImageDimensions.landscape.masterImage)
                .append(this.templates.highlights.clone().css({
                    left: masterImageDimensions.landscape.screenPositionLeft,
                    top: masterImageDimensions.landscape.screenPositionTop,
                    width: masterImageDimensions.landscape.screenWidth,
                    height: masterImageDimensions.landscape.screenHeight
                }));

            var keyHighlights = this.templates.keyHighlights
                .clone()
                .data({
                    screenPositionTop: masterImageDimensions.portrait.screenPositionTop,
                    screenPositionLeft: masterImageDimensions.portrait.screenPositionLeft,
                    screenWidth: masterImageDimensions.portrait.screenWidth,
                    screenHeight: masterImageDimensions.portrait.screenHeight,
                    originalWidth: masterImageDimensions.portrait.origWidth,
                    originalHeight: masterImageDimensions.portrait.origHeight
                });
            target.css({
                width: this.settings.maxWidth,
                height: this.settings.maxHeight
            }).append(masterImage.append(masterImagePortrait, masterImageLandscape), keyHighlights);
        },
        _initMagnifier: function(target, init) {
            if (!init)
                return false;
            target.append(
                    this.templates.magnifier
                    .clone()
                    .css({
                        'width': this.settings.magnify.width,
                        'height': this.settings.magnify.height,
                    }))
                .on('mousemove', function(e) {
                    var magnifier = target.find('.' + magnifierClass);

                    var highlight = target.find('.wm-hl-highlights:visible, .wm-hl-keyhighlights:visible').find('.' + highlightClass + ':visible');

                    if (!allowZoom || highlight.length != 1) {
                        return;
                    }

                    var imageWidth = highlight.width();
                    var imageHeight = highlight.height();
                    var highlightPosition = highlight.position();

                    var magnify_offset = target.offset();
                    var mx = e.pageX - magnify_offset.left - highlightPosition.left;
                    var my = e.pageY - magnify_offset.top - highlightPosition.top;

                    if (mx < imageWidth && my < imageHeight && mx > 0 && my > 0) {
                        magnifier.fadeIn(100);
                    } else {
                        magnifier.fadeOut(100);
                    }

                    if (magnifier.is(":visible")) {
                        var data = highlight.find('img:visible').data();
                        var rx = Math.round(mx / imageWidth * data['original-width'] - magnifier.width() / 2) * -1;
                        var ry = Math.round(my / imageHeight * data['original-height'] - magnifier.height() / 2) * -1;
                        var bgp = rx + "px " + ry + "px";

                        var px = mx - magnifier.width() / 2;
                        var py = my - magnifier.height() / 2;

                        magnifier.css({
                            left: px + highlightPosition.left,
                            top: py + highlightPosition.top,
                            backgroundPosition: bgp
                        });
                    }
                });
        },
        _toggleZoomHelper: function(toggle) {
            allowZoom = toggle;
            $(this.settings.zoomHelper).toggle(toggle);
        },
        _setPointer: function(obj, data, pointerRatio, pointerOffsetTop, pointerOffsetLeft, animate) {
            if (!data.pointer) {
                obj.hide();
                return;
            }

            var oldPointerOffset = {
                top: 0,
                left: 0
            };
            if (data.pointerOldCmt) {
                oldPointerOffset = this._oldPointerOffset(parseInt(data.pointerType), 1);
            }

            var top = (data.top * pointerRatio) + pointerOffsetTop - data.pointerTop - oldPointerOffset.top;
            var left = (data.left * pointerRatio) + pointerOffsetLeft - data.pointerLeft - oldPointerOffset.left;
            var image = this._getUrl(data.pointer);
            var speed = data.pointerSpeed;
            var frames = data.pointerFrames;
            var width = data.pointerWidth;
            var height = data.pointerHeight;


            var pointer = obj
                .show()
                .stop("fx", true)
                .stop(true)
                .css({
                    'background-image': '',
                    'background-position-x': 0,
                    'opacity': 1,
                    'width': width,
                    'height': height
                });

            var img = pointer.find('img');

            if (height) {
                img.css({
                    'height': height
                });
            }

            if (speed && speed > 0) {
				//console.log(image);
				if(image == ''){ image = "'images/pointer002.png'"; }
				
                img.hide();
                pointer.css({
                    'width': width,
                    'height': height,
                    'background-image': 'url(' + image + ')'
                });

                if (image != activePointerImage) {
                    frame = 0;
                    clearInterval(pointerTimer);

                    if (frames > 1) {
                        pointerTimer = setInterval(function() {
                            nextFrame(width);
                        }, speed);
                    } else {
                        pointerTimer = setInterval(function() {
                            fadeInFadeOut(speed);
                        }, speed * 2 + 200);
                    }
                }

            } else {
                img.show().attr('src', image);
            }

            if (animate) {
                pointer.stop(true).animate({
                    top: top,
                    left: left
                }, 400);
            } else {
                pointer.css({
                    top: top,
                    left: left
                });
            }

            activePointerImage = image;
        },
        _oldPointerOffset: function(type, ratio) {
            var top = 0;
            var left = 0;

            switch (type) {
                case 1:
                case 5:
                    top = 35 - (14 * ratio);
                    left = 25 - (4 * ratio);
                    break;
                case 2:
                case 6:
                    top = 34 - (14 * ratio);
                    left = 34 - (14 * ratio);
                    break;
                case 3:
                case 7:
                    top = 25 - (4 * ratio);
                    left = 24 - (4 * ratio);
                    break;
                case 4:
                case 8:
                    top = 26 - (4 * ratio);
                    left = 35 - (14 * ratio);
                    break;
                case 9:
                case 10:
                    top = 30 - (10 * ratio);
                    left = 30 - (10 * ratio);
                    break;
                case 14:
                    top = 20 - (4 * ratio);
                    left = 29 - (9 * ratio);
                    break;
                case 13:
                    top = 30 - (11 * ratio);
                    left = 20 - (3 * ratio);
                    break;
                case 12:
                    top = 39 - (19 * ratio);
                    left = 30 - (9 * ratio);
                    break;
                case 15:
                    top = 29 - (11 * ratio);
                    left = 39 - (16 * ratio);
                    break;
                case 11:
                case 16:
                    top = 29 - (11 * ratio);
                    left = 29 - (10 * ratio);
                    break;
                default:
            }

            return {
                top: top,
                left: left
            };
        },
        _setOverlay: function(obj, text, top, left, position) {
            /// <param name="position" type="Number">0: north, 1: south</param>
            var html = (typeof(this.settings.overlay.html) === 'function' ? this.settings.overlay.html() : this.settings.overlay.html).replace('{text}', text);
            var overlay = obj.css({
                top: -9999,
                left: -9999
            }).html(html).show();

            var offsetTop = 0;
            var offsetLeft = 0;

            var dimensions = getDimensions(overlay);
            // north
            if (position == 0) {
                offsetTop -= dimensions.height;
            }

            // north / south
            if (position == 1 || position == 0) {
                offsetLeft -= dimensions.width / 2;
            }

            // Reversed because its the direction of the arrow now!
            overlay.find(':first-child')
                .toggleClass('north', position == 1)
                .toggleClass('south', position == 0);

            top += offsetTop;
            left += offsetLeft;

            overlay.css({
                top: top,
                left: left
            });
        },
        _getUrl: function(name, maxWidth, maxHeight, rotate, noPointer) {
			if(name == 75 || name == 74){
				if(name === 75 || name === '75'){
					var url = "images/pointer002.png?public=alls";
				}else if(name === 74 || name === ''){
					var url = "images/pointer001.png?public=alls";
				}else{
					name = '&id=' + name + '&out_type=png';
					var url = this.settings.cdn + FormaT.AccessToken() + name;
				}
				
				//console.log(url);
			}else{
				name = '&id=' + name + '&out_type=png';
				if (noPointer) {
					return "http://wmstatic.global.ssl.fastly.net/qelp/" + name;
				}

				var url = this.settings.cdn + FormaT.AccessToken() + name;
				
				//////////////////////////////console.log(url);
				
				/**
				if (name.indexOf('/Content/images/') == 0 ||
					name.indexOf("http://") == 0 ||
					name.indexOf("https://") == 0 ||
					name.indexOf("//") == 0) {
					url = name;
				}
				**/

			};
			
				var params = '';

				if (maxWidth && maxWidth > 0)
					params += 'width=' + Math.ceil(maxWidth) + '&';

				if (maxHeight && maxHeight > 0)
					params += 'height=' + Math.ceil(maxHeight) + '&';

				if (rotate)
					params += 'rotate=' + rotate + '&';
			
				//console.log(url + '?1&' + params); // URL DE LAS IMAGENES
				return url + '&' + params;
        },
        _getIdentifier: function(data) {
            var name = data.display || data.masterImage;
			
            //name = name.lastIndexOf('/') > -1 && name.lastIndexOf('.') > -1 ? name.substring(name.lastIndexOf('/') + 1, name.lastIndexOf('.')) : name;
			name = 'wm-hl-' + name + '-' + data.pointerType + '-' + data.top + '-' + data.left;
			//console.log('wm-hl-' + name + '-' + data.pointerType + '-' + data.top + '-' + data.left); //IDENTIFICADOR DE LA IMAGEN  (CLASS)
			///////////////////////////////console.log(name);
            return name;
        }
    };

    var fadeInFadeOut = function(speed) {
        $('.' + pointerClass).fadeOut(speed).fadeIn(speed);
    }

    var nextFrame = function(offset) {
        $('.' + pointerClass).css('background-position-x', -(frame * offset));
        if (frame > frames) frame = 0;
        else frame++;
    };

    var calculateAspectRatioFit = function(srcWidth, srcHeight, maxWidth, maxHeight) {
        var ratio = calculateRatio(srcWidth, srcHeight, maxWidth, maxHeight);
		areturn = {
            width: srcWidth * ratio,
            height: srcHeight * ratio,
            ratio: ratio
        };
		
		//console.log(areturn);
        return areturn;
    }

    var calculateRatio = function(srcWidth, srcHeight, maxWidth, maxHeight) {
        var ratio = Math.min(maxWidth / srcWidth, maxHeight / srcHeight);
        return ratio > 1 ? 1 : ratio;
    };

    var getDimensions = function(domObj) {
        var shadowElm = domObj.clone(false).css({
            'visibility': 'hidden',
            'position': 'absolute'
        });

        shadowElm.appendTo('.worldmanuals');
        var width = shadowElm.outerWidth(true);
        var height = shadowElm.outerHeight(true);
        shadowElm.remove();
        return {
            'width': width,
            'height': height
        };
    }

    $.fn[pluginName] = function(options) {
        var plugin;

        this.each(function() {
            plugin = $.data(this, 'plugin_' + pluginName);
            if (!plugin) {
                plugin = new Plugin(this, options);
                $.data(this, 'plugin_' + pluginName, plugin);
            }
        });

        return plugin;
    };

})(jQuery, window, document);
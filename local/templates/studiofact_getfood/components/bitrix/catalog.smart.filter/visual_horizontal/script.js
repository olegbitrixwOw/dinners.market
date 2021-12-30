function JCSmartFilter(ajaxURL, viewMode, params)
{
	this.ajaxURL = ajaxURL;
	this.form = null;
	this.timer = null;
	this.cacheKey = '';
	this.cache = [];
	this.popups = [];
	this.viewMode = viewMode;
	if (params && params.SEF_SET_FILTER_URL)
	{
		this.bindUrlToButton('set_filter', params.SEF_SET_FILTER_URL);
		this.sef = true;
	}
	if (params && params.SEF_DEL_FILTER_URL)
	{
		this.bindUrlToButton('del_filter', params.SEF_DEL_FILTER_URL);
	}
}

JCSmartFilter.prototype.keyup = function(input)
{
	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(input);
	}, this), 500);
};

JCSmartFilter.prototype.click = function(checkbox)
{
	if(!!this.timer)
	{
		clearTimeout(this.timer);
	}

	this.timer = setTimeout(BX.delegate(function(){
		this.reload(checkbox);
	}, this), 500);
};

JCSmartFilter.prototype.reload = function(input)
{
	if (this.cacheKey !== '')
	{
		//Postprone backend query
		if(!!this.timer)
		{
			clearTimeout(this.timer);
		}
		this.timer = setTimeout(BX.delegate(function(){
			this.reload(input);
		}, this), 1000);
		return;
	}
	this.cacheKey = '|';

	this.position = BX.pos(input, true);

	this.position.top = $(input).parent().offset().top - $(input).parents('form').offset().top;
	
	this.form = BX.findParent(input, {'tag':'form'});
	if (this.form)
	{
		var values = [];
		values[0] = {name: 'ajax', value: 'y'};
		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag': new RegExp('^(input|select)$', 'i')}, true));

		for (var i = 0; i < values.length; i++)
			this.cacheKey += values[i].name + ':' + values[i].value + '|';

		if (this.cache[this.cacheKey])
		{
			this.curFilterinput = input;
			this.postHandler(this.cache[this.cacheKey], true);
		}
		else
		{
			this.curFilterinput = input;
			BX.ajax.loadJSON(
				this.ajaxURL,
				this.values2post(values),
				BX.delegate(this.postHandler, this)
			);
		}
	}
};

JCSmartFilter.prototype.updateItem = function (PID, arItem)
{
	if (arItem.PROPERTY_TYPE === 'N' || arItem.PRICE)
	{
		var trackBar = window['trackBar' + PID];
		if (!trackBar && arItem.ENCODED_ID)
			trackBar = window['trackBar' + arItem.ENCODED_ID];

		if (trackBar && arItem.VALUES)
		{
			if (arItem.VALUES.MIN)
			{
				if (arItem.VALUES.MIN.FILTERED_VALUE)
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.FILTERED_VALUE);
				else
					trackBar.setMinFilteredValue(arItem.VALUES.MIN.VALUE);
			}

			if (arItem.VALUES.MAX)
			{
				if (arItem.VALUES.MAX.FILTERED_VALUE)
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.FILTERED_VALUE);
				else
					trackBar.setMaxFilteredValue(arItem.VALUES.MAX.VALUE);
			}
		}
	}
	else if (arItem.VALUES)
	{
		for (var i in arItem.VALUES)
		{
			if (arItem.VALUES.hasOwnProperty(i))
			{
				var value = arItem.VALUES[i];
				var control = BX(value.CONTROL_ID);

				if (!!control)
				{
					var label = document.querySelector('[data-role="label_'+value.CONTROL_ID+'"]');
					if (value.DISABLED)
					{
						if (label)
							BX.addClass(label, 'disabled');
						else
							BX.addClass(control.parentNode, 'disabled');
					}
					else
					{
						if (label)
							BX.removeClass(label, 'disabled');
						else
							BX.removeClass(control.parentNode, 'disabled');
					}

					if (value.hasOwnProperty('ELEMENT_COUNT'))
					{
						label = document.querySelector('[data-role="count_'+value.CONTROL_ID+'"]');
						if (label)
							label.innerHTML = value.ELEMENT_COUNT;
					}
				}
			}
		}
	}
};

JCSmartFilter.prototype.postHandler = function (result, fromCache)
{
	var hrefFILTER, url, curProp;
	var modef = BX('modef');
	var modef_num = BX('modef_num');
	if (!!result && !!result.ITEMS)
	{
		for(var PID in result.ITEMS)
		{
			if (result.ITEMS.hasOwnProperty(PID))
			{
				this.updateItem(PID, result.ITEMS[PID]);
			}
		}
		if (!!modef && !!modef_num)
		{
			modef_num.innerHTML = result.ELEMENT_COUNT;
			hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);

			if (result.FILTER_URL && hrefFILTER)
			{
				hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);
			}

			if (result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID)
			{
				BX.bind(hrefFILTER[0], 'click', function(e)
				{
					url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
					return BX.PreventDefault(e);
				});
			}

			if (result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID)
			{
				url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
				BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
			}
			else
			{
				if (modef.style.display === 'none')
				{
					modef.style.display = 'block';
				}

				modef.style.top = (this.position.top-4) + 'px';

				if (this.viewMode == "vertical")
				{
					curProp = BX.findChild(BX.findParent(this.curFilterinput, {'class':'bx_filter_parameters_box'}), {'class':'bx_filter_container_modef'}, true, false);
					curProp.appendChild(modef);
				}

                if (result.SEF_SET_FILTER_URL)
                {
                    this.bindUrlToButton('set_filter', result.SEF_SET_FILTER_URL);
                }
			}
		}

	}

	if (!fromCache && this.cacheKey !== '')
	{
		this.cache[this.cacheKey] = result;
	}
	this.cacheKey = '';
};

JCSmartFilter.prototype.bindUrlToButton = function (buttonId, url)
{
	var button = BX(buttonId);
	if (button)
	{
		var proxy = function(j, func)
		{
			return function()
			{
				return func(j);
			}
		};

		if (button.type == 'submit')
			button.type = 'button';

		BX.bind(button, 'click', proxy(url, function(url)
		{
			window.location.href = url;
			return false;
		}));
	}
};

JCSmartFilter.prototype.gatherInputsValues = function (values, elements)
{
	if(elements)
	{
		for(var i = 0; i < elements.length; i++)
		{
			var el = elements[i];
			if (el.disabled || !el.type)
				continue;

			switch(el.type.toLowerCase())
			{
				case 'text':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for (var j = 0; j < el.options.length; j++)
					{
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
}

JCSmartFilter.prototype.values2post = function (values)
{
	var post = new Array;
	var current = post;
	var i = 0;
	while(i < values.length)
	{
		var p = values[i].name.indexOf('[');
		if(p == -1)
		{
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		}
		else
		{
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = new Array;

			var pp = rest.indexOf(']');
			if(pp == -1)
			{
				//Error - not balanced brackets
				current = post;
				i++;
			}
			else if(pp == 0)
			{
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			}
			else
			{
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
}

function cDoubleTrackBar(Track, Tracker,LeftDrag, RightDrag, Settings)
{
	switch(typeof Track){
		case 'string': this.Track = document.getElementById(Track); break;
		case 'object': this.Track = Track; break;
	}
	switch(typeof Tracker){
		case 'string': this.Tracker = document.getElementById(Tracker); break;
		case 'object': this.Tracker = Tracker; break;
	}
	switch(typeof LeftDrag){
		case 'string': this.LeftDrag = document.getElementById(LeftDrag); break;
		case 'object': this.LeftDrag = LeftDrag; break;
	}
	switch(typeof RightDrag){
		case 'string': this.RightDrag = document.getElementById(RightDrag); break;
		case 'object': this.RightDrag = RightDrag; break;
	}
	if (!Track || !Tracker)
		return false;
	this.OnUpdate = Settings.OnUpdate;
	this.OnComplete = Settings.OnComplete;
	this.FingerOffset = Settings.FingerOffset || 0;
	this.Min = Settings.Min || 0;
	this.Max = Settings.Max || 100;
	this.MinSpace = Settings.MinSpace || 0;
	this.RoundTo = Settings.RoundTo || 1;
	if (this.RoundTo < 1)
	{
		this.Precision = parseInt(Settings.Precision, 10) || 0;
		if (isNaN(this.Precision))
		{
			this.Precision = 0;
		}
	}
	else
	{
		this.Precision = 0;
	}
	this.PrecisionFactor = Math.pow(10,this.Precision);

	this.Disabled = (typeof Settings.Disabled != 'undefined') ? Settings.Disabled : false;

	if (this.Min >= this.Max)
		this.Max = this.Min +1;
	this.MinPos = this.Min;
	this.MaxPos = this.Max;
	if (this.Max - this.Min < this.MinSpace)
		this.MinSpace =  this.Max - this.Min;
	if (this.Max - this.Min < this.RoundTo)
		this.RoundTo =  this.Max - this.Min;
	this.MinSpace = Math.ceil(this.MinSpace/this.RoundTo)*this.RoundTo;

	//this.Track.style.width = (this.Track.clientWidth || this.Track.offsetWidth) + 'px';
	this.OnTrackMouseDown = this.bindAsEventListener(this.TrackMouseDown);
	this.OnDocumentMouseMove = this.bindAsEventListener(this.DocumentMouseMove);
	this.OnDocumentMouseUp = this.bindAsEventListener(this.DocumentMouseUp);

	if ('ontouchstart' in document.documentElement)
	{
		this.bindEvent(this.Track, 'touchstart', this.OnTrackMouseDown);
	}
	else
		this.bindEvent(this.Track, 'mousedown', this.OnTrackMouseDown);

	this.TrackerLeft = 0;
//	this.UpdateTracker(this.Track.offsetWidth + this.FingerOffset);
	/*	if (typeof this.OnUpdate == 'function') {
	 this.OnUpdate.call(this);
	 }*/

	this.MinInputId = Settings.MinInputId || 0;
	this.MaxInputId = Settings.MaxInputId || 1000;

	BX.defer(BX.proxy(this.startPosition, this))();
}
cDoubleTrackBar.prototype = {

	TrackMouseDown: function(event) {
		this.TrackerLeft = this.Tracker.offsetLeft;
		this.TrackerRight = this.TrackerLeft + this.Tracker.offsetWidth;

		this.TrackerOffsets = this.getOffsets(this.Track);

		var currentX = ('ontouchmove' in document.documentElement) ? event.targetTouches[0].pageX : event.clientX;
		var X = currentX + document.documentElement.scrollLeft;
		X -= this.TrackerOffsets[0];

		var diff = Math.abs(this.TrackerLeft-X) - Math.abs(this.TrackerRight-X);
		if (diff == 0 && this.TrackerLeft == 0)
			this.Left = false;
		else
			this.Left = (diff <= 0);

		if (typeof this.Disabled == 'function') {
			if ( this.Disabled.call(this) )
				return true;
		} else if ( this.Disabled )
			return true;

		this.UpdateTracker(X);

		if ('ontouchmove' in document.documentElement)
		{
			this.bindEvent(document, 'touchmove', this.OnDocumentMouseMove);
			this.bindEvent(document, 'touchend', this.OnDocumentMouseUp);
		}
		else
		{
			this.bindEvent(document, 'mousemove', this.OnDocumentMouseMove);
			this.bindEvent(document, 'mouseup', this.OnDocumentMouseUp);
		}
		return this.stopEvent(event);
	},
	DocumentMouseMove: function(event) {
		var currentX = ('ontouchmove' in document.documentElement) ? event.targetTouches[0].pageX : event.clientX;
		this.UpdateTracker(currentX + document.documentElement.scrollLeft - this.TrackerOffsets[0]);
		return this.stopEvent(event);
	},
	DocumentMouseUp: function(event) {
		if ('ontouchmove' in document.documentElement)
		{
			this.unbindEvent(document, 'touchmove', this.OnDocumentMouseMove);
			this.unbindEvent(document, 'touchend', this.OnDocumentMouseUp);
		}
		else
		{
			this.unbindEvent(document, 'mousemove', this.OnDocumentMouseMove);
			this.unbindEvent(document, 'mouseup', this.OnDocumentMouseUp);
		}

		if (typeof this.OnComplete == 'function') {
			this.OnComplete.call(this);
		}
		return this.stopEvent(event);
	},
	UpdateTracker: function(X)
	{
		var _LogicWidth = this.Track.clientWidth;
		var _minSpace = Math.floor(_LogicWidth*this.MinSpace/(this.Max-this.Min));
		var _oldMin = this.MinPos;
		var _oldMax = this.MaxPos;

		if (this.Left)
		{
			X += this.FingerOffset;
			this.TrackerLeft = Math.max(0, Math.min(this.TrackerRight - _minSpace - 1, X));
			this.MinPos = Math.round((this.Min + this.TrackerLeft*(this.Max-this.Min)/_LogicWidth) / this.RoundTo) * this.RoundTo;
			if (this.MinSpace >= this.MaxPos - this.MinPos)
			{
				this.MinPos = this.MaxPos - this.MinSpace;
			}
			if (this.Precision > 0)
			{
				this.MinPos = Math.round(this.MinPos*this.PrecisionFactor)/this.PrecisionFactor;
			}

			this.TrackerLeft = this.price2px(this.Track, this.MinPos - this.Min);

			this.LeftDrag.style.left = this.px2percent(this.Track, this.TrackerLeft) + '%';
			this.Tracker.style.width = this.px2percent(this.Track, this.TrackerRight - this.TrackerLeft) + '%';
			this.Tracker.style.left = this.px2percent(this.Track, this.TrackerLeft) + '%';
			this.MinInputId.value = this.MinPos;
			smartFilter.keyup(this.MinInputId);
		}
		else
		{
			X -= this.FingerOffset;
			this.TrackerRight = Math.max(this.TrackerLeft + _minSpace + 1 , Math.min(_LogicWidth + 1, X));
			this.MaxPos = Math.round((this.Min + (this.TrackerRight-1)*(this.Max-this.Min)/_LogicWidth) / this.RoundTo) * this.RoundTo;
			if (this.MinSpace >= this.MaxPos - this.MinPos)
			{
				this.MaxPos = this.MinPos + this.MinSpace;
			}
			if (this.Precision > 0)
			{
				this.MaxPos = Math.round(this.MaxPos*this.PrecisionFactor)/this.PrecisionFactor;
			}

			this.TrackerRight = this.price2px(this.Track, this.MaxPos - this.Min);

			this.Tracker.style.left = this.px2percent(this.Track, this.TrackerLeft) + '%';
			this.Tracker.style.width = this.px2percent(this.Track, this.TrackerRight - this.TrackerLeft) + '%';
			this.RightDrag.style.left = this.px2percent(this.Track, this.TrackerRight) + '%';
			this.MaxInputId.value = this.MaxPos;
			smartFilter.keyup(this.MaxInputId);
		}
	},
	getOffsets: function(element) {
		var valueT = 0, valueL = 0;
		do {
			valueT += element.offsetTop  || 0;
			valueL += element.offsetLeft || 0;
			element = element.offsetParent;
		} while (element);
		return [valueL, valueT];
	},
	bindEvent: function(element, event, callBack){
		if (element.addEventListener) {
			element.addEventListener(event, callBack, false);
		} else {
			element.attachEvent('on' + event, callBack);
		}
	},
	unbindEvent: function(element, event, callBack){
		if (element.removeEventListener) {
			element.removeEventListener(event, callBack, false);
		} else if (element.detachEvent) {
			element.detachEvent('on' + event, callBack);
		}
	},
	bindAsEventListener: function (callBack) {
		var _object = this;
		return function(event) {
			return callBack.call(_object, event || window.event);
		}
	},
	stopEvent: function (event){
		if (event.preventDefault) {
			event.preventDefault();
			event.stopPropagation();
		} else {
			event.returnValue = false;
			event.cancelBubble = true;
		}
		return false;
	},
	startPosition: function ()
	{
		var curMinPrice = this.MinInputId.value || 0,
			curMaxPrice = this.MaxInputId.value || 0,
			curLeft = 0,
			curRight = 0;

		if (curMinPrice || curMaxPrice)
		{
			if (!curMinPrice || curMinPrice < this.Min|| curMinPrice > this.Max)
				curMinPrice = this.Min;
			if (!curMaxPrice || curMaxPrice > this.Max || curMaxPrice < this.Min)
				curMaxPrice = this.Max;

			if (curMinPrice)
				curLeft = this.price2px(this.Track, curMinPrice - this.Min);
			if (curMaxPrice)
				curRight = this.price2px(this.Track, curMaxPrice - this.Min);

			this.LeftDrag.style.left = this.px2percent(this.Track, curLeft) + "%";
			this.Tracker.style.left = this.px2percent(this.Track, curLeft) + "%";
			this.Tracker.style.width = this.px2percent(this.Track, curRight - curLeft) + "%";
			if (Math.round(this.px2percent(this.Track, curRight)) < 100)
				this.RightDrag.style.left = this.px2percent(this.Track, curRight)  + "%";
		}
	},
	px2percent: function (control, px)
	{
		return px / control.clientWidth * 100;
	},
	price2px: function (control, price)
	{
		var scale = (this.Max - this.Min) / control.clientWidth;
		return Math.round(price / scale);
	}
};

function resizeFilterPropsBlock() {
	var filterPropsBlock = $('#main_block_page .smart_filter_props'),
		filterBlockHeight = 0,
		padding = 18;
	$('.bx_filter_block').each(function (index, elem) {
		var elemHeight = $(elem).height();
		if (elemHeight > filterBlockHeight) {
			filterBlockHeight = elemHeight;
		}
	});
	filterPropsBlock.height(filterBlockHeight + $('.smart_filter_props0').height() + padding);
	$('.smart_filter_props .bx_filter_block').css({
		top: $('.smart_filter_props0').height()
	})
}

$(document).ready(function () {
	if ($(window).width() > 768) {
		resizeFilterPropsBlock();
	}

	/*$(".smart_filter_props").find(".bx_filter_block").css("width", parseInt($(".smart_filter_props").width()) - parseInt($(".smart_filter_props").find(".bx_filter_block").css("left")) + "px");*/
	if ($(".smart_filter_props0 .bx_filter_container_title").length > 0) {
		$(".smart_filter_props0 .bx_filter_container_title").eq(0).click();
		setTimeout(function () {
			$('.smart_filter_props .bx_filter_block').css({
				top: $('.smart_filter_props0').height()
			})
		}, 100);
	}
});
$(window).resize(function () {
	if ($(window).width() > 768) {
		resizeFilterPropsBlock();
	}

	/*$(".smart_filter_props").find(".bx_filter_block").css("width", parseInt($(".smart_filter_props").width()) - parseInt($(".smart_filter_props").find(".bx_filter_block").css("left")) + "px");*/
	var height = parseInt($(".smart_filter_props0").css("height"));
	if (height < parseInt($(".smart_filter_props .active").find(".bx_filter_block").css("height"))) {
		height = parseInt($(".smart_filter_props .active").find(".bx_filter_block").css("height"));
	}
	/*$(".smart_filter_props").stop(true, true).animate({
		height: height + "px"
	}, 350);*/
});
$(document).on("click", ".bx_catalog_section_box .bx_filter_container_title", function() {
	var obj = $(this).closest(".bx_filter_container");
	var height = parseInt($(".smart_filter_props0").css("height"));
	if (!obj.hasClass("active")) {
		$(".smart_filter_props").find(".bx_filter_container").removeClass("active");
		$(".smart_filter_props").find(".bx_filter_block").hide();
		obj.find(".bx_filter_block").fadeIn("fast", function() {
			if (height < parseInt(obj.find(".bx_filter_block").css("height"))) {
				height = parseInt(obj.find(".bx_filter_block").css("height"));
			}
			/*$(".smart_filter_props").stop(true, true).animate({
				height: height + "px"
			}, 350);*/
			obj.addClass("active");
		});
	}

	return false;
});

$(document).on("click", ".control_button_hide a", function () {
	$(".smart_filter").slideUp("fast");
	$(".bx_catalog_section_box .control_button_show").removeClass("active");
	return false;
});
$(document).on("click", ".control_button_show a", function () {
	if ($(".control_button_show").hasClass("active")) {
		$(".bx_filter_section").slideUp("fast");
		$('.smart_filter').addClass('smart_filter--closed');
		$(".control_button_show").removeClass("active");
		// $('.bx_catalog_text').addClass('bx_catalog_text--beautiful-bottom');
		$('#modef').hide();
	} else {
		$(".control_button_show").addClass("active");
		$(".bx_filter_section").slideDown("fast").css('overflow', 'visible');
		$('.smart_filter').removeClass('smart_filter--closed');
		// if ($('.bx_catalog_section_box .smart_filter').length) {
		// 	$('.bx_catalog_text').removeClass('bx_catalog_text--beautiful-bottom');
		// }
		if ($(window).width() > 768) {
			resizeFilterPropsBlock();
		}
	}

	return false;
});
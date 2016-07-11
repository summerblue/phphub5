(function($){

	/**
	 * For the item form transition
	 */
	ko.bindingHandlers.itemTransition = {
		init: function(element, valueAccessor, allBindingsAccessor, viewModel, context)
		{
			var $element = $(element),
				viewModel = context.$root,
				$child = $element.find('.item_edit'),
				$tableContainer = $('div.table_container'),
				expandWidth = viewModel.expandWidth();

			//the lastItem gets reset to null when the form is closed. This way we can draw the table properly initially
			//so that it doesn't keep reopening.
			if (viewModel.lastItem === null)
			{
				$tableContainer.css('margin-right', 290);
				$element.hide();
				$child.css('marginLeft', expandWidth + 2);
			}
			else
			{
				$tableContainer.css('margin-right', expandWidth + 5);
				$child.css('marginLeft', 2);
			}
		},
		update: function(element, valueAccessor, allBindingsAccessor, viewModel, context)
		{
			var $element = $(element),
				viewModel = context.$root,
				$child = $element.find('.item_edit'),
				$tableContainer = $('div.table_container'),
				expandWidth = viewModel.expandWidth();

			//if the value is false, we want to hide the form, otherwise show it
			if (!valueAccessor())
			{
				$child.stop().animate({marginLeft: expandWidth + 2}, 150, function()
				{
					$element.hide();
				});

				$tableContainer.stop().animate({marginRight: 290}, 150, function()
				{
					window.admin.resizePage();
				});
			}
			else
			{
				if (viewModel.lastItem === null)
				{
					$element.show();
					$child.stop().animate({marginLeft: 2}, 150);
					$tableContainer.stop().animate({marginRight: expandWidth + 5}, 150, function()
					{
						window.admin.resizePage();
					});
				}
			}
		}
	};

	var select2Defaults = {
			placeholder: adminData.languages['select_options'],
			formatNoMatches: function(term)
			{
				return adminData.languages['no_results'];
			},
			width: 'resolve',
			allowClear: true
		};

	//for select2
	ko.bindingHandlers.select2 = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				defaults = $.extend({}, select2Defaults),
				data;

			if (options && typeof options === 'object')
			{
				$.extend(defaults, options);
			}

			//pull the latest from the list
			if (defaults.data)
			{
				if ($.isFunction(defaults.data.results))
				{
					defaults.data.results = options.data.results();
				}

				$(element).data('list_data', defaults.data.results);

				defaults.data = function()
				{
					return {results: $(element).data('list_data')};
				}
			}

			//init select2 if it isn't already set up
			if ($(element).data("select2") === undefined || $(element).data("select2") === null)
			{
				//set the original list data in case we need it for sorting
				$(element).data('original_list_data', [].concat($(element).data('list_data')));

				$(element).select2(defaults);

				//if the sort option is set, set up jquery ui sortable
				if (options.sort)
				{
					$(element).select2('container').find('ul.select2-choices').sortable({
						containment: 'parent',
						start: function() { $(element).select2("onSortStart") },
						update: function() { $(element).select2("onSortEnd") }
					});
				}
			}

			//it's necessary to reorder the options array if the sort is set
			if (options.sort)
			{
				var listData = $(element).data('list_data'),
					val = $(element).val();

				//initially we want to reset the list data so we can work with a fresh, alphabetized sort
				$(element).data('list_data', [].concat($(element).data('original_list_data')));

				//if there is a value for this field, split it and find the relevant items in the array
				if (val)
				{
					var vals = val.split(','),
						topItems = [],
						allItems = $(element).data('list_data');

					//iterate over the values
					$.each(vals, function(ind, el)
					{
						//iterate over all the items to find our value
						$.each(allItems, function(i, e)
						{
							if (e.id == el)
							{
								topItems.push(e);
								allItems.splice(i, 1);
								return false;
							}
						});
					});

					$(element).data('list_data', topItems.concat(allItems));
				}
			}

			//make sure we're monitoring the change event for page resizing
			$(element).on('change', function()
			{
				window.admin.resizePage();
			});

			setTimeout(function()
			{
				$(element).trigger('change');
			}, 50);
		}
	};

	var select2RemoteHandler = function (element, valueAccessor, allBindingsAccessor, viewModel, context)
	{
		var options = valueAccessor(),
			defaults = $.extend({
				minimumInputLength: 1,
				allowClear: true,
				ajax: {
					url: base_url + adminData.model_name + '/update_options',
					dataType: 'json',
					quietMillis: 100,
					type: 'POST',
					data: function(term, page)
					{
						var data = {
								term: term,
								page: page,
								field: options.field,
								type: options.type,
								constraints: {}
							};

						if (data.type === 'edit')
						{
							data.selectedItems = admin.viewModel[data.field]();
						}
						else if (data.type === 'filter')
						{
							data.selectedItems = admin.filtersViewModel.filters[parseInt(options.filterIndex)].value();
						}

						//figure out if there are any constraints that we need to send over
						if (options.constraints)
						{
							$.each(options.constraints, function(ind, el)
							{
								data.constraints[ind] = admin.viewModel[ind]();
							});
						}

						return {fields: [data]};
					},
					results: function(returndata, page)
					{
						var data = {},
							val = $(element).val();

						//we want to update the autocomplete index so we can show all possibly-selected items
						if (val)
						{
							$(val.split(',')).each(function(ind, el)
							{
								data[this] = {id: this, text: admin.viewModel[options.field + '_autocomplete'][this].text};
							});
						}

						//iterate over the results and put them in the autocomplete array
						$.each(returndata[options.field], function(ind, el)
						{
							data[el.id] = el;
						});

						admin.viewModel[options.field + '_autocomplete'] = data;

						return {
							results: returndata[options.field]
						}
					}
				},
				initSelection: function(element, callback) {
					var data = [],
						val = $(element).val();

					// If the select2 field has a default value,
					// initSelection will be called before the admin object
					// is correctly initialized. 
					if (!val || typeof admin === 'undefined')
						return callback(null);

					//if this is a multi-select, set up the data as an array
					if (options.multiple)
					{
						$(element.val().split(',')).each(function(ind, el)
						{
							if(this in admin.viewModel[options.field + '_autocomplete'])
								data.push({id: this, text: admin.viewModel[options.field + '_autocomplete'][this].text});
						});
					}
					//otherwise make the data a simple object
					else
					{
						if(val in admin.viewModel[options.field + '_autocomplete'])
							data = {id: val, text: admin.viewModel[options.field + '_autocomplete'][val].text};
					}

					callback(data);
				}
			}, select2Defaults);

		if (options && typeof options === 'object')
		{
			$.extend(defaults, options);
		}

		//init select2 if it isn't already set up
		if ($(element).data("select2") === undefined || $(element).data("select2") === null)
		{
			$(element).select2(defaults);

			//if the sort option is set, set up jquery ui sortable
			if (options.sort)
			{
				$(element).select2('container').find('ul.select2-choices').sortable({
					containment: 'parent',
					start: function() { $(element).select2("onSortStart") },
					update: function() { $(element).select2("onSortEnd") }
				});
			}
		}

		setTimeout(function()
		{
			$(element).trigger('change');
		}, 50);
	}

	//for ajax/remote select2
	ko.bindingHandlers.select2Remote = {
		update: select2RemoteHandler
	};

	/**
	 * The number binding ensures that a value is decimal-like
	 */
	ko.bindingHandlers.number = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				value = allBindingsAccessor().value(),
				floatVal,
				$element = $(element);

			//if this is a null or false value, run a parseFloat on it so we can check for isNaN later
			if (value === null || value === false)
			{
				floatVal = parseFloat(value);
			}
			//else we will try to parse the number using the user-supplied thousands and decimal separators
			else
			{
				floatVal = parseFloat(value.toString().trim().split(options.thousandsSeparator).join('').split(options.decimalSeparator).join('.'));
			}

			//if the value is not a number, set the value equal to ''
			if (isNaN(floatVal))
			{
				allBindingsAccessor().value(null);

				//if this is an uneditable field, set the text
				if ($element.hasClass('uneditable'))
					$element.text('');
				//otherwise we know it's an input
				else
					$element.val('');
			}
			//else set up the value up using the accounting library with the user-supplied separators
			else
			{
				//if this is an uneditable field, set the text
				if ($element.hasClass('uneditable'))
					$element.text(accounting.formatMoney(floatVal, "",options.decimals, options.thousandsSeparator, options.decimalSeparator));
				//otherwise we know it's an input
				else
					$element.val(accounting.formatMoney(floatVal, "", options.decimals, options.thousandsSeparator, options.decimalSeparator));
			}
		}
	};

	/**
	 * The datepicker binding makes sure the jQuery UI datepicker is set for this item
	 */
	ko.bindingHandlers.datepicker = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor();

			$(element).datepicker({
				dateFormat: options.dateFormat
			});
		}
	};

	/**
	 * The formatDate binding transforms a date string into a formatted date
	 */
	ko.bindingHandlers.formatDate = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				dateVal = options.value.length === 10 ? options.val + ' 00:00' : options.val;

			$(element).text($.datepicker.formatDate(options.dateFormat, new Date(options.value)));
		}
	};

	/**
	 * The timepicker binding makes sure the jQuery UI timepicker is set for this item
	 */
	ko.bindingHandlers.timepicker = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				val = allBindingsAccessor().value(),
				date = new Date('01/01/2013 ' + val),
				timeObject = {
					hour: date.getHours(),
					minute: date.getMinutes()
				};

			if (val)
				$(element).val($.datepicker.formatTime(options.timeFormat, timeObject));

			$(element).timepicker({
				timeFormat: options.timeFormat
			});
		}
	};

	/**
	 * The formatTime binding transforms a time string into a formatted time
	 */
	ko.bindingHandlers.formatTime = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				date = new Date('01/01/2012 ' + options.value),
				timeObject = {
					hour: date.getHours(),
					minute: date.getMinutes()
				};

			$(element).text($.datepicker.formatTime(options.timeFormat, timeObject));
		}
	};

	/**
	 * The datetimepicker binding makes sure the jQuery UI datetimepicker is set for this item
	 */
	ko.bindingHandlers.datetimepicker = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				val = allBindingsAccessor().value(),
				date = new Date(val),
				timeObject = {
					hour: date.getHours(),
					minute: date.getMinutes()
				};

			if (val && !isNaN(date.getHours()))
			{

				var formattedDate = $.datepicker.formatDate(options.dateFormat, date),
					formattedTime = $.datepicker.formatTime(options.timeFormat, timeObject);

				$(element).val(formattedDate + ' ' + formattedTime);
			}

			$(element).datetimepicker({
				dateFormat: options.dateFormat,
				timeFormat: options.timeFormat
			});
		}
	};

	/**
	 * The formatTime binding transforms a datetime string into a formatted datetime
	 */
	ko.bindingHandlers.formatDateTime = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				date = new Date(options.value),
				timeObject = {
					hour: date.getHours(),
					minute: date.getMinutes()
				};

			if (!isNaN(date.getHours()))
			{
				var formattedDate = $.datepicker.formatDate(options.dateFormat, date),
					formattedTime = $.datepicker.formatTime(options.timeFormat, timeObject);

				$(element).text(formattedDate + ' ' + formattedTime);
			}
		}
	};

	/**
	 * The characterLimit binding makes sure a text field only has so many characters
	 */
	ko.bindingHandlers.characterLimit = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var limit = valueAccessor(),
				val = allBindingsAccessor().value();

			val = val === null ? '' : val + '';

			if (!limit || val === null || val.length < limit)
				return;

			val = val.substr(0, limit);

			$(element).val(val);
			allBindingsAccessor().value(val);
		}
	};

	/**
	 * The charactersLeft binding fills the element with (#chars allowed - #chars typed)
	 */
	ko.bindingHandlers.charactersLeft = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				limit = options.limit,
				val = options.value();

			val = val === null ? '' : val + '';

			//if the limit is zero, there is no limit
			if (!limit)
				return;

			//if the value is null, set it to an empty string
			if (val === null)
				val = '';

			left = limit - val.length;

//			text = ' character' + (left !== 1 ? 's' : '') + ' left';
			text = (left !== 1 ? adminData.languages['characters_left'] : adminData.languages['character_left']);

			$(element).text(left + text);
		}
	};

	/**
	 * This ensures that a bool field is always a boolean value
	 */
	ko.bindingHandlers.bool = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel, context)
		{
			var viewModel = context.$root,
				modelVal = viewModel[valueAccessor()]();

			if (modelVal === '0')
				viewModel[valueAccessor()](false);
			else if (modelVal === '1')
				viewModel[valueAccessor()](true);
		}
	};

	var editors = {};

	/**
	 * The wysiwyg binding makes the field a ckeditor wysiwyg
	 */
	ko.bindingHandlers.wysiwyg = {
		init: function (element, valueAccessor, allBindingsAccessor, context)
		{
			var options = valueAccessor(),
				value = ko.utils.unwrapObservable(options.value),
				$element = $(element),
				editor;

			value = value ? value : '';

			$element.html(value);

			if (options.id in editors)
				editor = editors[options.id];
			else
			{
				$element.ckeditor({
					language : language,
					readOnly : !adminData.edit_fields[context.field_name].editable
				});

				editor = $element.ckeditorGet();
				editors[options.id] = editor;
			}

			//when the editor is loaded, we want to resize our page
			editor.on('loaded', function()
			{
				setTimeout(function()
				{
					window.admin.resizePage();
				}, 50);

				editor.on('resize', function()
				{
					window.admin.resizePage();
				});
			});

			//wire up the blur event to ensure our observable is properly updated
			editor.focusManager.blur = function()
			{
				var observable = valueAccessor().value,
					$el = $('#' + options.id);

				//set the blur attribute to true so we know now to set the editor data in the update method
				$el.data('blur', true);

				observable($el.val());
			}

			//handle destroying an editor (based on what jQuery plugin does)
			ko.utils.domNodeDisposal.addDisposeCallback(element, function (test) {
				var editor = editors[options.id];

				if (editor)
				{
					editor.destroy();
					delete editors[options.id];
				}
			});
		},
		update: function (element, valueAccessor, allBindingsAccessor, context)
		{
			//handle programmatic updates to the observable
			var options = valueAccessor(),
				value = ko.utils.unwrapObservable(options.value),
				$element = $(element),
				editor = editors[options.id];

			value = value ? value : '';

			//if there isn't a value, set the value immediately
			if (!value)
			{
				$element.html(value);
				editor.setData(value);
			}
			//otherwise pause for a moment and then set it
			else
			{
				setTimeout(function()
				{
					$element.html(value);

					if ($element.data('blur'))
						$element.removeData('blur');
					else
						editor.setData(value);

				}, 50);
			}
		}
	};

	/**
	 * The markdown binding is attached to the field next a markdown textarea
	 */
	 ko.bindingHandlers.markdown = {
		update: function (element, valueAccessor, allBindingsAccessor, context)
		{
			//handle programmatic updates to the observable
			var value = ko.utils.unwrapObservable(valueAccessor());

			if (!value)
			{
				$(element).html(value);
			}
			else
			{
				$(element).html(markdown.toHTML(value.toString()));
			}
		}
	 };

	/**
	 * The enumText binding converts a value and an options array to a "Label (value)" readable format
	 */
	ko.bindingHandlers.enumText = {
		update: function (element, valueAccessor, allBindingsAccessor, viewModel)
		{
			var options = valueAccessor(),
				value = options.value,
				enumOptions = options.enumOptions;

			for (var i = 0; i < enumOptions.length; i++) {
				if(enumOptions[i].id == value) {
					$(element).html( enumOptions[i].text + " (" + value + ")" );
					return;
				}
			}

			$(element).html(value);
		}
	};

	/**
	 * File uploader using plupload
	 */
	ko.bindingHandlers.fileupload = {
		init: function(element, valueAccessor, allBindingsAccessor, viewModel, context)
		{
			var options = valueAccessor(),
				cacheName = options.field + '_uploader',
				viewModel = context.$root,
				filters = options.image ? [{title: 'Image files', extensions: 'jpg,jpeg,gif,png'}] : [];

			viewModel[cacheName] = new plupload.Uploader({
				runtimes: 'html5,flash,silverlight,gears,browserplus',
				browse_button: cacheName,
				container: 'edit_field_' + options.field,
				drop_element: cacheName,
				multi_selection: false,
				max_file_size: options.size_limit + 'mb',
				url: options.upload_url,
				flash_swf_url: asset_url + 'js/plupload/js/plupload.flash.swf',
				silverlight_xap_url: asset_url + 'js/plupload/js/plupload.silverlight.xap',
				filters: filters,
				multipart_params: {"_token" : window.csrf}
			});

			viewModel[cacheName].init();

			viewModel[cacheName].bind('FilesAdded', function(up, files) {

				viewModel.freezeActions(true);

				$(files).each(function(i, file) {
					//parent.uploader.removeFile(file);

				});

				options.upload_percentage(0);
				options.uploading(true);

				viewModel[cacheName].start();
			});

			viewModel[cacheName].bind('UploadProgress', function(up, file) {
				options.upload_percentage(file.percent);
			});

			viewModel[cacheName].bind('Error', function(up, err) {
				alert(err.message);
			});

			viewModel[cacheName].bind('FileUploaded', function(up, file, response) {
				var data = JSON.parse(response.response);

				options.uploading(false);

				if (data.errors.length === 0) {
					//success
					//iterate over the files until we find it and then set the proper fields
					viewModel[options.field](data.filename);
				} else {
					//error
					alert(data.errors);
				}

				setTimeout(function()
				{
					viewModel[cacheName].splice();
					viewModel[cacheName].refresh();
					$('div.plupload').css('z-index', 71);
					viewModel.freezeActions(false);
					admin.resizePage();
				}, 200);
			});

			$('#' + cacheName).bind('dragenter', function(e)
			{
				$(this).addClass('drag');
			});

			$('#' + cacheName).bind('dragleave drop', function(e)
			{
				$(this).removeClass('drag');
			});

			//destroy the existing editor if the DOM node is removed
			ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
				viewModel[cacheName].destroy();
			});
		},
		update: function(element, valueAccessor, allBindingsAccessor, viewModel, context)
		{
			var options = valueAccessor(),
				cacheName = options.field + '_uploader',
				viewModel = context.$root;

			//hack to get the z-index properly set up
			setTimeout(function()
			{
				viewModel[cacheName].refresh();
				$('div.plupload').css('z-index', 71);
			}, 200);
		}
	};

})(jQuery);
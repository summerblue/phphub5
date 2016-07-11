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
(function($)
{
	var admin = function()
	{
		return this.init();
	};

	//setting up csrf token
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': window.csrf
		}
	});

	admin.prototype = {

		//properties

		/*
		 * Main admin container
		 *
		 * @type jQuery object
		 */
		$container: null,

		/*
		 * The container for the datatable
		 *
		 * @type jQuery object
		 */
		$tableContainer: null,

		/*
		 * The data table
		 *
		 * @type jQuery object
		 */
		$dataTable: null,

		/*
		 * If this is true, the dataTable is scrollable instead of
		 * skipping columns at the end
		 *
		 * @type bool
		 */
		dataTableScrollable: false,

		/*
		 * The pixel points where the columns are hidden
		 *
		 * @type object
		 */
		columnHidePoints: {},

		/*
		 * If this is true, history.js has started
		 *
		 * @type bool
		 */
		historyStarted: false,

		/*
		 * Filters view model
		 */
		filtersViewModel: {

			/* The filters for the current result set
			 * array
			 */
			filters: [],

			/* The options lists for any fields
			 * object
			 */
			listOptions: {},

			/**
			 * The options for booleans
			 * array
			 */
			boolOptions: [{id: 'true', text: 'true'}, {id: 'false', text: 'false'}]
		},

		/*
		 * KO viewModel
		 */
		viewModel: {

			/*
			 * KO data model
			 */
			model: {},

			/*
			 * If this is true, all the values have been initialized and we can
			 *
			 * bool
			 */
			initialized: ko.observable(false),

			/* The model name for this data model
			 * string
			 */
			modelName: ko.observable(''),

			/* The model title for this data model
			 * string
			 */
			modelTitle: ko.observable(''),

			/* The sub title for this data model
			 * string
			 */
			subTitle: ko.observable(''),

			/* The title for single items of this model
			 * string
			 */
			modelSingle: ko.observable(''),

			/* The link (usually front-end) associated with this item
			 * string
			 */
			itemLink: ko.observable(null),

			/* The expand width of the edit area
			 * int
			 */
			expandWidth: ko.observable(null),

			/* The primary key value for this model
			 * string
			 */
			primaryKey: 'id',

			/* The rows of the current result set
			 * array
			 */
			rows: ko.observableArray(),

			/* The number of rows per page
			 * int
			 */
			rowsPerPage: ko.observable(20),

			/* The options (1-100 ...set up in init method) for the rows per page
			 * array
			 */
			rowsPerPageOptions: [],

			/* The columns for the current data model
			 * array
			 */
			columns: ko.observableArray(),

			/* The options lists for any fields
			 * object
			 */
			listOptions: {},

			/* The current sort options
			 * object
			 */
			sortOptions: {
				field: ko.observable(),
				direction: ko.observable()
			},

			/* The current pagination options
			 * object
			 */
			pagination: {
				page: ko.observable(),
				last: ko.observable(),
				total: ko.observable(),
				per_page: ko.observable(),
				isFirst: true,
				isLast: false,
			},

			/* The original edit fields array
			 * array
			 */
			originalEditFields: [],

			/* The original data when fetched from the server initially
			 * object
			 */
			originalData: {},

			/* The model edit fields
			 * array
			 */
			editFields: ko.observableArray(),

			/* The id of the active item. If it's null, there is no active item. If it's 0, the active item is new
			 * mixed (null, int)
			 */
			activeItem: ko.observable(null),

			/* The id of the last active item. This is set to null when an item is closed. 0 is new.
			 * mixed (null, int)
			 */
			lastItem: null,

			/* If this is set to true, the loading screen will be visible
			 * bool
			 */
			loadingItem: ko.observable(false),

			/* The id of the item currently being loaded
			 * int
			 */
			itemLoadingId: ko.observable(null),

			/* If this is set to true, the row loading screen will be visible
			 * bool
			 */
			loadingRows: ko.observable(false),

			/* The id of the rows currently being loaded
			 * int
			 */
			rowLoadingId: 0,

			/* If this is set to true, the form becomes uneditable
			 * bool
			 */
			freezeForm: ko.observable(false),

			/* If this is set to true, the action buttons on the form cannot be accessed
			 * bool
			 */
			freezeActions: ko.observable(false),

			/* If this is set to true, the relationship constraints won't update
			 * bool
			 */
			freezeConstraints: false,

			/* The current constraints queue
			 * object
			 */
			constraintsQueue: {},

			/* If this is set to true, the relationship constraints queue won't process
			 * bool
			 */
			holdConstraintsQueue: true,

			/* If custom actions are supplied, they are stored here
			 * array
			 */
			actions: ko.observableArray(),

			/* If custom global actions are supplied, they are stored here
			 * array
			 */
			globalActions: ko.observableArray(),

			/* Holds the per-action permissions
			 * object
			 */
			actionPermissions: {},

			/* The languages array holds text for the current language
			 * object
			 */
			languages: {},

			/* The status message and the type ('', 'success', 'error')
			 * strings
			 */
			statusMessage: ko.observable(''),
			statusMessageType: ko.observable(''),

			/* The global status message and the type ('', 'success', 'error')
			 * strings
			 */
			globalStatusMessage: ko.observable(''),
			globalStatusMessageType: ko.observable(''),

			/**
			 * Saves the item with the current settings. If id is 0, the server interprets it as a new item
			 */
			saveItem: function(norefresh)
			{
				var self = this,
					saveData = ko.mapping.toJS(self);

				saveData._token = csrf;

				//if this is a new item, delete the primary key from the data array
				if (!saveData[self.primaryKey])
					delete saveData[self.primaryKey];

				//iterate over the edit fields and ensure that the belongs_to relationships are false if they are an empty string
				$.each(self.editFields(), function(ind, field)
				{
					if (field.relationship && !field.external && saveData[field.field_name] === '')
					{
						saveData[field.field_name] = false;
					}
				});

				self.statusMessage(self.languages['saving']).statusMessageType('');
				self.freezeForm(true);

				$.ajax({
					url: base_url +  self.modelName() + '/' + self[self.primaryKey]() + '/save',
					data: saveData,
					dataType: 'json',
					type: 'POST',
					complete: function()
					{
						self.freezeForm(false);
						window.admin.resizePage();
					},
					success: function(response)
					{
						if (response.success) {
							self.statusMessage(self.languages['saved']).statusMessageType('success');
							self.updateRows();
							self.updateSelfRelationships();

							if (norefresh) return;

							self.setData(response.data);

							setTimeout(function()
							{
								History.pushState({modelName: self.modelName()}, document.title, route + self.modelName());
							}, 200);
						}
						else
							self.statusMessage(response.errors).statusMessageType('error');
					}
				});
			},

			/**
			 * Deletes the active item
			 */
			deleteItem: function(root, event, key)
			{
				var self = root;

				swal({
					title: '',
				    text: adminData.languages['delete_active_item'],
				    type: "warning",
				    showCancelButton: true,
				    confirmButtonColor: "#DD6B55",
				    cancelButtonText: adminData.languages['cancel'],
				    confirmButtonText: adminData.languages['delete'],
					showLoaderOnConfirm: true,
				    closeOnConfirm: false
				}, function() {
					var mykey = key ? key : self[self.primaryKey]();

					self.freezeForm(true);

					$.ajax({
						url: base_url + self.modelName() + '/' + mykey + '/delete',
						data: {_token: csrf},
						dataType: 'json',
						type: 'POST',
						complete: function()
						{
							self.freezeForm(false);
							window.admin.resizePage();
						},
						success: function(response)
						{
							if (response.success)
							{
								swal({
									title: adminData.languages['deleted'],
									text: "",
									type: "success",
									timer: 1000,
									showConfirmButton: false
								});

								self.updateRows();
								self.updateSelfRelationships();

								if (mykey == self[self.primaryKey]()) {
									setTimeout(function()
									{
										History.pushState({modelName: self.modelName()}, document.title, route + self.modelName());
										$('#sidebar').fadeIn();
									}, 500);
								}
							}
							else
								swal(response.error, "", "error");
						},
						error: function(response) {
							swal(adminData.languages['delete_failed'], "", "error");
						}
					});
				});
			},

			/**
			 * Deletes selected items
			 */
			deleteItems: function()
			{
				var self = this;
				var selected = [];

				$('.select-checkbox').each(function(i, el) {
					if ($(el).is(':checked')) {
						selected.push($(el).val());
					}
				});

				if (!selected.length) {
					swal('', adminData.languages['select_options'], "warning");
					return;
				}

				swal({
					title: '',
				    text: adminData.languages['delete_items'],
				    type: "warning",
				    showCancelButton: true,
				    confirmButtonColor: "#DD6B55",
				    cancelButtonText: adminData.languages['cancel'],
				    confirmButtonText: adminData.languages['delete'],
					showLoaderOnConfirm: true,
				    closeOnConfirm: false
				}, function() {
					var mykey = selected.join(',');

					self.freezeForm(true);

					$.ajax({
						url: base_url + self.modelName() + '/batch_delete',
						data: {_token: csrf, ids: mykey},
						dataType: 'json',
						type: 'POST',
						complete: function()
						{
							self.freezeForm(false);
							window.admin.resizePage();
						},
						success: function(response)
						{
							if (response.success)
							{
								swal({
									title: adminData.languages['deleted'],
									text: "",
									type: "success",
									timer: 1000,
									showConfirmButton: false
								});

								self.updateRows();
								self.updateSelfRelationships();

								setTimeout(function()
								{
									History.pushState({modelName: self.modelName()}, document.title, route + self.modelName());
									$('#sidebar').fadeIn();
									$('#select-all').prop('checked',false);
									$('#delete-all').addClass('disabled');
								}, 500);
							}
							else
								swal(response.error, "", "error");
						},
						error: function(response) {
							swal(adminData.languages['delete_failed'], "", "error");
						}
					});
				});
			},

			/**
			 * Callback for clicking an item
			 */
			clickItem: function(id)
			{
				if (!this.loadingItem() && this.activeItem() !== id && this.actionPermissions.view)
				{
					History.pushState({modelName: this.modelName(), id: id}, document.title, route + this.modelName() + '/' + id);
				}
			},

			/**
			 * Gets the active item in the grid
			 *
			 * @param int	id
			 */
			getItem: function(id)
			{
				var self = this;

				self.loadingItem(true);

				//override the edit fields to the original non-existent model
				adminData.edit_fields = self.originalEditFields;
				self.editFields(window.admin.prepareEditFields());

				//make sure constraints are only loaded once
				self.holdConstraintsQueue = true;

				//update all the info to the new item state
				ko.mapping.updateData(self, self.model, self.model);
				self.originalData = {};

				//scroll to the top of the page
				//$('html, body').animate({scrollTop: 0}, 'fast')

				//if this is a new item (id is falsy), just overwrite the viewModel with the original data model
				if (!id)
				{
					self.setUpNewItem();
					return;
				}

				//freeze the relationship constraint updates
				self.freezeConstraints = true;

				self.itemLoadingId(id);

				$.ajax({
					url: base_url + self.modelName() + '/' + id,
					dataType: 'json',
					success: function(data)
					{
						//if there was an error, kick out
						if (data.success === false && data.errors)
						{
							alert(data.errors);
							return;
						}

						if (self.itemLoadingId() !== id)
						{
							//if there are no currently-loading items, clear the form
							if (self.itemLoadingId() === null)
							{
								self.loadingItem(false);
								self.clearItem();
							}
						}
						else
							self.setData(data);
					}
				});
			},

			/**
			 * Sets the edit form up as a new item
			 */
			setUpNewItem: function()
			{
				this.itemLoadingId(null);
				this.activeItem(0);

				//set the last item property which helps manage the animation states
				this.lastItem = 0;

				var data = {};

				// 新建时加载 belongs_to_many 或 has_many 的默认值
				$.each(adminData.edit_fields, function(ind, el)
				{
					if(el.type == 'belongs_to_many' || el.type == 'has_many'){
						if(el.value){
							data[ind] = el.value;
						}
					}
				});
				ko.mapping.updateData(this, this.model, data);

				this.loadingItem(false);

				//run the constraints queue
				window.admin.runConstraintsQueue();
			},

			/**
			 * Overrides the data in the view model
			 *
			 * @param object	data
			 * @param
			 */
			setData: function(data)
			{
				var self = this;

				//set the active item and update the model data
				self.activeItem(data[self.primaryKey]);
				self.loadingItem(false);

				//update the edit fields
				adminData.edit_fields = data.administrator_edit_fields;
				self.editFields(window.admin.prepareEditFields());

				//update the actions and the action permissions
				self.actions(data.administrator_actions);
				self.actionPermissions = data.administrator_action_permissions;

				//set the original values
				self.originalData = data;

				//set the new options for relationships
				$.each(adminData.edit_fields, function(ind, el)
				{
					if (el.relationship && el.autocomplete)
					{
						self[el.field_name + '_autocomplete'] = data[el.field_name + '_autocomplete'];
					}
				});

				//set the item link if it exists
				if (data.admin_item_link)
				{
					self.itemLink(data.admin_item_link);
				}

				//set the last item property which helps manage the animation states
				self.lastItem = data[self.primaryKey];

				//fixes an error where the relationships wouldn't load
				setTimeout(function()
				{
					//first clear the data
					ko.mapping.updateData(self, self.model, self.model);

					//then update the data
					ko.mapping.updateData(self, self.model, data);

					//unfreeze the relationship constraint updates
					self.freezeConstraints = false;

					window.admin.resizePage();

					//run the constraints queue
					window.admin.runConstraintsQueue();
				}, 50);
			},

			/**
			 * Closes the item edit/create window
			 */
			closeItem: function()
			{
				History.pushState({modelName: this.modelName()}, document.title, route + this.modelName());
				$('#sidebar').fadeIn();
			},

			/**
			 * Clears the current item
			 */
			clearItem: function()
			{
				this.freezeForm(false);
				this.statusMessage('');
				this.statusMessageType('');
				this.itemLink(null);
				this.itemLoadingId(null);
				this.activeItem(null);
				this.lastItem = null;
			},

			/**
			 * Opens the create item form
			 */
			addNewItem: function()
			{
				//$('#users_list').resetSelection();
				this.getItem(0);
			},

			/**
			 * Performs a custom action on an item or the whole model
			 *
			 * @param bool		isItem
			 * @param string	action
			 * @param object	messages
			 * @param string	confirmation
			 */
			customAction: function(isItem, action, messages, confirmation)
			{
				var self = this,
					data = {_token: csrf, action_name: action},
					url;

				//if a confirmation string was supplied, flash it in a confirm()
				if (confirmation)
				{
					if (!confirm(confirmation))
						return false;
				}

				//if this is an item action (compared to a global model action), set the proper url
				if (isItem)
				{
					url = base_url + self.modelName() + '/' + self[self.primaryKey]() + '/custom_action';
					self.statusMessage(messages.active).statusMessageType('');
				}
				//otherwise set the url and add the filters
				else
				{
					url = base_url + self.modelName() + '/custom_action';
					data.sortOptions = self.sortOptions;
					data.filters = self.getFilters();
					data.page = self.pagination.page();
					self.globalStatusMessage(messages.active).globalStatusMessageType('');
				}

				self.freezeForm(true);

				$.ajax({
					url: url,
					data: data,
					dataType: 'json',
					type: 'POST',
					complete: function()
					{
						self.freezeForm(false);
					},
					success: function(response)
					{
						if (response.success)
						{
							if (isItem)
							{
								self.statusMessage(messages.success).statusMessageType('success');
								self.setData(response.data);
							}
							else
							{
								self.globalStatusMessage(messages.success).globalStatusMessageType('success');
							}

							// if this is a redirect, redirect the user to the supplied url
							if (response.redirect)
								window.location.href = response.redirect;

							//if there was a file download initiated, redirect the user to the file download address
							if (response.download)
								self.downloadFile(response.download);

							self.updateRows();
						}
						else
						{
							if (isItem)
								self.statusMessage(response.error).statusMessageType('error');
							else
								self.globalStatusMessage(response.error).globalStatusMessageType('error');
						}
					}
				});
			},

			/**
			 * Initiates a file download
			 *
			 * @param string	url
			 */
			downloadFile: function(url)
			{
				var hiddenIFrameId = 'hiddenDownloader',
					iframe = document.getElementById(hiddenIFrameId);

				if (iframe === null)
				{
					iframe = document.createElement('iframe');
					iframe.id = hiddenIFrameId;
					iframe.style.display = 'none';
					document.body.appendChild(iframe);
				}

				iframe.src = url;
			},

			/**
			 * Updates the rows given the data model's current state. Set sort, filters, and anything else before you call this.
			 * Calling this locks the results table.
			 *
			 * @param object	data
			 */
			updateRows: function()
			{
				var self = this,
					id = ++self.rowLoadingId,
					data = {
						_token: csrf,
						sortOptions: self.sortOptions,
						filters: self.getFilters(),
						page: self.pagination.page(),
						// hack by @Monkey: for paging logic
						filter_by: self.filter_by,
						filter_by_id: self.filter_by_id
					};

				//if the page hasn't been initialized yet, don't update the rows
				if (!this.initialized())
					return;

				//if we're on page 0 (i.e. there is currently no result set, set the page to 1)
				if (!data.page)
					data.page = 1;

				//set loadingRows to true so that the loading box comes up
				self.loadingRows(true);

				$.ajax({
					url: base_url + self.modelName() + '/results',
					type: 'POST',
					dataType: 'json',
					data: data,
					success: function(response)
					{
						//if the row loading id has changed, that means it's old...so don't use this data
						if (self.rowLoadingId !== id)
						{
							return;
						}

						//otherwise the rows aren't loading anymore and we can replace the data
						self.pagination.page(response.last ? response.page : response.last);
						self.pagination.last(response.last);
						self.pagination.total(response.total);
						self.rows(response.results);
						self.loadingRows(false);
					}
				});
			},

			/**
			 * Updates the sort options when a column header is clicked
			 *
			 * @param string	field
			 */
			setSortOptions: function(field)
			{
				//check if the field is a valid column
				var found = false;

				//iterate over the columns to check if it's a valid sort_field or field
				$.each(this.columns(), function(i, col)
				{
					if (field === col.sort_field || field === col.column_name)
					{
						found = true;
						return false;
					}
				})

				if (!found)
					return false;

				//the direction depends on the field
				if (field == this.sortOptions.field())
				//reverse the direction
					this.sortOptions.direction( (this.sortOptions.direction() == 'asc') ? 'desc' : 'asc' );
				else
				//set the direction to asc
					this.sortOptions.direction('asc');

				//update the field
				this.sortOptions.field(field);

				//update the rows
				this.updateRows();
			},

			/**
			 * Goes to the specified page
			 *
			 * @param string|int	page
			 */
			page: function(page)
			{
				var currPage = parseInt(this.pagination.page()),
					newPage = 1,
					lastPage = parseInt(this.pagination.last());

				//if the value is 'prev' or 'next', increment or decrement
				if (page === 'prev')
				{
					if (currPage > 1)
					{
						newPage = currPage - 1;
					}
				}
				else if (page === 'next')
				{
					if (currPage < lastPage)
					{
						newPage = currPage + 1;
					}
					else
					{
						newPage = lastPage;
					}
				}
				else if (!isNaN(parseInt(page)))
				{
					//set the page to the supplied value
					if (page > lastPage)
					{
						newPage = lastPage;
					}
					else
					{
						newPage = page;
					}
				}

				this.pagination.page(newPage);

				//update the rows
				this.updateRows();
			},

			/**
			 * Updates the rows per page for this model when the item is changed
			 *
			 * @param int
			 */
			updateRowsPerPage: function(rows)
			{
				var self = this;

				$.ajax({
					url: rows_per_page_url,
					data: {_token: csrf, rows: rows},
					dataType: 'json',
					type: 'POST',
					complete: function()
					{
						self.updateRows();
					}
				});
			},

			/**
			 * Gets a minimized filters array that can be sent to the server
			 */
			getFilters: function()
			{
				var filters = [],
					observables = ['value', 'min_value', 'max_value'];

				$.each(window.admin.filtersViewModel.filters, function(ind, el)
				{
					var filter = {
						field_name: el.field_name,
						type: el.type,
						value: el.value() ? el.value() : null,
					};

					//iterate over the observables to see if we should include them
					$(observables).each(function(i, obs)
					{
						if (this in el)
						{
							filter[this] = el[this]() ? el[this]() : null;

							if (obs === 'value' && filter[this] && el.type === 'belongs_to_many' && typeof filter[this] === 'string')
							{
								filter.value = filter.value.split(',');
							}
						}
					});

					//push this filter onto the filters array
					filters.push(filter);
				});

				return filters;
			},

			/**
			 * Determines if the provided field is dirty
			 *
			 * @param string
			 *
			 * @return bool
			 */
			fieldIsDirty: function(field)
			{
				return this.originalData[field] != this[field]();
			},

			/**
			 * Updates any self-relationships
			 */
			updateSelfRelationships: function()
			{
				var self = this;

				//first we will iterate over the filters and update them if any exist
				$.each(window.admin.filtersViewModel.filters, function(ind, filter)
				{
					var fieldIndex = ind,
						fieldName = filter.field_name;

					if ((!filter.constraints || !filter.constraints.length) && filter.self_relationship)
					{
						window.admin.filtersViewModel.filters[fieldIndex].loadingOptions(true);

						$.ajax({
							url: base_url + self.modelName() + '/update_options',
							type: 'POST',
							dataType: 'json',
							data: {fields: [{
								type: 'filter',
								field: fieldName,
								selectedItems: filter.value()
							}]},
							complete: function()
							{
								window.admin.filtersViewModel.filters[fieldIndex].loadingOptions(false);
							},
							success: function(response)
							{
								//update the options
								window.admin.filtersViewModel.listOptions[fieldName](response[fieldName]);
							}
						});

					}
				});

				//then we'll update the edit fields
				$.each(self.editFields(), function(ind, field)
				{
					var fieldName = field.field_name;

					//if there are no constraints for this field and if it is a self-relationship, update the options
					if ((!field.constraints || !field.constraints.length) && field.self_relationship)
					{
						field.loadingOptions(true);

						$.ajax({
							url: base_url + self.modelName() + '/update_options',
							type: 'POST',
							dataType: 'json',
							data: {fields: [{
								type: 'edit',
								field: fieldName,
								selectedItems: self[fieldName]()
							}]},
							complete: function()
							{
								field.loadingOptions(false);
							},
							success: function(response)
							{
								//update the options
								self.listOptions[fieldName] = response[fieldName];
							}
						});

					}
				});
			}
		},



		//methods

		/**
		 * Init method
		 */
		init: function()
		{
			var self = this;

			//set up the basic pieces of data
			this.viewModel.model = adminData.data_model;
			this.$container = $('#admin_content');

			var viewModel = ko.mapping.fromJS(this.viewModel.model);

			$.extend(this.viewModel, viewModel);

			this.viewModel.rows(adminData.rows.results);
			this.viewModel.pagination.page(adminData.rows.page);
			this.viewModel.pagination.last(adminData.rows.last);
			this.viewModel.pagination.total(adminData.rows.total);
			this.viewModel.sortOptions.field(adminData.sortOptions.field);
			this.viewModel.sortOptions.direction(adminData.sortOptions.direction);
			this.viewModel.columns(this.prepareColumns());
			this.viewModel.modelName(adminData.model_name);
			this.viewModel.modelTitle(adminData.model_title);
			this.viewModel.subTitle(adminData.sub_title);
			this.viewModel.modelSingle(adminData.model_single);
			this.viewModel.expandWidth(adminData.expand_width);
			this.viewModel.rowsPerPage(adminData.rows_per_page);
			this.viewModel.primaryKey = adminData.primary_key;
			this.viewModel.actions(adminData.actions);
			this.viewModel.globalActions(adminData.global_actions);
			this.viewModel.actionPermissions = adminData.action_permissions;
			this.viewModel.languages = adminData.languages;
			// hack by @Monkey: for paging logic
			this.viewModel.filter_by = adminData.filter_by;
			this.viewModel.filter_by_id = adminData.filter_by_id;

			//set up the rowsPerPageOptions
			var perPageArr = [20, 50, 100, 200, 500, 1000, 2000, 5000, 8000, 10000];
			for (var i = 0; i < perPageArr.length; i++)
			{
				this.viewModel.rowsPerPageOptions.push({id: perPageArr[i], text: perPageArr[i] + ''});
			}

			//now that we have most of our data, we can set up the computed values
			this.initComputed();

			//prepare the filters
			this.filtersViewModel.filters = this.prepareFilters();

			//prepare the edit fields
			this.viewModel.originalEditFields = adminData.edit_fields;
			this.viewModel.editFields(this.prepareEditFields());

			//set up the relationships
			this.initRelationships();

			//set up the KO bindings
			ko.applyBindings(this.viewModel, $('#content')[0]);
			ko.applyBindings(this.filtersViewModel, $('#filters_sidebar_section')[0]);

			//set up pushstate history
			this.initHistory();

			//set up the subscriptions
			this.initSubscriptions();

			//set up the events
			this.initEvents();

			//run an initial page resize
			this.resizePage();

			//finally run a timer to overcome bugs with select2
			setTimeout(function()
			{
				self.viewModel.initialized(true);

			}, 1000);

			return this;
		},

		/**
		 * Prepare the filters
		 *
		 * @return array with value observables
		 */
		prepareFilters: function()
		{
			var filters = [];

			$.each(adminData.filters, function(ind, filter)
			{
				var observables = ['value', 'min_value', 'max_value'];

				//iterate over the desired observables and check if they're there. if so, assign them an observable slot
				$.each(observables, function(i, obs)
				{
					if (obs in filter)
					{
						filter[obs] = ko.observable(filter[obs]);
					}
				});

				//if this is a relationship field, we want to set up the loading options observable
				if (filter.relationship)
				{
					filter.loadingOptions = ko.observable(false);
				}

				filter.field_id = 'filter_field_' + filter.field_name;

				filters.push(filter);
			});

			return filters;
		},

		/**
		 * Prepare the edit fields
		 *
		 * @return object with loadingOptions observables
		 */
		prepareEditFields: function()
		{
			var self = this,
				fields = [];

			$.each(adminData.edit_fields, function(ind, field)
			{
				//if this is a relationship field, set up the loadingOptions observable
				if (field.relationship)
				{
					field.loadingOptions = ko.observable(false);
					field.constraintLoading = ko.observable(false);
				}

				//if this is an image field, set the upload params
				if (field.type === 'image' || field.type === 'file')
				{
					field.uploading = ko.observable(false);
					field.upload_percentage = ko.observable(0);
				}

				//add the id field
				field.field_id = 'edit_field_' + ind;

				fields.push(field);
			});

			return fields;
		},

		/**
		 * Sets up the column model with various observable values
		 *
		 * @return array
		 */
		prepareColumns: function()
		{
			var self = this,
				columns = [];

			$.each(adminData.column_model, function(ind, column)
			{
				column.visible = ko.observable(column.visible);
				columns.push(column);
			});

			return columns;
		},

		/**
		 * Set up the relationship items
		 */
		initRelationships: function()
		{
			var self = this;

			//set up the filters
			$.each(adminData.filters, function(ind, el)
			{
				if (el.relationship)
					self.filtersViewModel.listOptions[ind] = ko.observableArray(el.options);
			});

			//set up the edit fields
			$.each(adminData.edit_fields, function(ind, el)
			{
				if (el.relationship)
					self.viewModel.listOptions[ind] = el.options;

				// add any loaded option to the autocomplete array
				if (el.autocomplete)
				{
					if(! (el.field_name + '_autocomplete' in self.viewModel) )
						self.viewModel[el.field_name + '_autocomplete'] = [];
					$.each(el.options, function(x, option)
					{
						self.viewModel[el.field_name + '_autocomplete'][option.id] = option;
					});
				}
			});
		},

		/**
		 * Inits the KO subscriptions
		 */
		initSubscriptions: function()
		{
			var self = this,
				runFilter = function(val)
				{
					self.viewModel.updateRows();
				};

			//iterate over filters
			$.each(self.filtersViewModel.filters, function(ind, filter)
			{
				//subscribe to the value field
				self.filtersViewModel.filters[ind].value.subscribe(function(val)
				{
					//if this is an id field, make sure it's an integer
					if (self.filtersViewModel.filters[ind].type === 'key')
					{
						var intVal = isNaN(parseInt(val)) ? '' : parseInt(val);

						self.filtersViewModel.filters[ind].value(intVal);
					}

					//update the rows now that we've got new filters
					self.viewModel.updateRows();
				});

				//check if there's a min and max value. if so, subscribe to those as well
				if ('min_value' in filter)
				{
					self.filtersViewModel.filters[ind].min_value.subscribe(runFilter);
				}
				if ('max_value' in filter)
				{
					self.filtersViewModel.filters[ind].max_value.subscribe(runFilter);
				}
			});

			//iterate over the edit fields
			$.each(self.viewModel.editFields(), function(ind, field)
			{
				//if there are constraints to maintain, set up the subscriptions
				if (field.constraints && self.getObjectSize(field.constraints))
				{
					self.establishFieldConstraints(field);
				}
			});

			//subscribe to page change
			self.viewModel.pagination.page.subscribe(function(val)
			{
				self.viewModel.page(val);
			});

			//subscribe to rows per page change
			self.viewModel.rowsPerPage.subscribe(function(val)
			{
				self.viewModel.updateRowsPerPage(parseInt(val));
			});
		},

		/**
		 * Establish constraints
		 *
		 * @param object	field
		 */
		establishFieldConstraints: function(field)
		{
			var self = this;

			//we want to subscribe to changes on the OTHER fields since that's what defines changes to this one
			$.each(field.constraints, function(key, relationshipName)
			{
				var fieldName = field.field_name,
					f = field,
					constraintsLength = self.getFieldConstraintsLength(key);

				self.viewModel[key].subscribe(function(val)
				{
					if (self.viewModel.freezeConstraints || f.loadingOptions())
						return;

					//if this key hasn't been set up yet, set it
					if (!self.viewModel.constraintsQueue[key])
						self.viewModel.constraintsQueue[key] = {};

					//add the constraint to the queue
					self.viewModel.constraintsQueue[key][fieldName] = f;

					var currentQueueLength = Object.keys(self.viewModel.constraintsQueue[key]).length;

					if (!self.viewModel.holdConstraintsQueue && (currentQueueLength === constraintsLength))
						self.runConstraintsQueue();
				});
			});
		},

		/**
		 * Sets the constrainer's constraintLoading field to true
		 *
		 * @param string	key
		 *
		 * @return int
		 */
		getFieldConstraintsLength: function(key)
		{
			var length = 0;

			//iterate over the edit fields until we find our match
			$.each(this.viewModel.editFields(), function(ind, field)
			{
				if (field.constraints && field.constraints[key])
				{
					length++;
				}
			});

			return length;
		},

		/**
		 * Sets the constrainer's constraintLoading field to true
		 *
		 * @param string	key
		 * @param bool		freeze
		 */
		setConstrainerFreeze: function(key, freeze)
		{
			//iterate over the edit fields until we find our match
			$.each(this.viewModel.editFields(), function(ind, field)
			{
				if (field.field_name === key)
				{
					field.constraintLoading(freeze);
					return false;
				}
			});
		},

		/**
		 * Sets a field's loadingOptions
		 *
		 * @param string	fieldName
		 * @param bool		type
		 */
		setFieldLoadingOptions: function(fieldName, type)
		{
			//iterate over the edit fields until we find our match
			$.each(this.viewModel.editFields(), function(ind, field)
			{
				if (field.field_name === fieldName)
				{
					field.loadingOptions(type);
					return false;
				}
			});
		},

		/**
		 * Runs the constraints queue
		 */
		runConstraintsQueue: function()
		{
			var self = this,
				fields = self.buildConstraintsFromQueue();

			//if there are no fields, exit out
			if (!fields.length)
				return;

			//freeze the actions
			self.viewModel.freezeActions(true);

			$.ajax({
				url: base_url + self.viewModel.modelName() + '/update_options',
				type: 'POST',
				dataType: 'json',
				data: {
					fields: fields
				},
				complete: function()
				{
					self.viewModel.freezeActions(false);

					$.each(self.viewModel.constraintsQueue, function(key, fieldConstraints)
					{
						$.each(fieldConstraints, function(fieldName, field)
						{
							self.setFieldLoadingOptions(fieldName, false);
							self.setConstrainerFreeze(key, false);
						});
					});

					//clear the constraints queue
					self.viewModel.constraintsQueue = {};
					self.viewModel.holdConstraintsQueue = false;
				},
				success: function(response)
				{
					//iterate over the results and put them in the autocomplete array
					$.each(response, function(fieldName, el)
					{
						var data = {};

						$.each(el, function(i, e)
						{
							data[e.id] = e;
						});

						self.viewModel[fieldName + '_autocomplete'] = data;

						//update the options
						self.viewModel.listOptions[fieldName] = el;
					});
				}
			});
		},

		/**
		 * Prepares the constraints for the queue job
		 */
		buildConstraintsFromQueue: function()
		{
			var self = this,
				allConstraints = [];

			$.each(self.viewModel.constraintsQueue, function(key, fieldConstraints)
			{
				$.each(fieldConstraints, function(fieldName, field)
				{
					var constraints = {};

					//set the field to loading and freeze the constrainer
					self.setFieldLoadingOptions(fieldName, true);
					self.setConstrainerFreeze(key, true);

					//iterate over this field's constraints
					$.each(field.constraints, function(key, relationshipName)
					{
						constraints[key] = self.viewModel[key]();
					});

					allConstraints.push({
						constraints: constraints,
						type: 'edit',
						field: fieldName,
						selectedItems: self.viewModel[fieldName]()
					});
				});
			});

			return allConstraints;
		},

		/**
		 * Inits the page events
		 */
		initEvents: function()
		{
			var self = this;

			//clicking the new item button
			$('#content').on('click', 'div.results_header a.new_item', function(e)
			{
				e.preventDefault();
				History.pushState({modelName: self.viewModel.modelName(), id: 0}, document.title, route + self.viewModel.modelName() + '/new');
			});

			//resizing the window
			$(window).resize(self.resizePage);

			//mousedowning or keypressing anywhere should resize the page as well
			$('body').on('mouseup keypress', self.resizePage);

			//set up the history event callback
			History.Adapter.bind(window,'statechange',function() {
				var state = History.getState();

				//if the ignore key is true, or if this is the inital state, exit out.
				if (state.data.ignore || (state.data.init && !self.historyStarted))
					return;


				//if the model name is present
				if ('modelName' in state.data)
				//if that model name isn't the current model name, we are updating the model
					if (state.data.modelName !== self.viewModel.modelName())
					//get the new model
						self.viewModel.getNewModel(state.data);

				//if the state data has an id field and if it's not the active item
				if ('id' in state.data)
				{
					//get the new item (this includes when state.data.id === 0, which means it should be a new item)
					if (state.data.id !== self.viewModel.activeItem())
						self.viewModel.getItem(state.data.id);
				}
				else
				{
					//otherwise, assume that the user wants to be taken back to the results page. close the form
					self.viewModel.clearItem();
				}
			});
		},

		/**
		 * Sets up the push state's initial state
		 */
		initHistory: function()
		{
			var historyData = {
					modelName: this.viewModel.modelName(),
					init: true
				},
				uri = route + this.viewModel.modelName();

			//if the admin data had an id supplied, it means this is either the edit page or the new item page
			if ('id' in adminData)
			{
				//if the view model hasn't been set up yet, wait for it to be set up
				var timer = setInterval(function()
				{
					if (window.admin)
					{
						window.admin.viewModel.getItem(adminData.id);
						historyData.id = adminData.id;
						uri += '/' + (historyData.id ? historyData.id : 'new');

						//now call the same to trigger the statechange event
						History.pushState(historyData, document.title, uri);

						clearInterval(timer);
					}
				}, 100);
			}

			this.historyStarted = true;
		},

		/**
		 * Initializes the computed observables
		 */
		initComputed: function()
		{
			//pagination information
			this.viewModel.pagination.isFirst = ko.computed(function()
			{
				return this.pagination.page() == 1;
			}, this.viewModel);

			this.viewModel.pagination.isLast = ko.computed(function()
			{
				return this.pagination.page() == this.pagination.last();
			}, this.viewModel);

		},

		/**
		 * Helper to get an object's size
		 *
		 * @param object
		 *
		 * @return int
		 */
		getObjectSize: function(obj)
		{
			var size = 0, key;

			for (key in obj)
			{
				if (obj.hasOwnProperty(key)) size++;
			}

			return size;
		},

		/**
		 * Handles a window resize to make sure the admin area is always
		 */
		resizePage: function()
		{
			setTimeout(function()
			{
				var winHeight = $(window).height(),
					itemEditHeight = $('div.item_edit').outerHeight() + 50,
					usedHeight = winHeight > itemEditHeight ? winHeight - 45 : itemEditHeight,
					size = window.getComputedStyle(document.body, ':after').getPropertyValue('content');

				//resize the page height
				$('#admin_page').css({minHeight: usedHeight+45});

				//resize or scroll the data table
				if (window.admin) {
					if (! window.admin.dataTableScrollable)
						window.admin.resizeDataTable();
					else
						window.admin.scrollDataTable();
				}


				// Popover with html
	            $('.popover-with-html').popover({
					 html : true,
					//  trigger : 'click hover',
					 trigger : 'manual',
                     container: 'body',
					 placement: 'top',
					 delay: {show: 50, hide: 400},
					 content: function () {
					 	return $(this).attr('hint');
					 }
				 }).on("mouseenter", function () {
                    var _this = this;
                    $(this).popover("show");
                    $(".popover").on("mouseleave", function () {
                        $(_this).popover('hide');
                    });
                }).on("mouseleave", function () {
                    var _this = this;
                    setTimeout(function () {
                        if (!$(".popover:hover").length) {
                            $(_this).popover("hide");
                        }
                    }, 400);
                });

			}, 50);
		},

		/**
		 * Allows to scroll wide data tables (alternative to resizeDataTable)
		 */
		scrollDataTable: function()
		{
			if (!self.$tableContainer)
			{
				self.$tableContainer = $('div.table_container');
				self.$dataTable = self.$tableContainer.find('table.results');
			}

			// exit if table is already wrapped
			if (self.$dataTable.parent().hasClass('table_scrollable')) return true;

			// wrap table within div.table_scrollable
			self.$dataTable.wrap('<div class="table_scrollable"></div>')
		},

		/**
		 * Hides columns until the table container is at least as wide as the data table
		 */
		resizeDataTable: function()
		{
			var self = window.admin,
				winWidth = $(window).width();

			if (!self.$tableContainer)
			{
				self.$tableContainer = $('div.table_container');
				self.$dataTable = self.$tableContainer.find('table.results');
			}

			//grab the columns
			var columns = self.viewModel.columns();

			//iterate over the column hide points to see if we should unhide any of them
			$.each(self.columnHidePoints, function(i, el)
			{
				if (el < winWidth)
					columns[i].visible(true);
			});

			//walk backwards over the columns to determine which ones to hide
			for (var i = columns.length - 1; i >= 2; i--)
			{
				//if the datatable is visible and the table is large than its container
				if (columns.length >= 2 && self.$dataTable.is(':visible') && (self.$tableContainer.width() < self.$dataTable.width()) )
				{
					//we don't want to hide all the columns
					if (i <= 1)
						return;
					if (columns[i].visible())
					{
						columns[i].visible(false);
						self.columnHidePoints[i] = winWidth;
						break;
					}
				}
			}
		}
	};
	//set up the admin instance
	$(function() {
		if ($('#admin_page').length) {
			window.admin = new admin();
		}

	    // 二维码
	    var qrcode = new QRCode(document.getElementById('qrcode-img'), {
	        text: 'http://tianyinzaixian.com',
	        width: 320,
	        height: 320
	    });

    	// $('#qrcode-img').attr('title', '')
		$(document).on( 'click', '.get-qrcode-btn', function(e)
		{
			e.preventDefault();

			// 重新生成二维码
			qrcode.clear(); // clear the code.
			qrcode.makeCode($(this).attr('data-link')); // make another code.

			$('#getQrcode').modal('show');

		});

		// select all items
		$('#select-all').on('click', function() {
			var checked = false;

			if ($(this).is(':checked')) {
				$('.select-checkbox').prop('checked', true);
				checked = true;
			} else {
				$('.select-checkbox').prop('checked', false);
			}

			if (checked && $('.select-checkbox').length) {
				$('#delete-all').removeClass('disabled');
			} else {
				$('#delete-all').addClass('disabled');
			}
		});

		// disable delete-all btn
		$('.select-checkbox').on('click', function() {
			var selected = 0;

			$('.select-checkbox').each(function(i, el) {
				if ($(el).is(':checked')) {
					selected++;
				}
			});

			if (selected > 0) {
				$('#delete-all').removeClass('disabled');
			} else {
				$('#delete-all').addClass('disabled');
			}
		});

		$('[data-toggle="tooltip"]').tooltip();
	});
})(jQuery);

(function($)
{
	var admin = function()
	{
		return this.init();
	};

	//setting up csrf token
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': window.csrf
		}
	});

	admin.prototype = {

		//properties

		/*
		 * Main admin container
		 *
		 * @type jQuery object
		 */
		$container: null,


		/*
		 * KO viewModel
		 */
		viewModel: {


			/* The settings name
			 * string
			 */
			settingsName: ko.observable(''),

			/* The settings title
			 * string
			 */
			settingsTitle: ko.observable(''),

			/* The model edit fields
			 * array
			 */
			editFields: ko.observableArray(),

			/* If this is set to true, the form becomes uneditable
			 * bool
			 */
			freezeForm: ko.observable(false),

			/* If this is set to true, the action buttons on the form cannot be accessed
			 * bool
			 */
			freezeActions: ko.observable(false),

			/* If custom actions are supplied, they are stored here
			 * array
			 */
			actions: ko.observableArray(),

			/* The languages array holds text for the current language
			 * object
			 */
			languages: {},

			/* The status message and the type ('', 'success', 'error')
			 * strings
			 */
			statusMessage: ko.observable(''),
			statusMessageType: ko.observable(''),

			/**
			 * Saves the item with the current settings
			 */
			save: function()
			{
				var self = this,
					saveData = ko.mapping.toJS(self);

				saveData._token = csrf;

				self.statusMessage(self.languages['saving']).statusMessageType('');
				self.freezeForm(true);

				$.ajax({
					url: save_url,
					data: saveData,
					dataType: 'json',
					type: 'POST',
					complete: function()
					{
						self.freezeForm(false);
					},
					success: function(response)
					{
						if (response.success)
						{
							self.statusMessage(self.languages['saved']).statusMessageType('success');

							//update the model
							self.updateData(response.data);

							//update the custom actions
							self.actions(response.actions);
						}
						else
							self.statusMessage(response.errors).statusMessageType('error');
					}
				});
			},

			/**
			 * Performs a custom action
			 *
			 * @param string	action
			 * @param object	messages
			 * @param string	confirmation
			 */
			customAction: function(action, messages, confirmation)
			{
				var self = this;

				//if a confirmation string was supplied, flash it in a confirm()
				if (confirmation)
				{
					if (!confirm(confirmation))
						return false;
				}

				self.statusMessage(messages.active).statusMessageType('');
				self.freezeForm(true);

				$.ajax({
					url: custom_action_url,
					data: {_token: csrf, action_name: action},
					dataType: 'json',
					type: 'POST',
					complete: function()
					{
						self.freezeForm(false);
					},
					success: function(response)
					{
						if (response.success)
						{
							self.statusMessage(messages.success).statusMessageType('success');

							//update the custom actions
							self.actions(response.actions);

							// if this is a redirect, redirect the user to the supplied url
							if (response.redirect)
								window.location.href = response.redirect;

							//if there was a file download initiated, redirect the user to the file download address
							if (response.download)
								self.downloadFile(response.download);
						}
						else
							self.statusMessage(response.error).statusMessageType('error');
					}
				});
			},

			/**
			 * Initiates a file download
			 *
			 * @param string	url
			 */
			downloadFile: function(url)
			{
				var hiddenIFrameId = 'hiddenDownloader',
					iframe = document.getElementById(hiddenIFrameId);

				if (iframe === null)
				{
					iframe = document.createElement('iframe');
					iframe.id = hiddenIFrameId;
					iframe.style.display = 'none';
					document.body.appendChild(iframe);
				}

				iframe.src = url;
			},

			/**
			 * Updates the view model data
			 *
			 * @param array		data
			 */
			updateData: function(data)
			{
				var self = this;

				//iterate over the data and find the associated observable
				$.each(data, function(i, el)
				{
					self[i](el);
				});
			}

		},



		//methods

		/**
		 * Init method
		 */
		init: function()
		{
			//set up the basic pieces of data
			this.$container = $('#admin_content');

			var viewModel = ko.mapping.fromJS(adminData.data);

			$.extend(this.viewModel, viewModel);

			this.viewModel.settingsName(adminData.name);
			this.viewModel.settingsTitle(adminData.title);
			this.viewModel.actions(adminData.actions);
			this.viewModel.languages = adminData.languages;

			//now that we have most of our data, we can set up the computed values
			this.initComputed();

			//prepare the edit fields
			this.viewModel.editFields = this.prepareEditFields();

			//set up the KO bindings
			ko.applyBindings(this.viewModel, $('#main_content')[0]);

			//set up the subscriptions
			this.initSubscriptions();

			//set up the events
			this.initEvents();

			return this;
		},

		/**
		 * Prepare the edit fields
		 *
		 * @return object with loadingOptions observables
		 */
		prepareEditFields: function()
		{
			var self = this,
				fields = [];

			$.each(adminData.edit_fields, function(ind, field)
			{
				//if this is an image field, set the upload params
				if (field.type === 'image' || field.type === 'file')
				{
					field.uploading = ko.observable(false);
					field.upload_percentage = ko.observable(0);
				}

				//add the id field
				field.field_id = 'edit_field_' + ind;

				fields.push(field);
			});

			return fields;
		},

		/**
		 * Inits the KO subscriptions
		 */
		initSubscriptions: function()
		{
			var self = this;

		},

		/**
		 * Inits the page events
		 */
		initEvents: function()
		{
			var self = this;

		},

		/**
		 * Initializes the computed observables
		 */
		initComputed: function()
		{

		},

		/**
		 * Handles a window resize
		 */
		resizePage: function()
		{

		}
	};


	//set up the admin instance
	$(function() {
		if ($('#settings_page').length)
			window.admin = new admin();
	});
})(jQuery);
(function($)
{
	var $menu, $mobileMenu, $menuButton, $filterButton, $content;

	//dom ready
	$(function()
	{
		$menu = $('ul#menu, ul#lang_menu');
		$mobileMenu = $('#mobile_menu_wrapper');
		$menuButton = $('a#menu_button');
		$filterButton = $('a#filter_button');
		$filters = $('#sidebar');
		$content = $('#content');

		//set the menu hover and hoverout states
		$menu.find('li.menu').each(function()
		{
			var $this = $(this),
				$submenu = $this.children('ul');

			//bind events for the top-level menu item
			$this.bind({
				mouseenter: function()
				{
					clearTimeout($this.data('timer'));
					$this.addClass('current');
				},
				mouseleave: function()
				{
					$this.data('timer', setTimeout(function()
					{
						$submenu.fadeOut(150);
						$this.removeClass('current');
					}, 150));
				}
			});

			//make the submenu slide down on hover
			$this.hover(function()
			{
				//if this is a sub-submenu, slide it right instead of down
				if ($this.parent().closest('li.menu').length)
				{
					$this.addClass('current');
					$submenu.stop(true, true).show('slide', { direction: 'left' }, 200);
				}
				else
					$submenu.stop(true, true).slideDown(200);
			});
		});

		toggleMenu = function(toggle)
		{
			$menuButton.toggleClass('current', toggle);

			if (toggle)
				$mobileMenu.stop(true, true).show('slide', { direction: 'left' }, 100);
			else
				$mobileMenu.stop(true, true).hide('slide', { direction: 'left' }, 100);
		}

		toggleFilter = function(toggle)
		{
			$filterButton.toggleClass('current', toggle);
			$filters.toggleClass('shown', toggle);
			$content.toggleClass('hidden', toggle);

			admin.resizePage();
		}

		//clicking the menu button hides/shows the mobile menu
		$menuButton.click(function(e)
		{
			e.preventDefault();

			toggleMenu(!$menuButton.hasClass('current'));
		});

		//clicking the filter button hides/shows the filter
		$filterButton.click(function(e)
		{
			e.preventDefault();

			toggleFilter(!$filterButton.hasClass('current'));
		});

		//hide the menu on document click outside
		$(document).click(function(e)
		{
			var inMenuButton = $menuButton.is(e.target) || $menuButton.has(e.target).length !== 0,
				inMenu = $mobileMenu.is(e.target) || $mobileMenu.has(e.target).length !== 0,
				inFilterButton = $filterButton.is(e.target) || $filterButton.has(e.target).length !== 0,
				inFilters = $filters.is(e.target) || $filters.has(e.target).length !== 0;

			if ($menuButton.hasClass('current') && !inMenu && !inMenuButton)
				toggleMenu(false);

			if ($filterButton.hasClass('current') && !inFilters && !inFilterButton)
				toggleFilter(false);
		});

		//clicking menu items in the mobile menu hides/shows that submenu
		$mobileMenu.on('click', 'li.menu > span', function()
		{
			$(this).siblings('ul').toggle();
		});

		//set up the customscroll plugin for the mobile menu
		$mobileMenu.customscroll();


		//disable body scroll when scroll a scrollable content
		$('.scrollable').on('DOMMouseScroll mousewheel', function(ev) {
		    var $this = $(this),
		        scrollTop = this.scrollTop,
		        scrollHeight = this.scrollHeight,
		        height = $this.height(),
		        delta = (ev.type == 'DOMMouseScroll' ?
		            ev.originalEvent.detail * -40 :
		            ev.originalEvent.wheelDelta),
		        up = delta > 0;

		    var prevent = function() {
		        ev.stopPropagation();
		        ev.preventDefault();
		        ev.returnValue = false;
		        return false;
		    };

		    if (!up && -delta > scrollHeight - height - scrollTop) {
		        // Scrolling down, but this will take us past the bottom.
		        $this.scrollTop(scrollHeight);
		        return prevent();
		    } else if (up && delta > scrollTop) {
		        // Scrolling up, but this will take us past the top.
		        $this.scrollTop(0);
		        return prevent();
		    }
		});

		// filter btn
		$('#filter-btn-success').on('click', function() {
			var visible = $('#sidebar').is(':visible');

			if (visible) {
				$('.item_edit_container').fadeIn();
				$('#sidebar').fadeOut();
			} else {
				$('.item_edit_container').fadeOut();
				$('#sidebar').fadeIn();
			}
		});
	});
})(jQuery);

//fixes the issue with media queries not firing when the user resizes the browser in another tab
(function() {
	var hidden = "hidden";

	// Standards:
	if (hidden in document)
		document.addEventListener("visibilitychange", onchange);
	else if ((hidden = "mozHidden") in document)
		document.addEventListener("mozvisibilitychange", onchange);
	else if ((hidden = "webkitHidden") in document)
		document.addEventListener("webkitvisibilitychange", onchange);
	else if ((hidden = "msHidden") in document)
		document.addEventListener("msvisibilitychange", onchange);
	// IE 9 and lower:
	else if ('onfocusin' in document)
		document.onfocusin = document.onfocusout = onchange;
	// All others:
	else
		window.onpageshow = window.onpagehide
			= window.onfocus = window.onblur = onchange;

	function onchange (evt) {
		var v = 'sg-tab-bust-visible', h = 'sg-tab-bust-hidden',
			evtMap = {
				focus:v, focusin:v, pageshow:v, blur:h, focusout:h, pagehide:h
			};

		evt = evt || window.event;
		if (evt.type in evtMap)
			document.body.className = evtMap[evt.type];
		else
			document.body.className = this[hidden] ? "sg-tab-bust-hidden" : "sg-tab-bust-visible";

		//clear out the body's class
		document.body.className = '';
	}
})();

//# sourceMappingURL=app.js.map

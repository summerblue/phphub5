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

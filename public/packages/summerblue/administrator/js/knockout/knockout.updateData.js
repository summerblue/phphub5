/*
 * Extension to the knockoutjs mapping plugin
 * http://github.com/janhartigan/knockout-mapping-updatedata
 * Requires KnockoutJS and the mapping plugin
 *
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * Jan Hartigan
 */
(function () {
	/**
	 * A function that lets you "update from js" without overriding all the view model properties and methods. You just need to supply
	 * the viewModel, the original JS model on which you based your data (typically what you'd use in the mapping fromJS method), and the new JS
	 * object that has the updated information.
	 *
	 * @param Object	viewModel
	 * @param Object	dataModel
	 * @param Object	jsObject
	 *
	 * @return Object (returns the viewModel)
	 */
	ko.mapping.updateData = function(viewModel, dataModel, jsObject) {
		if (arguments.length < 3) throw new Error("When calling ko.updateData, pass: the view model, the data model, and the updated data.");
		if (!viewModel) throw new Error("The view model is undefined.");

		for (var i in dataModel) {
			if (i in jsObject && i in viewModel && typeof dataModel[i] != 'function') {
				viewModel[i](jsObject[i]);
			}
		}

		return viewModel;
	}

	ko.exportSymbol('ko.mapping.updateData', ko.mapping.updateData);
})();
// checkAlphabet() plug-in implementation
jQuery.fn.checkAlphabet = function() {
	var IDs = [];
	var errors = [];
	$(this).find("input").each(function() {
		if ($(this).attr('ischeck') == 'true') {
			if ($(this).val() != '') {
				IDs.push("#" + this.id);
			}
		}
	});

	if (IDs.length == 0)
		return true;

	if (!checkInput(IDs + "")) {
		printErrors();
		return false;
	} else
		return true;
};

function hasAlpha(str) {
	var patt = new RegExp("[a-zA-Z0-9]+");
	var bResult = patt.test(str);
	return bResult;
}

function checkInput(arTagIDs) {
	errors = [];
	$(arTagIDs).map(function() {
		if (!hasAlpha($(this).val())) {
			errorCollection($(this));
		}

	});

	return errors.length > 0 ? false : true;
}

function errorCollection(tag) {
	errors.push({
		"name" : tag.attr('display-name'),
		"value" : tag.val()
	});
}

function printErrors() {
	var strError = 'Invalid Input\n..................................................................\n';
	for (var i = 0; i < errors.length; i++) {
		strError += errors[i].name + ": " + errors[i].value + "\n";
	}
	alert(strError);
}
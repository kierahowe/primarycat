
window.pricatfunctions = {
	updateList: function() {
		var enlist = {};
		jQuery('#categorychecklist input').each (function (index) { 
			if (this.type == 'checkbox' && this.name == 'post_category[]') {
				if (this.checked) {
					enlist[this.id.substring(12)] = 1;
				} else { 
					enlist[this.id.substring(12)] = 0;
				}
			}	
		});	

		jQuery('#primarycat option').each (function (index) { 
			if (this.value != -1) { 
				if (enlist[this.value]) {
					this.disabled = false;
				} else {
					this.disabled = true;
				}
			}
		});

		console.log (enlist);
	}
};



jQuery(document).ready (function () { 
	jQuery('#categorychecklist input').each (function (index) { 
		jQuery(this).click (window.pricatfunctions.updateList);
	});
	window.pricatfunctions.updateList ();
});
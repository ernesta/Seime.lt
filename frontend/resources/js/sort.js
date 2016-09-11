var letters = {
	"ą": "a", "č": "c", "ę": "e",
	"ė": "e", "į": "i", "š": "s",
	"ų": "u", "ū": "u", "ž": "z"
}

$("#one").click(function() {
	sortItems("one");
});

$("#two").click(function() {
	sortItems("two");
});

$("#three").click(function() {
	sortItems("three");
});

$("#four").click(function() {
	sortItems("four");
});

function sortItems(tag) {
	var items = jQuery("#NTAKKList li").get();
	var header = items.splice(0, 1);
	
	var aToZ = 1;
	
	if (jQuery("#" + tag).data("sorted") == "0") {
		jQuery("#" + tag).data("sorted", "1");
	} else {
		aToZ = -1;
		jQuery("#" + tag).data("sorted", "0");
	}
	
	items.sort(function(a, b) {
		var result = 0;
		
		var keyA = jQuery(a).data(tag);
		var keyB = jQuery(b).data(tag);
		
		if (tag == "one") {
			keyA = keyA.toLowerCase().replace(/[^\w ]/g, function(char) {
			  return letters[char] || char;
			});
			
			keyB = keyB.toLowerCase().replace(/[^\w ]/g, function(char) {
			  return letters[char] || char;
			});
		} else {
			keyA = parseFloat(keyA);
			keyB = parseFloat(keyB);
		}
		
		if (keyA < keyB) result = -1;
		if (keyA > keyB) result = 1;
		
		return aToZ * result;
	});
	
	var ul = jQuery("#NTAKKList");
	jQuery.each(items, function(i, li) {
		ul.append(li);
	});
}
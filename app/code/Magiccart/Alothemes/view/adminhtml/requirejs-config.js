var config = {
    map: {
        '*': {
            alothemes: "Magiccart_Alothemes/js/alothemes"
        },
    },

	paths: {
		'magiccart/mcolorpicker'	: 'Magiccart_Alothemes/js/mcolorpicker'
	},

	shim: {
		'magiccart/mcolorpicker': {
			deps: ['jquery']
		},
		'alothemes': {
			deps: ['jquery', 'magiccart/mcolorpicker']
		},

	}

};
var config = {
    map: {
        '*': {
            mcolorpicker: "Magiccart_Alothemes/js/mcolorpicker",
            alothemes: "Magiccart_Alothemes/js/alothemes",
        },
    },

	paths: {
		'mcolorpicker'	: 'Magiccart_Alothemes/js/mcolorpicker',
		'alothemes'		: 'Magiccart_Alothemes/js/alothemes',
	},

	shim: {
		'mcolorpicker': {
			deps: ['jquery']
		},
		'alothemes': {
			deps: ['jquery', 'mcolorpicker']
		},

	}

};
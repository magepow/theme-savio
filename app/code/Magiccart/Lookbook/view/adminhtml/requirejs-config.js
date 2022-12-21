var config = {
    map: {
        '*': {
            select2: 'magiccart/select2',
        },
    },

	paths: {
		'magiccart/select2'		: 'Magiccart_Lookbook/js/plugin/select2.full.min',
	},

	shim: {
		'magiccart/select2': {
			deps: ['jquery']
		}
	}

};

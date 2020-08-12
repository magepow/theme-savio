var config = {

	map: {
		'*': {
			'alothemes': 'Magiccart_Alothemes/js/alothemes',
		},
	},

	paths: {
		'magiccart/easing'			: 'Magiccart_Alothemes/js/plugins/jquery.easing.min',
		'magiccart/parallax'		: 'Magiccart_Alothemes/js/plugins/jquery.parallax',
		'magiccart/socialstream'	: 'Magiccart_Alothemes/js/plugins/jquery.socialstream',
		'magiccart/bootstrap'		: 'Magiccart_Alothemes/js/plugins/bootstrap.min',
		'magiccart/slick'			: 'Magiccart_Alothemes/js/plugins/slick.min',
		'magiccart/sticky'		    : 'Magiccart_Alothemes/js/plugins/sticky-kit.min',
		'magiccart/wow'				: 'Magiccart_Alothemes/js/plugins/wow.min',
		// 'alothemes'					: 'Magiccart_Alothemes/js/alothemes',
	},

	shim: {
		'magiccart/easing': {
			deps: ['jquery']
		},
		'magiccart/bootstrap': {
			deps: ['jquery']
		},
		'magiccart/parallax': {
			deps: ['jquery']
		},
		'magiccart/socialstream': {
			deps: ['jquery']
		},
		'magiccart/slick': {
			deps: ['jquery']
		},
		'magiccart/sticky': {
			deps: ['jquery']
		},
		'magiccart/wow': {
			deps: ['jquery']
		},
        'alothemes': {
            deps: ['jquery', 'magiccart/easing', 'magiccart/slick']
        },

	}

};

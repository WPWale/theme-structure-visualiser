
module.exports = function ( grunt ) {

	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),
		wp_readme_to_markdown: {
			dist: {
				options: {
					screenshot_url: '<%= pkg.repository.url %>/raw/master/org-assets/{screenshot}.png',
					post_convert: function ( file ) {
						return "<img src='" + grunt.config.get( 'pkg' ).repository.url + "/raw/master/org-assets/banner-772x250.png'/>\n\n" + file;
					}
				},
				files: {
					'README.md': 'readme.txt'
				}
			}
		},
		concat: {
			release: {
				src: [
					'assets/js/prepend-to-next.js',
					'assets/js/display-header-object.js',
				],
				dest: 'assets/js/theme-structure-visualiser.min.js',
			}
		},
		uglify: {
			dist: {
				options: {
					mangle: {
						reserved: [ 'jQuery', '$' ]
					},
					sourceMap: true,
				},
				files: {
					'assets/js/theme-structure-visualiser.min.js': [ 'assets/js/theme-structure-visualiser.min.js' ]
				}
			}
		},
		makepot: {
			target: {
				options: {
					domainPath: '/languages',
					exclude: [ 'node_modules/.*', 'tests/.*' ],
					mainFile: '<%= pkg.main %>',
					potFilename: '<%= pkg.name %>.pot',
					potHeaders: {
						poedit: false,
						'report-msgid-bugs-to': '<%= pkg.bugs.url %>'
					},
					type: 'wp-plugin',
					updateTimestamp: false
				}
			}
		},
		watch: {
			grunt: {
				files: [ 'Gruntfile.js' ]
			},
			uglify: {
				files: [ 'admin/js/*.js', '!admin/js/*.min.js' ],
				tasks: [ 'uglify' ]
			},
			wp_readme_to_markdown: {
				files: [ 'readme.txt' ],
				tasks: [ 'wp_readme_to_markdown' ]
			}
		},
		phpcs: {
			application: {
				    src: [ '*' ]
			},
			options: {
				bin: '/home/shantanu/.config/composer/vendor/bin/phpcs',
				standard: 'WordPress',
				verbose: true
			}
		}
	} );

	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-phpcs' );

	grunt.registerTask( 'default', [
		'wp_readme_to_markdown',
		'concat',
		'uglify'
	] );

};
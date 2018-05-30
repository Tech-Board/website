module.exports = function(grunt) {
    grunt.initConfig({
		sass: {
			options: {
                includePaths: ['node_modules/bootstrap-sass/assets/stylesheets']
            },
            dist: {
				options: {
					outputStyle: 'compressed'
				},
                files: [{
					expand: true,
					cwd: 'assets/sass', /*root folder for styles*/
					src: 'main.scss', /* file path which have to be converted into css after the root folder*/
					dest: 'assets/css/',/* css file path where the converted files have to be placed*/
					ext: '.css' /*file extention for converted files*/
				}]
            }
        },
        
        uglify: {
          my_target: {
            files: {
               'Assets/bundles/libscripts.bundle.js': ['assets/js/jquery.1.8.3.min.js','assets/js/bootstrap.js','assets/js/material-kit.js','assets/js/material.min.js'], /* main js*/
               
               'Assets/bundles/vendorscripts.bundle.js': ['assets/js/jquery-scrolltofixed.js','assets/js/jquery.easing.1.3.js','assets/js/jquery.isotope.js','assets/js/wow.js','assets/js/classie.js'], /* coman js*/
                             
                           
              }
            }
        }
    });
    grunt.loadNpmTasks("grunt-sass");
    grunt.loadNpmTasks('grunt-contrib-uglify');
    
    grunt.registerTask("buildcss", ["sass"]);
    grunt.registerTask("buildjs", ["uglify"]);
};


module.exports = function(grunt) {
 
  grunt.registerTask('watch', [ 'watch' ]);
  grunt.registerTask('uglify', 'uglify');
  grunt.registerTask('cssmin', 'cssmin');

  grunt.initConfig({
   
   uglify: {
     build: {
     	src: ['js/app.js'],
     	dest: 'js/app.min.js'
      }
    },
    cssmin: {
     build: {
     	src: ['css/app.css'],
     	dest: 'css/app.min.css'
      }
    },
    watch: {
      js: {
        files: ['js/*.js'],
        options: {
          livereload: true,
        }
      },
      css: {
        files: ['css/*.css'],
        options: {
          livereload: true,
        }
      },
      html: {
      	files: ['index.html'],
      	options: {
      		livereload: true,
      	}
      }
    }

 

  });
 
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
 
};
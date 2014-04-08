require.config({
  baseUrl: '/content/themes/<%= _.slugify(blogName) %>/library/js',
  paths: {
    'fastclick':          '../vendor/js/fastclick/fastclick',
    'jquery':             '../vendor/js/jquery/jquery',
  },
  
});

requirejs(['app/main']);

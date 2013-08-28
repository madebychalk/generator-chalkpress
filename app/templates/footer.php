        <footer class="footer" role="contentinfo">
          <div class="row">
            <div class="large-12 columns">

              <nav role="navigation">
                <ul class="inline-list inversed breakout">
                  <li><a href="">Contact</a></li>
                  <li><a href="">Terms and Conditions</a></li>
                  <li><a href="">Privacy Policy</a></li>
                </ul>  
              </nav>

            </div>
          </div>

          <div class="row">
            <div class="large-12 columns"> 

              <div class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>.</div>

            </div>
          </div>
        </footer>
      </div>
    </div>
    
    <!-- build:remove:dist -->
      <?php ChalkPress::vendor_javascript_tag('requirejs/require.js', array('data-main' => ChalkPress::javascript_url('app.js')) ); ?>
    <!-- /build -->

    <!-- build:template:dist
      <?php ChalkPress::javascript_tag('app.min.js') ?>
    /build -->

    <?php wp_footer(); ?>

  </body>
</html> 

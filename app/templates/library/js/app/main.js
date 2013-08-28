define(["fastclick", "jquery"], function(FastClick, $) {

  $(function() {
    FastClick.attach(document.body);
    console.log("Require JS is working!");
  });

});

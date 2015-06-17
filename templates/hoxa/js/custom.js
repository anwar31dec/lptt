// (function($) {
//  "use strict";

    jQuery(document).ready(function($){
        $(".close-but").click(function(){
          $(this).closest(".style-box").fadeOut("slow");
        });
    
    });

// })(jQuery);
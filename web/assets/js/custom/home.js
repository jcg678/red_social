$(document).ready(function(){
   var ias = jQuery.ias({
      container: '#timeline .box-content',
      item: '.publication-item',
      pagination: '#timeline .pagination',
      next: '#timeline .pagination .next_link',
      triggerPageThreshold: 5
   });

   ias.extension(new IASTriggerExtension({
      text: 'Ver más',
      offset: 3
   }));

   ias.extension(new IASSpinnerExtension({
      src: URL+'/assets/images/ajax-loader.gif'
   }));
   ias.extension(new IASNoneLeftExtension({
      text: 'No hay más públicaciones'
   }));

   ias.on('ready',function(event){
      buttons();
   });
   ias.on('rendered',function(event){
      buttons();
   });
});

function buttons(){
   $(".btn-img").unbind("click").click(function(){
      $(this).parent().find('.pub-image').fadeToggle();
   });
}
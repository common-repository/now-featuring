/*
 * Scripts for the Now Featuring Wordpress plugin
 *
 */

function nf_switch_widget_form(select_input, input_name) {
   
   var which_input = get_div( 'which_input', select_input );
   var how_input = get_div( 'how_input', select_input );
   var post_type_input = get_div( 'post_type_input', select_input );
   var slider_inputs = get_div( 'slider_inputs', select_input );
   var list_inputs = get_div( 'list_inputs', select_input );
   var category_input = get_div( 'category_input', select_input );
   var tag_input = get_div( 'tag_input', select_input );
   var selection_input = get_div( 'selection_input', select_input );
   
   var widget_type;
   if ( input_name == 'nf_widget_type' ){
      widget_type = jQuery(select_input).val();
   }else{
       var widget_type_input = get_div( 'widget_type_input', select_input );
       widget_type = get_select_input_value(widget_type_input);
   }
   
   var how = get_select_input_value(how_input);
   var post_type = get_select_input_value(post_type_input);
   
   if ( input_name == 'nf_widget_type' ){
      
      if ( widget_type == 'Single') {
         // show
         jQuery( which_input ).addClass("nf_widget_fields");
         jQuery( which_input ).removeClass("nf_widget_fields_hidden");
         
         // hide
         jQuery( how_input ).removeClass("nf_widget_fields");
         jQuery( how_input ).addClass("nf_widget_fields_hidden");
         jQuery( category_input ).removeClass("nf_widget_fields");
         jQuery( category_input ).addClass("nf_widget_fields_hidden");
         jQuery( tag_input ).removeClass("nf_widget_fields");
         jQuery( tag_input ).addClass("nf_widget_fields_hidden");
         jQuery( selection_input ).removeClass("nf_widget_fields");
         jQuery( selection_input ).addClass("nf_widget_fields_hidden");
         show_hide_hows( '', category_input, tag_input, selection_input );
         jQuery( slider_inputs ).removeClass("nf_widget_fields");
         jQuery( slider_inputs ).addClass("nf_widget_fields_hidden");
         jQuery( list_inputs ).removeClass("nf_widget_fields");
         jQuery( list_inputs ).addClass("nf_widget_fields_hidden");
         
      }else if ( widget_type == 'Slider') {  
         // show
         jQuery( how_input ).addClass("nf_widget_fields");
         jQuery( how_input ).removeClass("nf_widget_fields_hidden");
         show_hide_hows( how, category_input, tag_input, selection_input );
         jQuery( slider_inputs ).addClass("nf_widget_fields");
         jQuery( slider_inputs ).removeClass("nf_widget_fields_hidden");
         
         // hide
         jQuery( which_input ).removeClass("nf_widget_fields");
         jQuery( which_input ).addClass("nf_widget_fields_hidden");
         jQuery( list_inputs ).removeClass("nf_widget_fields");
         jQuery( list_inputs ).addClass("nf_widget_fields_hidden");
      }else if ( widget_type == 'List') {
          // show
         jQuery( how_input ).addClass("nf_widget_fields");
         jQuery( how_input ).removeClass("nf_widget_fields_hidden");
         show_hide_hows( how, category_input, tag_input, selection_input );
         jQuery( list_inputs ).addClass("nf_widget_fields");
         jQuery( list_inputs ).removeClass("nf_widget_fields_hidden");
         
         // hide
         jQuery( which_input ).removeClass("nf_widget_fields");
         jQuery( which_input ).addClass("nf_widget_fields_hidden");
         jQuery( slider_inputs ).removeClass("nf_widget_fields");
         jQuery( slider_inputs ).addClass("nf_widget_fields_hidden");
      }
   }else if ( input_name == 'nf_how' ) {
      show_hide_hows( how, category_input, tag_input, selection_input );
   }else if ( input_name == 'nf_post_type' ) {
      populate_which_one_select( post_type, select_input );
      populate_which_ones_select( post_type, select_input );
   }else{
      // hide all
      jQuery( how_input ).removeClass("nf_widget_fields");
      jQuery( how_input ).addClass("nf_widget_fields_hidden");
      show_hide_hows( '', category_input, tag_input, selection_input );
      jQuery( list_inputs ).removeClass("nf_widget_fields");
      jQuery( list_inputs ).addClass("nf_widget_fields_hidden");
      jQuery( which_input ).removeClass("nf_widget_fields");
      jQuery( which_input ).addClass("nf_widget_fields_hidden");
      jQuery( slider_inputs ).removeClass("nf_widget_fields");
      jQuery( slider_inputs ).addClass("nf_widget_fields_hidden");
   }
}

function show_hide_hows( how, category_input, tag_input, selection_input ){
   // show the one selected
   if ( how == 'category') {
      // show
      jQuery( category_input ).addClass("nf_widget_fields");
      jQuery( category_input ).removeClass("nf_widget_fields_hidden");
      
      // hide
      jQuery( tag_input ).removeClass("nf_widget_fields");
      jQuery( tag_input ).addClass("nf_widget_fields_hidden");
      jQuery( selection_input ).removeClass("nf_widget_fields");
      jQuery( selection_input ).addClass("nf_widget_fields_hidden");
      
   }else if ( how == 'tag') {
      // show
      jQuery( tag_input ).addClass("nf_widget_fields");
      jQuery( tag_input ).removeClass("nf_widget_fields_hidden");
      
      // hide
      jQuery( category_input ).removeClass("nf_widget_fields");
      jQuery( category_input ).addClass("nf_widget_fields_hidden");
      jQuery( selection_input ).removeClass("nf_widget_fields");
      jQuery( selection_input ).addClass("nf_widget_fields_hidden");
      
   }else if ( how == 'selection') {
      // show
      jQuery( selection_input ).addClass("nf_widget_fields");
      jQuery( selection_input ).removeClass("nf_widget_fields_hidden");
      
      // hide
      jQuery( category_input ).removeClass("nf_widget_fields");
      jQuery( category_input ).addClass("nf_widget_fields_hidden");
      jQuery( tag_input ).removeClass("nf_widget_fields");
      jQuery( tag_input ).addClass("nf_widget_fields_hidden");
      
   }else{
      // hide all
      jQuery( selection_input ).removeClass("nf_widget_fields");
      jQuery( selection_input ).addClass("nf_widget_fields_hidden");
      jQuery( category_input ).removeClass("nf_widget_fields");
      jQuery( category_input ).addClass("nf_widget_fields_hidden");
      jQuery( tag_input ).removeClass("nf_widget_fields");
      jQuery( tag_input ).addClass("nf_widget_fields_hidden");
   }
}

function populate_which_ones_select ( post_type, input ){
   populate_which_one_select(post_type, input, 'selection_input');
}

function populate_which_one_select ( post_type, input, name ){
   
   if (!name) {
      name = 'which_input';
   }
   var which_input_div = get_div( name, input );
   var which_selects = jQuery( which_input_div ).find( 'select' );
   which_select = which_selects[0];
   jQuery(which_select).empty();
   
   var the_options;
   if ( post_type == 'post' ){
      the_options = jQuery.parseJSON( post_options );
   }else{
      the_options = jQuery.parseJSON( page_options );
   }
   
   jQuery(the_options).each(function(k){
      option = the_options[k];
      
      jQuery(which_select).append(jQuery('<option>', {
                                             value: option.id,
                                             html: option.title
                                          }));
   });
}

function get_select_input_value( div ){
   var selects = jQuery( div ).find( 'select' );
   select = selects[0];
   var value = jQuery(select).val();
   
   return value;
}

function get_div( div_match, select_input ){
 
   var parent_div = jQuery(select_input).parent().parent().parent();
   var children = jQuery(parent_div).children();
   var div_wanted;
   var re = new RegExp(div_match);
   children.each(function( k ) {
      kid = children[k];
      if (kid.nodeName == 'DIV') {
         div = kid;
         if (jQuery(div).attr('id').match( re )){
            div_wanted = div;
         }
      }
   });
   return div_wanted;
}
(function ($) {
  $(document).ready(function () {
    $('.slick_homepage').each(function() {
        var $this = jQuery(this);
        if ($this.children().length > 1) {
          $this.slick({
        dots: false,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 1,
        adaptiveHeight: true,
        arrows: false,
          });
        }
    });
    $('.slick_cate_shop').each(function() {
        var $this = jQuery(this);
        if ($this.children().length > 1) {
          $this.slick({
        dots: false,
        infinite: true,
        autoplay: false,
        autoplaySpeed: 3000,
        slidesToShow: 1,
        slidesToScroll: 1,
        variableWidth: true,
        arrows: true,
          });
        }
	});
        $(document).bind('ready ajaxComplete', function () {
            $(".ds-hover-gallery .et_pb_gallery_item").each(function () {
                $(this).find(".et_pb_gallery_title, .et_pb_gallery_caption").wrapAll('<div class="ds-gallery-text"></div>');
            });
        });

        /*On Click hide button*/
        // $(document).on("click",".product-col .add_to_cart_button",function(){
        //   $(this).hide();
        // });
        $(".product-tab-content").first().addClass("active_tab");

        //slide toggle cart bar
        $(document).on('click','.side-cart-toggle',function(){
            // $(".rightcart").toggleClass("side-cart-open");
            $("#sidebar").toggleClass("side-cart-open");
            $(".overlay").toggle();
        });
        setTimeout(() => {
          if($('#how_many_servers').length > 0 || $('#server_hours').length > 0 || $('#event_time').length > 0){
            new Cleave("#how_many_servers", {
                numeral: true,
                numeralThousandsGroupStyle: 'none'
            });
      
            new Cleave('#server_hours', {
                numeral: true,
                numeralThousandsGroupStyle: 'none',
                numeralPositiveOnly: true
            });
            // new Cleave('#event_time', {
            //     time: true,
            //     timePattern: ['h', 'm']
            // });
            // $('#event_time').mdtimepicker();
            var example = flatpickr('#event_time',{
              enableTime: true,
              noCalendar: true,
              allowInput: false
            });
            var example = flatpickr('#server_from',{
              enableTime: true,
              noCalendar: true,
              allowInput: false
            });
            var example = flatpickr('#server_to',{
              enableTime: true,
              noCalendar: true,
              allowInput: false
            });
            // $('#server_from').mdtimepicker();
            // $('#server_to').mdtimepicker();
            

            jQuery('#how_many_servers_field').prepend('<span class="server-label">How many servers:</span>')
            jQuery('#server_hours_field').prepend('<span class="server-label">Hours:</span>')
          }
          jQuery('#billing_address_2_field').before('<div class="form-row form-row-wide"> <input type="checkbox" class="same-as-address"> Same as Street Address</div>');
          
          jQuery("#billing_address_1").keyup(function(){
            if($('.same-as-address').is(':checked') == true){
              jQuery('#billing_address_2').val(jQuery(this).val());
            }
          });
          
          jQuery(document).on('click',".same-as-address",function() {
            
            if($(this).is(':checked') == true){
              
              var address1 = $('#billing_address_1').val();
              $('#billing_address_2').val(address1);
              $('#billing_address_2').attr('disabled','disabled');
              jQuery('#billing_address_2').attr('value',address1);
              
              }
              if($(this).is(':checked') == false){
  
                $('#billing_address_2').val('');
                $('#billing_address_2').removeAttr('disabled');
            }
        });
          $("#server_to_field").append('<a href="javascript:;" class="set-servers button">Save servant info</a>');
          $(document).on('click','#need_servers_yes',function(){
            $('#how_many_servers_field').show();
            $('#server_hours_field').show();
            $('#server_from_field').show();
            $('#server_to_field').show();
          })
          $(document).on('click','#need_servers_no',function(){
            $('#how_many_servers_field').hide();
            $('#server_hours_field').hide();
            $('#server_from_field').hide();
            $('#server_to_field').hide();
            $('.servers-error').remove();
          })
          if($('body').hasClass("page-template-checkout-page")){
            $('.nopfield').attr("readonly","readonly")
            $('#event_time').attr('type','time');
            $('#server_from').attr('type','time');
            $('#server_to').attr('type','time');
          }
          if($('#event_date').length > 0){
            var dateToday = new Date();
            $( "#event_date" ).datepicker({
              minDate: dateToday,
            });
          }
          $('.select2-container--default .select2-results__option[aria-disabled=true]').text("Select Hours");
        }, 1000);
        setTimeout(() => {
          $('.cart-loader').removeClass('active');
         }, 3000);
    });
}) (jQuery);

function minmax(value, min, max) 
    {
        if(parseInt(value) < min || isNaN(parseInt(value))) 
            return min; 
        else if(parseInt(value) > max) 
            return max; 
        else return value;
    }
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("product-tab-content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "flex";
  evt.currentTarget.className += " active";
}
// console.log(getFirstTab);

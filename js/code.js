
$(document).ready(function(){ //TODO : à refaire, c'est complètement sous-optimal...
    
   $('.toBeToggled').hide();
   $('.isClickable').click(function(){
   $(this).parent('.toBeClicked').children('.toBeToggled').slideToggle("slow");
   });
   
   
});



/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



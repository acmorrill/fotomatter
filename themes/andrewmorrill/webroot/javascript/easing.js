/* 
Below are easing equations based on Robert Penner's work, modified for JQuery
The "In" part of an animation is the start of it, the "Out" part is the end of it
If you apply "easing" at the "In" or the "Out" then the supplied animation curve is most apparent at that point
Enjoy the animation curves!
usage: $(".myImageID").animate({"left": "+=100"},{queue:false, duration:500, easing:"bounceEaseOut"});

*/

jQuery.extend({
    
    easing: 
    {
		sineEaseOut:function(p, n, firstNum, diff) {
            
            var c=firstNum+diff;
            return c * Math.sin(p * (Math.PI/2)) + firstNum;
        },
		backEaseInOut:function(p, n, firstNum, diff) {
            var c=firstNum+diff;
            
            var s = 1.70158; // default overshoot value, can be adjusted to suit
            if ((p/=0.5) < 1) 
                return c/2*(p*p*(((s*=(1.525))+1)*p - s)) + firstNum;
            else
                return c/2*((p-=2)*p*(((s*=(1.525))+1)*p + s) + 2) + firstNum;
        },
        // ******* bounce
        bounceEaseIn:function(p, n, firstNum, diff) {
            
            var c=firstNum+diff;
            var inv = this.bounceEaseOut (1-p, 1, 0, diff);
            return c - inv + firstNum;
        },
        
        bounceEaseOut:function(p, n, firstNum, diff) {

            var c=firstNum+diff;

            if (p < (1/2.75))
            {
                return c*(7.5625*p*p) + firstNum;
            }
            else if (p < (2/2.75))
            {
                return c*(7.5625*(p-=(1.5/2.75))*p + .75) + firstNum;
            }
            else if (p < (2.5/2.75))
            {
                return c*(7.5625*(p-=(2.25/2.75))*p + .9375) + firstNum;
            }
            else
            {
                return c*(7.5625*(p-=(2.625/2.75))*p + .984375) + firstNum;
            }
        }
    }
});

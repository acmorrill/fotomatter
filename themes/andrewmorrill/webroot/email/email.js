pic1= new Image(16,16); 
pic1.src="email/loading.gif"; 
pic2= new Image(16,16); 
pic2.src="email/x.png"; 
pic3= new Image(16,16); 
pic3.src="email/valid.png"; 

function writecookie(content){
var expiredate = new Date
expiredate.setMonth(expiredate.getFullYear()+9)
document.cookie = "newsletter="+ content+";expires="+expiredate.toGMTString();
}

function checkemail(){
var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i
var returnval=emailfilter.test(document.getElementById('newsletter').value)
if (returnval==false){
	document.getElementById('valid').src = 'email/x.png';
	jQuery("span#newsletterWarning").text("");
	jQuery("img.button").unbind('click').click(function () {
		jQuery("span#newsletterWarning").html("<img src='images/misc/error_arrow_up.gif' />Please Enter a Valid Email Address");
	});
}
else {
document.getElementById('valid').src = 'email/valid.png';
jQuery("span#newsletterWarning").text("");
jQuery("img.button").unbind('click').click(function () {
	submitemail(document.getElementById('newsletter').value);
});
//document.getElementById('sending').innerHTML = ''
}
}

function submitemail(email){
document.getElementById('sending').innerHTML = '<img src="email/loading.gif">';
new Ajax.Request( 'email/write.php?var='+encodeURIComponent(email), {method:'get', onSuccess:Rcvd, onFailure:Failed});
}

//// Place failure message here
function Failed()
{
document.getElementById('results').innerHTML = 'Sorry, an error occured. Refresh and try again.'
}

//// Place confirm message here
function Rcvd()
{
writecookie(document.getElementById('newsletter').value);
trackNewsLetterConv();
closeNewsletter();
//document.getElementById('results').innerHTML = 'Your e-mail address has been succesfully submitted!'
}

//// Don't edit this.
function getcookie(c_name) {
    if(document.cookie.length > 0) {
        var c_start = document.cookie.indexOf(c_name + "=");
        if(c_start != -1) {
            c_start = c_start + c_name.length + 1;
            var c_end = document.cookie.indexOf(";",c_start);
            if(c_end == -1)
                c_end = document.cookie.length;
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
	}
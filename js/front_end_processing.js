function add_input(form_name){
	var form  = document.getElementById(form_name);
	var input = document.createElement('input');
	input.type = 'file'								
	input.name = 'file[]'
	input.accept ='application/pdf';
	//append the input to the form
	form.appendChild(input);
}
function send(form_name, action){
	if(!action){
		var action = '';
	}
	var form  =  document.getElementById(form_name);
	var formData = new FormData(form);
	var img_url = 'images/minions/'+image_name(action)+'.png';

	get_images('images/gear.png','rotate');
	$('#output').html("").hide();
	$('#progress_bar').val(0).fadeIn();
	var timer = setInterval(update_progress,500);
	$.ajax({
		url: 'php_files/'+action+'.php',
		type: 'POST',
		xhr: function() {
			var myXhr = $.ajaxSettings.xhr();
			return myXhr;
		},
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		success: function (data) {
			$('#output').html(data);
			setTimeout(function(){
				get_images(img_url,'big_minion');
				$('#results').html('Current progress: 100% .Finished !')
				$('#progress_bar').val(100); 
				clearTimeout(timer);
				},5000);
			setTimeout(function(){
				$('#output').show();
				document.getElementById('title3').className = "blink_me";
				},6000);
		}
	});
}
function get_images(url,class_name){
	var img_box = document.getElementById('images');
	img_box.style.display='none';
	img_box.src = url;
	img_box.className  = class_name;
	img_box.style.display='block'; 
}
function update_progress(){
	var progress_bar = document.getElementById('progress_bar');
	var value = progress_bar.value;
	$("#results").html("Current progress: " + parseInt(value)+ "%");
	if (parseInt(value) < 75) {
		progress_bar.value = parseInt(value) + 5 ;
	}
}
// get the image showing the process is finished for each action
function image_name(action){
	switch(action){
		case 'split':
			name = 'combine_minions'; 
		break;
		case 'image_pdf':
			name = 'jpg_minion'; 
		break;
		case 'pdf_word':
			name = 'word_minion';
		break;
		case 'pdf_excel':
			name = 'excel_minion';
		break;
		case 'html_pdf':
			name = 'html_minion';
		break;
		default:
			name ='big_minion';
	}
	return name;
}


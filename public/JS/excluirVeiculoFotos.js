// Vars
var selectedImages = 0;
var btnDeleteImages = document.getElementById('btnDeleteImages');
var inputID = document.getElementById('inputID').value;
var checkboxImage = document.getElementsByName('checkboxImage[]');
var Domain = document.getElementById('DOMAIN').value;
var checkboxImageValues = new Array();

// Events
if(btnDeleteImages)
	btnDeleteImages.addEventListener('click', delImages, false);

for(var i=0;i<checkboxImage.length;i++) {
	checkboxImage[i].addEventListener('change', imagesCount, false);
}

// Functions
function delImages() {
	if(selectedImages < 1) {
		alert('Selecione pelo menos 1 item para continuar.');
	} else {
		btnDeleteImages.setAttribute("disabled", "disabled");

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: Domain+"/ordem/excluirVeiculoFotos",
			data: {
					"ImagesID" : checkboxImageValues,
					"inputID" : inputID
			},
			success: function(response) {
				alert(response.msg);
				if(response.error == 0)	location.reload();
				else btnDeleteImages.removeAttribute("disabled");
			}
		});
	}
}

function imagesCount(e) {
	if(e.target.checked) {
		selectedImages++;
		checkboxImageValues.push(e.target.value);
	} else {
		selectedImages--;
		checkboxImageValues.splice(checkboxImageValues.indexOf(e.target.value), 1);
	}
}
var selected = 0;

document.getElementById('list-actions-archive').addEventListener('click', unarchive, false);
document.getElementById('list-actions-archive').innerHTML = "Desarquivar";
document.getElementById('list-actions-archiveds').remove();

document.getElementById('form').setAttribute('action', 
	document.getElementById('form').
	getAttribute('action').
	replace('arquivar', 'desarquivar'));

for(var i=0;i<document.getElementsByName('selectedItems[]').length;i++) {
	document.getElementsByName('selectedItems[]')[i].addEventListener('change', selectedCount, false);
}

function selectedCount(e) {
	if(e.target.checked) selected++;
	else if(selected > 0) selected--;
	document.getElementById('selectedCount').innerHTML = selected+' selecionados';
}

function unarchive() {
	if(selected < 1) {
		alert('Selecione pelo menos 1 item para continuar.');
	} else {
		document.getElementById('form').submit();
	}
}
var selected = 0;

document.getElementById('list-actions-archive').addEventListener('click', archive, false);
document.getElementsByClassName('material-symbols-outlined')[0].addEventListener('click', help, false);

document.getElementById('list-actions-archiveds').setAttribute('href',
	document.getElementById('list-actions-archiveds').getAttribute('href')+
	"/"+'arquivados/1');

for(var i=0;i<document.getElementsByName('selectedItems[]').length;i++) {
	document.getElementsByName('selectedItems[]')[i].addEventListener('change', selectedCount, false);
}

function selectedCount(e) {
	if(e.target.checked) selected++;
	else if(selected > 0) selected--;
	document.getElementById('selectedCount').innerHTML = selected+' selecionados';
}

function archive() {
	if(selected < 1) {
		alert('Selecione pelo menos 1 item para continuar.');
	} else {
		document.getElementById('form').submit();
	}
}

function help() {
	alert("Mostra a última modificação realizada no cadastro. As modificações incluem: quando foi cadastrado, quando as informações foram alteradas, quando o cadastro foi arquivado ou desarquivado e até, no caso das Ordens de Serviço, quando o status foi alterado.");
}
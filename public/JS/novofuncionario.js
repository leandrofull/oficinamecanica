var error = false;

window.onload = function() {
	document.getElementById('btnSave').addEventListener('click', save, false);
	document.getElementById('inputName').addEventListener('keyup', mask, false);
	document.getElementById('inputCargo').addEventListener('keyup', mask, false);
	document.getElementById('inputMatricula').addEventListener('keyup', mask, false);
	document.getElementById('inputCPF').addEventListener('keyup', mask, false);
	document.getElementById('inputRemuneracao').addEventListener('keyup', mask, false);
	document.getElementById('inputNasc').addEventListener('keyup', mask, false);
	document.getElementById('inputAdmissao').addEventListener('keyup', mask, false);
	document.getElementById('inputDemissao').addEventListener('keyup', mask, false);
	document.getElementById('inputName').addEventListener('keydown', mask, false);
	document.getElementById('inputCargo').addEventListener('keydown', mask, false);
	document.getElementById('inputMatricula').addEventListener('keydown', mask, false);
	document.getElementById('inputCPF').addEventListener('keydown', mask, false);
	document.getElementById('inputRemuneracao').addEventListener('keydown', mask, false);
	document.getElementById('inputNasc').addEventListener('keydown', mask, false);
	document.getElementById('inputAdmissao').addEventListener('keydown', mask, false);
	document.getElementById('inputDemissao').addEventListener('keydown', mask, false);
	document.getElementById('inputNasc').addEventListener('change', mask, false);
	document.getElementById('inputAdmissao').addEventListener('change', mask, false);
	document.getElementById('inputDemissao').addEventListener('change', mask, false);
}

function save() {
	if(!errorCheck()) {
		document.getElementById('form').submit();
	} else {
		alert('Algum preenchimento está incorreto. Por favor, verifique os campos e tente novamente.');
	}
}

function mask(e) {
	if(e.target.id == 'inputName' || e.target.id == 'inputCargo') {
		let string = e.target.value.replace(/[^a-zA-Zà-úÀ-Ú\ ]/g, '');
		string = string.replace(/\s{2,}/g, ' ');
		string = string.toUpperCase().trimLeft();
		e.target.value = string;

		let minLength = [];
		minLength['inputName'] = 3;
		minLength['inputCargo'] = 5;

		let nomeCampo = [];
		nomeCampo['inputName'] = 'Nome Completo';
		nomeCampo['inputCargo'] = 'Cargo';

		let validation = e.target.id.replace('input', 'valid');

		if(!string) {
			error = true;
			document.getElementById(validation).innerHTML = 'O campo '+nomeCampo[e.target.id]+' é obrigatório.';	
		} else if(string.length < minLength[e.target.id]) {
			error = true;
			document.getElementById(validation).innerHTML = 'O campo precisa possuir '+minLength[e.target.id]+' caracteres no mínimo.';	
		} else {
			document.getElementById(validation).innerHTML = '';
		}
	} else if(e.target.id == 'inputMatricula') {
		let string = e.target.value.replace(/[^0-9]/g, '');
		e.target.value = ("00000000000" + string).slice(-11);

		if(!string || string < 1) {
			error = true;
			document.getElementById('validMatricula').innerHTML = 'O campo Matrícula é obrigatório.';	
		} else {
			document.getElementById('validMatricula').innerHTML = '';
		}
	} else if(e.target.id == 'inputCPF') {
        let cpf = e.target.value.replace(/\D/g,"");
        cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
		e.target.value = cpf;
		let teste = /^\d{3}\.\d{3}\.\d{3}\-\d{2}$/.test(cpf);
		if(!cpf) {
			error = true;
			document.getElementById('validCPF').innerHTML = 'O campo CPF é obrigatório.';
		} else if(teste) {
			document.getElementById('validCPF').innerHTML = '';
		} else {
			error = true;
			document.getElementById('validCPF').innerHTML = 'Formato de CPF inválido.';
		}
	} else if(e.target.id == 'inputRemuneracao') {
        let string = e.target.value.replace(/[^0-9\,]/g, '');

      	if(string[0] == ',') {
        	string = string.substring(1);
       	} else if(string.match(/\,/g) && string.match(/\,/g).length > 1){
        	string = string.substring(0, string.length-1);
        } else {
			array = string.split(',');
			if(array[0] > 999999) array[0] = 999999;
			if(array[1] && array[1].length > 2) array[1] = array[1].substring(0,2);
			array[0] = (new Number(array[0])).toLocaleString('pt-br');
			string = array.join(',');
		}

       	e.target.value = "R$ "+string;

       	string = string.replace(/[^0-9\,]/g, '');

       	if(string && string != "0" && string.replace(",",".") < 800) {
       		error = true;
       		document.getElementById('validRemuneracao').innerHTML = 'O valor não pode ser inferior a R$ 800,00.';
       	} else {
       		document.getElementById('validRemuneracao').innerHTML = '';
       	}
	} else if(['inputNasc', 'inputAdmissao', 'inputDemissao'].indexOf(e.target.id) !== -1) {
		var data = new Date(e.target.value);

		let validation = e.target.id.replace('input', 'valid');

		if(!e.target.value) {
			document.getElementById(validation).innerHTML = '';
		} else if(data == 'Invalid Date' || e.target.value.split('-')[0] < 1900 || e.target.value.split('-')[0] > 2300) {
			error = true;
			document.getElementById(validation).innerHTML = 'Data inválida.';
		} else {
			document.getElementById(validation).innerHTML = '';
		}
	}
}

function errorCheck() {
	error = false;

	document.getElementById('inputName').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCargo').dispatchEvent(new Event('keyup'));
	document.getElementById('inputMatricula').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCPF').dispatchEvent(new Event('keyup'));
	document.getElementById('inputRemuneracao').dispatchEvent(new Event('keyup'));
	document.getElementById('inputNasc').dispatchEvent(new Event('keyup'));
	document.getElementById('inputAdmissao').dispatchEvent(new Event('keyup'));
	document.getElementById('inputDemissao').dispatchEvent(new Event('keyup'));

	return error;
}
window.onload = function() {
	document.getElementById('btnSave').addEventListener('click', save, false);
	document.getElementById('inputName').addEventListener('keyup', mask, false);
	document.getElementById('inputName').addEventListener('keydown', mask, false);
	document.getElementById('inputEmail').addEventListener('keyup', mask, false);
	document.getElementById('inputEmail').addEventListener('keydown', mask, false);
	document.getElementById('inputCPF').addEventListener('keyup', mask, false);
	document.getElementById('inputCPF').addEventListener('keydown', mask, false);
	document.getElementById('inputTelefone').addEventListener('keyup', mask, false);
	document.getElementById('inputTelefone').addEventListener('keydown', mask, false);
	document.getElementById('inputCelular').addEventListener('keyup', mask, false);
	document.getElementById('inputCelular').addEventListener('keydown', mask, false);
	document.getElementById('inputWhatsapp').addEventListener('keyup', mask, false);
	document.getElementById('inputWhatsapp').addEventListener('keydown', mask, false);
	document.getElementById('inputEndereco').addEventListener('keyup', mask, false);
	document.getElementById('inputEndereco').addEventListener('keydown', mask, false);
	document.getElementById('inputEndNumero').addEventListener('keyup', mask, false);
	document.getElementById('inputEndNumero').addEventListener('keydown', mask, false);
	document.getElementById('inputEndCompl').addEventListener('keyup', mask, false);
	document.getElementById('inputEndCompl').addEventListener('keydown', mask, false);
	document.getElementById('inputBairro').addEventListener('keyup', mask, false);
	document.getElementById('inputBairro').addEventListener('keydown', mask, false);
	document.getElementById('inputCep').addEventListener('keyup', mask, false);
	document.getElementById('inputCep').addEventListener('keydown', mask, false);
	document.getElementById('inputCidade').addEventListener('keyup', mask, false);
	document.getElementById('inputCidade').addEventListener('keydown', mask, false);
	document.getElementById('inputUf').addEventListener('keyup', mask, false);
	document.getElementById('inputUf').addEventListener('keydown', mask, false);
	document.getElementById('inputNasc').addEventListener('keyup', mask, false);
	document.getElementById('inputNasc').addEventListener('keydown', mask, false);
	document.getElementById('helpTelefone').addEventListener('click', help, false);
	document.getElementById('helpCelular').addEventListener('click', help, false);
	document.getElementById('helpWhatsapp').addEventListener('click', help, false);
}

function save() {
	if(!errorCheck()) {
		document.getElementById('form').submit();
	} else {
		alert('Algum preenchimento está incorreto. Por favor, verifique os campos e tente novamente.')
	}
}

function help() {
	alert('Preencha pelo menos 1 dos 3 campos de telefones disponíveis.\nDigite apenas os números. O formato será ajustado automaticamente.')
}

function mask(e) {
	if(['inputName', 'inputEndereco', 'inputEndCompl', 'inputBairro', 'inputCidade'].indexOf(e.target.id) !== -1) {
		var string = e.target.value.replace(/\s{2,}/g, ' ');
		string = string.toUpperCase().trimLeft();
		let validation = e.target.id.replace('input', 'valid');
		if(e.target.id == 'inputName') {
			string = string.replace(/[^a-zA-Zà-úÀ-Ú]/g, ' ');
			document.getElementById(e.target.id).value = string;
			if(!string) {
				document.getElementById(validation).innerHTML = 'O campo Nome Completo é obrigatório.';	
			} else if(string.length < 3) {
				document.getElementById(validation).innerHTML = 'O campo precisa possuir 3 caracteres no mínimo.';	
			} else {
				document.getElementById(validation).innerHTML = '';
			}
		} else if(e.target.id == 'inputEndereco') {
			string = string.replace(/[^a-zA-Zà-úÀ-Ú0-9]/g, ' ');
			document.getElementById(e.target.id).value = string;
			if(!string) {
				document.getElementById(validation).innerHTML = '';	
			} else if(string.length < 5) {
				document.getElementById(validation).innerHTML = 'O campo precisa possuir 5 caracteres no mínimo.';	
			} else if(!isNaN(string[0])) {
				document.getElementById(validation).innerHTML = 'O campo Endereço não pode começar com números.';
			} else {
				document.getElementById(validation).innerHTML = '';
			}
		} else if(e.target.id == 'inputEndCompl') {
			string = string.replace(/[^a-zA-Zà-úÀ-Ú0-9]/g, ' ');
			document.getElementById(e.target.id).value = string;
			if(!string) {
				document.getElementById(validation).innerHTML = '';	
			} else if(string.length < 4) {
				document.getElementById(validation).innerHTML = 'O campo precisa possuir 4 caracteres no mínimo.';	
			} else if(!isNaN(string[0])) {
				document.getElementById(validation).innerHTML = 'O campo Complemento não pode começar com números.';
			} else {
				document.getElementById(validation).innerHTML = '';
			}
		} else if(e.target.id == 'inputBairro' || e.target.id == 'inputCidade') {
			string = string.replace(/[^a-zA-Zà-úÀ-Ú]/g, ' ');
			document.getElementById(e.target.id).value = string;
			if(!string) {
				document.getElementById(validation).innerHTML = '';	
			} else if(string.length < 4) {
				document.getElementById(validation).innerHTML = 'O campo precisa possuir 4 caracteres no mínimo.';	
			} else {
				document.getElementById(validation).innerHTML = '';
			}
		}
	} else if(e.target.id == 'inputCPF') {
        let cpf = e.target.value.replace(/\D/g,"");
        cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
        cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
		document.getElementById('inputCPF').value = cpf;
		let teste = /^\d{3}\.\d{3}\.\d{3}\-\d{2}$/.test(cpf);
		if(!cpf) {
			document.getElementById('validCPF').innerHTML = 'O campo CPF é obrigatório.';
		} else if(teste) {
			document.getElementById('validCPF').innerHTML = '';
		} else {
			document.getElementById('validCPF').innerHTML = 'Formato de CPF inválido.';
		}
	} else if(e.target.id == 'inputEmail') {
		let email = e.target.value.trim().toLowerCase();
		document.getElementById('inputEmail').value = email;
		let teste = /^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/.test(email);
		if(!email) {
			document.getElementById('validEmail').innerHTML = '';
		} else if(teste) {
			document.getElementById('validEmail').innerHTML = '';
		} else {
			document.getElementById('validEmail').innerHTML = 'Formato de e-mail inválido.';
		}
	} else if(['inputTelefone', 'inputCelular', 'inputWhatsapp'].indexOf(e.target.id) !== -1) {
   		let tel = e.target.value.replace(/\D/g,"");
    	tel = tel.replace(/^(\d{2})(\d)/g,"($1) $2");
    	tel = tel.replace(/(\d)(\d{4})$/,"$1-$2");
    	document.getElementById(e.target.id).value = tel;
    	let validation = e.target.id.replace('input', 'valid');
    	if(!tel) {
    		document.getElementById(validation).innerHTML = '';
		} else if(tel.length < e.target.getAttribute('maxlength')) {
			document.getElementById(validation).innerHTML = 'Formato de telefone inválido.';
    	} else {
    		document.getElementById(validation).innerHTML = '';
    	}
	} else if(e.target.id == 'inputEndNumero') {
		let num = e.target.value.replace(/[^a-zA-Z0-9]/g, '');
		num = num.toUpperCase();
		document.getElementById('inputEndNumero').value = num;
	} else if(e.target.id == 'inputCep') {
		let cep = e.target.value.replace(/\D/g,"");
		cep = cep.replace(/^([\d]{5})\.*([\d]{3})/, "$1-$2");
		document.getElementById('inputCep').value = cep;
		if(!cep) {
			document.getElementById('validCep').innerHTML = '';
		} else if(cep.length < 9) {
			document.getElementById('validCep').innerHTML = 'Formato de CEP inválido.';
		} else {
			document.getElementById('validCep').innerHTML = '';
		}
	} else if(e.target.id == 'inputUf') {
		let uf = e.target.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
		document.getElementById('inputUf').value = uf;
		if(!uf) {
			document.getElementById('validUf').innerHTML = '';
		} else if(uf.length < 2) {
			document.getElementById('validUf').innerHTML = 'Formato de UF inválido.';
		} else {
			document.getElementById('validUf').innerHTML = '';
		}
	} else if(e.target.id == 'inputNasc') {
		var Nasc = new Date(e.target.value);
		if(!e.target.value) {
			document.getElementById('validNasc').innerHTML = '';
		} else if(Nasc == "Invalid Date" || e.target.value.split('-')[0] < 1900 || e.target.value.split('-')[0] > 2300) {
			document.getElementById('validNasc').innerHTML = 'Data de nascimento inválida.';
		} else {
			document.getElementById('validNasc').innerHTML = '';
		}
	}
}

function errorCheck() {
	var error = false;
	var name = document.getElementById('inputName').value.replace(/\s{2,}/g, ' ');
	var endereco = document.getElementById('inputEndereco').value.replace(/\s{2,}/g, ' ');
	var complemento = document.getElementById('inputEndCompl').value.replace(/\s{2,}/g, ' ');
	var bairro = document.getElementById('inputBairro').value.replace(/\s{2,}/g, ' ');
	var cidade = document.getElementById('inputCidade').value.replace(/\s{2,}/g, ' ');
	var cpf = document.getElementById('inputCPF').value.replace(/\D/g,"");
	var email = document.getElementById('inputEmail').value.trim().toLowerCase();
	var tel = document.getElementById('inputTelefone').value.replace(/\D/g,"");
	var cel = document.getElementById('inputCelular').value.replace(/\D/g,"");
	var whats = document.getElementById('inputWhatsapp').value.replace(/\D/g,"");
	var num = document.getElementById('inputEndNumero').value.replace(/[^a-zA-Z0-9]/g, '');
	var cep = document.getElementById('inputCep').value.replace(/\D/g,"");
	var uf = document.getElementById('inputUf').value.replace(/[^a-zA-Z]/g, '').toUpperCase();
	var nasc = document.getElementById('inputNasc').value;
	var teste;
	name = name.replace(/[^a-zA-Zà-úÀ-Ú]/g, ' ');
	endereco = endereco.replace(/[^a-zA-Zà-úÀ-Ú0-9]/g, ' ');
	complemento = complemento.replace(/[^a-zA-Zà-úÀ-Ú0-9]/g, ' ');
	bairro = bairro.replace(/[^a-zA-Zà-úÀ-Ú]/g, ' ');
	cidade = cidade.replace(/[^a-zA-Zà-úÀ-Ú]/g, ' ');
	name = name.toUpperCase().trim();
	endereco = endereco.toUpperCase().trim();
	complemento = complemento.toUpperCase().trim();
	bairro = bairro.toUpperCase().trim();
	cidade = cidade.toUpperCase().trim();
    cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
    cpf = cpf.replace(/(\d{3})(\d)/,"$1.$2");
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
    tel = tel.replace(/^(\d{2})(\d)/g,"($1) $2");
    cel = cel.replace(/^(\d{2})(\d)/g,"($1) $2");
    whats = whats.replace(/^(\d{2})(\d)/g,"($1) $2");
    tel = tel.replace(/(\d)(\d{4})$/,"$1-$2");
    cel = cel.replace(/(\d)(\d{4})$/,"$1-$2");
    whats = whats.replace(/(\d)(\d{4})$/,"$1-$2");
    num = num.toUpperCase();
    cep = cep.replace(/^([\d]{5})\.*([\d]{3})/, "$1-$2");

	if(!name || name.length < 3) error = true;

	if((endereco.length < 5 || !isNaN(endereco[0])) && endereco) error = true;

	if((complemento.length < 4 || !isNaN(complemento[0])) && complemento) error = true;

	if(bairro.length < 4 && bairro) error = true;

	if(cidade.length < 4 && cidade)	error = true;

	teste = /^\d{3}\.\d{3}\.\d{3}\-\d{2}$/.test(cpf);
	if(!cpf || !teste) error = true;

	teste = /^[\w-]+(\.[\w-]+)*@(([\w-]{2,63}\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/.test(email);
	if(!teste && email)	error = true;

	if(!tel && !cel && !whats) {
		help();
		error = true;
	}
	
	if((tel.length < 14 && tel) || (cel.length < 15 && cel) || (whats.length < 15 && whats)) error = true;

	if(num && num.length > 6) error = true;

	if(cep && cep.length < 9) error = true;

	if(uf && uf.length < 2)	error = true;

	teste = new Date(nasc);
	if(nasc && (teste == "Invalid Date" || nasc.split('-')[0] < 1900 || nasc.split('-')[0] > 2300))	error = true;

	document.getElementById('inputName').value = name;
	document.getElementById('inputEndereco').value = endereco;
	document.getElementById('inputEndCompl').value = complemento;
	document.getElementById('inputBairro').value = bairro;
	document.getElementById('inputCidade').value = cidade;
	document.getElementById('inputCPF').value = cpf;
	document.getElementById('inputEmail').value =email;
	document.getElementById('inputTelefone').value = tel;
	document.getElementById('inputCelular').value = cel;
	document.getElementById('inputWhatsapp').value = whats;
	document.getElementById('inputEndNumero').value = num;
	document.getElementById('inputCep').value = cep;
	document.getElementById('inputUf').value = uf;
	document.getElementById('inputNasc').value = nasc;

	document.getElementById('inputName').dispatchEvent(new Event('keyup'));
	document.getElementById('inputEndereco').dispatchEvent(new Event('keyup'));
	document.getElementById('inputEndCompl').dispatchEvent(new Event('keyup'));
	document.getElementById('inputBairro').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCidade').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCPF').dispatchEvent(new Event('keyup'));
	document.getElementById('inputEmail').dispatchEvent(new Event('keyup'));
	document.getElementById('inputTelefone').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCelular').dispatchEvent(new Event('keyup'));
	document.getElementById('inputWhatsapp').dispatchEvent(new Event('keyup'));
	document.getElementById('inputEndNumero').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCep').dispatchEvent(new Event('keyup'));
	document.getElementById('inputCep').dispatchEvent(new Event('keyup'));
	document.getElementById('inputNasc').dispatchEvent(new Event('keyup'));

	return error;
}
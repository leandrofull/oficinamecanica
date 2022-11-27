# oficinamecanica
<strong>Projeto para a Faculdade</strong>

Um projeto simples feito em PHP puro, HTML, CSS e Javascript.

O objetivo deste projeto é dar os primeiros passos rumo a um sistema de gestão de oficina mecânica.

Aqui temos: módulo de clientes, módulo de veículo, módulo de funcionários e módulo de Ordens de Serviço.

Em todos os módulos, é possível criar/editar/arquivar/desarquivar os cadastros, além de alguns recursos extras dentro do módulo de Ordens de Serviço.

Para instalar este aplicativo em sua máquina, é necessário que você renomeie os arquivos file.env, file.htaccess e public/file.htaccess para .env, .htaccess e public/.htaccess respectivamente.

Envie para o seu banco de dados (do tipo MySQL) o arquivo oficinamecanica.sql.

No arquivo .env, coloque os dados do seu banco de dados.

Configure o arquivo config.php. Neste passo, é importante que a constante DOMAIN aponte para a pasta public.

Depois disso, basta você acessar o sistema utilizando as credenciais de acesso:<br/>
Login: teste_convidado<br/>
Senha: teste123

<strong>Versão 1.1</strong><br />
-> Tamanho máximo de imagem para upload alterado de 10 MB para 4 MB;<br />
-> O número máximo de imagens para serem enviadas a cada Ordem de Serviço alterado de 10 para 6;<br />
-> Compressão de imagens ao fazer o upload adicionado, otimizando assim o tempo de carregamento ao visualizar uma Ordem de Serviço;<br />
-> Bug ao tentar alterar as informações de um veículo ou de uma Ordem de Serviço corrigido;<br />
-> Tempo de expiração de 90 dias adicionado para os links públicos gerados (ordem de serviço);<br />
-> Bug após uma ação via método POST corrigido;<br />
-> Agora, ao gerar um link público (ordem de serviço), a aplicação registra o número de Whatsapp do cliente também;<br />
-> Sistema de busca melhorado;<br />
-> Problema de não reponsividade nas tabelas corrigido;<br />
-> Problema de não reponsividade na Navbar corrigido;<br />
-> O resumo do sistema na página inicial mostrava dados incorretos. Corrigido.

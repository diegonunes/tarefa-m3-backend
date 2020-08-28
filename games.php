<?php

//  ....................................
//  Games App - Aplica��o exemplo em PHP
//  ....................................


$db  =  new PDO('mysql:host=localhost;dbname=games;charset=utf8','root','root');

function mostraTabela($qtdeColunas, $consulta, $func){

	$i = 0;
	$tab = "";

	while( $row = $consulta->fetch(PDO::FETCH_NUM))
	{
		$tab .=  "<tr valign = center>";
		$tab .=  "<td class=tabv><img src=img/sp.gif width=10 height=8></td>";
		for($j = 0; $j < $qtdeColunas; $j++){
			$tab .=  "<td class = tabv width = 180 height = 6>".htmlspecialchars($row[$j])."&nbsp;</td>";
		}
		$tab .=  "<td class = tabv><button type = \"button\" onclick = \"deleta".$func."(".htmlspecialchars($row[$j]).")\">X</button></td>";
		$tab .=  "<td class = tabv></td>";
		$tab .=  "</tr>";
		$i++;
	}
	$tab .=  "<p></p>";
	echo $tab;
}
function recuperaTabela($tabela, $db){
		$retData = array();
		$allUsers = $db->query("SELECT cod, nome FROM ".$tabela);
		foreach ($allUsers as $singleUser) {
			$retData[] = [
				'id' => $singleUser[0],
				'name' => $singleUser[1],
			];
		}
		echo json_encode($retData);
}
function mostraUsuarios($db){
		$result = $db->query("SELECT usuarios.nome,nick,cidades.nome,email,idade,usuarios.cod FROM usuarios,cidades WHERE usuarios.cidade = cidades.cod ORDER BY usuarios.nome");
		mostraTabela(5,$result,'Usuario');    //este codigo eh usado como parametro na funcao
}
function mostraJogos($db){
		$result = $db->query("SELECT titulos.nome,fabricantes.nome,preco,classificacao,titulos.cod FROM titulos,fabricantes WHERE titulos.fabricante = fabricantes.cod ORDER BY titulos.nome");
		mostraTabela(4,$result,'Jogo');
}

function mostraForums($db){
		$result = $db->query("SELECT usuarios.nome, forum.titulo, forum.mensagem, forum.cod FROM forum,usuarios WHERE forum.codUsuario = usuarios.cod");
		mostraTabela(3,$result,'Forum');
}

	if(@$_REQUEST['action'] == "recuperaCidades")     //recupera lista de nomes das cidades
	{
		recuperaTabela('cidades', $db);
	}
	if(@$_REQUEST['action'] == "recuperaFabricantes") //recupera lista de nomes dos fabricantes
	{
		recuperaTabela('fabricantes', $db);
	}
	if(@$_REQUEST['action'] == "recuperaRemetentes")     //recupera lista de nomes das cidades
	{
		recuperaTabela('usuarios', $db);
	}
	if(@$_REQUEST['action'] == "ins")  //insere novo Usuario
	{
		$nomeUsuario = $_REQUEST['usuario'];
		$nick = $_REQUEST['nick'];
		$email = $_REQUEST['email'];
		$idade = $_REQUEST['idade'];
		if($idade == "") $idade = "NULL";
		$cidade = $_REQUEST['cidade'];

		$stm = $db->prepare("INSERT INTO usuarios (nome,nick,email,idade,cidade) VALUES('$nomeUsuario','$nick','$email','$idade','$cidade');");
		$stm->execute();
		mostraUsuarios($db);
	}
	if(@$_REQUEST['action'] == "insJogo") //insere novo Jogo
	{
		$jogo = $_REQUEST['jogo'];
		$fabricante = $_REQUEST['fab'];
		$preco = floatval($_REQUEST['preco']);
		$classificacao = intval($_REQUEST['class']);

		$stm = $db->prepare("INSERT INTO titulos (nome,fabricante,preco,classificacao) VALUES('$jogo','$fabricante','$preco','$classificacao');");
		$stm->execute();
		mostraJogos($db);
	}
	if(@$_REQUEST['action'] == "insForum") //insere novo Forum
	{
		$titulo = $_REQUEST['titulo'];
		$mensagem = $_REQUEST['mensagem'];
		$remetente = $_REQUEST['remetente'];

		$stm = $db->prepare("INSERT INTO forum (codUsuario,titulo,mensagem) VALUES('$remetente','$titulo','$mensagem');");
		$stm->execute();
		mostraForums($db);
	}
	if(@$_REQUEST['action'] == "del")     //remove Usuario
	{
		$stm = $db->prepare("DELETE FROM usuarios WHERE usuarios.cod  =  ".$_REQUEST['id']);
		$stm->execute();
		mostraUsuarios($db);
	}
	if(@$_REQUEST['action'] == "delJogo") //remove Jogo
	{
		$stm = $db->prepare("DELETE FROM titulos WHERE titulos.cod  =  ".$_REQUEST['id']);
		$stm->execute();
		mostraJogos($db);
	}
	if(@$_REQUEST['action'] == "delForum") //remove Jogo
	{
		$stm = $db->prepare("DELETE FROM forum WHERE forum.cod  =  ".$_REQUEST['id']);
		$stm->execute();
		mostraForums($db);
	}

	if(@$_REQUEST['action'] == "mostraUsuarios")
	{
		mostraUsuarios($db);
	}
		if(@$_REQUEST['action'] == "mostraJogos")
	{
		mostraJogos($db);
	}
	if(@$_REQUEST['action'] == "mostraForums")
	{
		mostraForums($db);
	}
?>

